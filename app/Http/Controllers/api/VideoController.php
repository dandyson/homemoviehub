<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function handleCoverImageUpload(Request $request, Video $video = null)
    {
        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:10000',
        ]);

        $path = "cover-images/{$video->id} - {$video->title}";
        $name = $request->file('cover_image')->getClientOriginalName();

        try {
            $url = Storage::disk('s3')->url("{$path}/{$name}");

            $video->cover_image = $url;
            $video->save();

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
        } catch (\Exception $e) {
            \Log::error('Error uploading cover image: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function destroy(Video $video)
    {
        $video->delete();

        // Optionally, you can return a response or redirect
        return response()->json(['success', 'Video deleted successfully']);
    }
}
