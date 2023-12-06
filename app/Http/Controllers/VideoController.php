<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
    }

    public function show(Video $video)
    {
        $video->featured_users = collect($video->featured_users)->map(function ($userId) {
            $user = optional(User::find($userId));
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        return Inertia::render('Video/VideoShow', [
            'video' => $video,
        ]);
    }

    public function create()
    {
        return Inertia::render('Video/VideoDetails');
    }

    public function edit(Video $video)
    {
        $video->featured_users = collect($video->featured_users)->map(function ($userId) {
            $user = optional(User::find($userId));
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        return Inertia::render('Video/VideoDetails', [
            'edit' => true,
            'video' => $video,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->getValidationRules($request));

        $video = new Video($request->only(['title', 'description', 'youtube_url']));
        $video->added_by = Auth::id();

        $this->handleCoverImageUpload($request, $video);

        $video->save();

        return Inertia::render('Video/VideoShow', [
            'video' => $video->only('id', 'title', 'description', 'youtube_url', 'cover_image', 'featured_users'),
            'message' => ['type' => 'Success', 'text' => 'Video created successfully'],
        ])->with(['url' => route('video.show', ['video' => $video->id])]);
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'youtube_url' => 'required|url',
            'featured_users' => 'nullable|array',
        ]);

        $video->update($request->only(['title', 'description', 'youtube_url']));
        $video->save();
        return Inertia::render('Video/VideoShow', [
            'video' => $video->only('id', 'title', 'description', 'youtube_url', 'featured_users'),
            'message' => ['type' => 'Success', 'text' => 'Video updated successfully'],
        ])->withViewData(['url' => route('video.show', ['video' => $video->id])]);
    }

    private function handleCoverImageUpload(Request $request, Video $video = null)
    {
        $request->validate([
            'cover-image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = "cover-images/{$video->id} - {$video->title}";
        $name = $request->file('avatar')->getClientOriginalName();

        $request->file('avatar')->storeAs(
            $path,
            $name,
            'cover_images'
        );

        $video->cover_image = Storage::disk('cover_images')->url("{$path}/{$name}");
        $video->save();

        return response()->json([
            'message' => 'Image uploaded successfully',
        ]);
    }
    public function destroy(Video $video)
    {
        $video->delete();

        // Optionally, you can return a response or redirect
        return response()->json(['success', 'Video deleted successfully']);
    }
}
