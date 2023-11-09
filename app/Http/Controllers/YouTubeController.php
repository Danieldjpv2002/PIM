<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class YouTubeController extends Controller
{
    public function audio(Request $request, string $media_id)
    {
        $path = "../storage/youtube/{$media_id}.mp3";
        $audio = file_get_contents($path);
        // unlink($path);
        return response($audio, 200, ['Content-Type' => 'audio/mp3']);
    }

    public function video(Request $request, string $media_id)
    {
        $path = "../storage/youtube/{$media_id}.mp4";
        $video = file_get_contents($path);
        // unlink($path);
        return response($video, 200, ['Content-Type' => 'video/mp4']);
    }
}
