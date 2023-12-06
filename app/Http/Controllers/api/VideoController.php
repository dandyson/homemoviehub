<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function handleCoverImageUpload(Request $request, Video $video = null)
    {
        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:10000',
        ]);

        $path = "cover-images/{$video->id} - {$video->title}";
        $name = $request->file('cover_image')->getClientOriginalName();

        $request->file('cover_image')->storeAs(
            $path,
            $name,
            's3'
        );

        $video->cover_image = Storage::disk('s3')->url("{$path}/{$name}");
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
