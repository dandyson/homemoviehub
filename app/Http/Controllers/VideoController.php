<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'youtube_url' => 'required|url',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'featured_users' => 'nullable|array',
        ]);

        // $coverImage = $request->file('cover_image')->store('covers', 'public');

        $video = new Video([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'youtube_url' => $request->input('youtube_url'),
            // 'cover_image' => $coverImage,
            // 'featured_users' => json_encode($request->input('featured_users')),
            'added_by' => Auth::id(),
        ]);
    
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
            // 'cover_image' => 'nullable|string',
            // 'featured_users' => 'nullable',
        ]);

        // Update video attributes
        $video->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'youtube_url' => $request->input('youtube_url'),
            // 'cover_image' => $request->input('cover_image'),
            // 'featured_users' => $request->input('featured_users'),
        ]);

        return Inertia::render('Video/VideoShow', [
            'video' => $video->only('id', 'title', 'description', 'youtube_url', 'cover_image', 'featured_users'),
            'message' => ['type' => 'Success', 'text' => 'Video updated successfully'],
        ])->withViewData(['url' => route('video.show', ['video' => $video->id])]);
        
        
    }

    public function destroy(Video $video)
    {
        $video->delete();

        // Optionally, you can return a response or redirect
        return response()->json(['success', 'Video deleted successfully']);
    }
}
