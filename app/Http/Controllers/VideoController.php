<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Video;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function show(Video $video)
    {
        // Ensure the user can only view their own video
        if (Gate::denies('show', $video)) {
            abort(403, 'Unauthorized action.');
        }

        $video->load('people', 'locations');

        return Inertia::render('Video/VideoShow', [
            'video' => $video,
        ]);
    }

    public function create()
    {
        return Inertia::render('Video/VideoDetails', [
            'people' => Auth::user()->people,
        ]);
    }

    public function edit(Video $video)
    {
        // Ensure the user can only edit their own video
        if (Gate::denies('edit', $video)) {
            abort(403, 'Unauthorized action.');
        }

        $video->load('people', 'locations');

        return Inertia::render('Video/VideoDetails', [
            'updateMode' => true,
            'video' => $video,
            'people' => Auth::user()->people,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'youtube_url' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]{11}$/'],
            'featured_people' => 'nullable|array',
            'locations' => 'nullable|array',
        ]);

        $video = new Video([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'youtube_url' => $request->input('youtube_url'),
            'user_id' => Auth::id(),
        ]);

        $video->save();

        // Add locations if any have been added
        if ($request->locations) {
            $this->storeVideoLocations($video, $request->locations);
        }

        // Attach the validated user IDs to the video
        if ($request->featured_people) {
            foreach ($request->featured_people as $person) {
                $video->people()->attach($person['id']);
            }
        }

        if ($request->cover_image) {
            $video->cover_image = config('app.default_cover_image');
        }

        $video->save();

        return response()->json([
            'video' => $video->only('id', 'title', 'description', 'youtube_url'),
            'message' => ['type' => 'Success', 'text' => 'Video created successfully'],
        ]);
    }

    public function update(Request $request, Video $video)
    {
        // Ensure the user can only update their own video
        if (Gate::denies('update', $video)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'youtube_url' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]{11}$/'],
            'featured_people' => 'nullable|array',
        ]);

        // Update video with extracted YouTube video ID
        $video->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'youtube_url' => $request->input('youtube_url'),
        ]);

        $video->save();

        // Get the array of person IDs from the request
        $featuredPeopleIds = collect($request->featured_people)->pluck('id')->toArray();

        // Sync the person IDs with the video's people relationship
        $video->people()->sync($featuredPeopleIds);

        // Add locations if any have been added
        if ($request->locations) {
            $this->storeVideoLocations($video, $request->locations);
        }

        $video->save();

        $video->load('people', 'locations');

        return Inertia::render('Video/VideoShow', [
            'video' => $video,
            'message' => ['type' => 'Success', 'text' => 'Video updated successfully'],
        ]);
    }

    public function destroy(Video $video)
    {
        // Ensure the user can only update their own video
        if (Gate::denies('update', $video)) {
            abort(403, 'Unauthorized action.');
        }

        $video->delete();

        return response()->json(['success' => 'Video deleted successfully']);
    }

    public function handleCoverImageUpload(Request $request, Video $video = null)
    {
        $request->validate([
            'cover_image' => [
                'required',
                File::types(['jpeg', 'png', 'jpg'])
                    ->max(12 * 1024),
            ],
        ]);

        $path = "cover-images/{$video->id} - {$video->title}";
        $name = $request->file('cover_image')->getClientOriginalName();

        DB::transaction(function () use ($video, $request, $path, $name) {
            if ($video->cover_image_upload_count >= 10) {
                abort(400,'Error: Upload limit reached for this video.');
            }
        
            // Update the video with the new cover image URL
            $video->cover_image = Storage::disk('s3')->url("{$path}/{$name}");
            $video->save();
        });

        // Perform the actual upload
        try {        
            $request->file('cover_image')->storeAs($path, $name, 's3');

            // Increment the upload count
            $video->cover_image_upload_count += 1;
            $video->save();
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Image uploaded successfully',
        ]);
    }

    public function storeVideoLocations(Video $video, $locations): void
    {
        DB::transaction(function () use ($video, $locations) {
            // Detach all existing locations from the video
            $video->locations()->detach();

            foreach ($locations as $locationData) {
                // Search for an existing location with the same coordinates
                $existingLocation = Location::where('lat', $locationData['lat'])
                    ->where('lng', $locationData['lng'])
                    ->first();

                if ($existingLocation) {
                    // Location already exists, attach it to the video
                    $video->locations()->attach($existingLocation->id);
                } else {
                    // Location doesn't exist, create a new one and attach it to the video
                    $newLocation = Location::create([
                        'location' => $locationData['location'],
                        'lat' => $locationData['lat'],
                        'lng' => $locationData['lng'],
                    ]);

                    $video->locations()->attach($newLocation->id);
                }
            }
        });
    }
}
