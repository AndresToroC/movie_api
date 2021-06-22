<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Models\Movie;

class MovieController extends Controller
{
    public function __construct() {
        $this->middleware('role:admin')->except(['index', 'show']);
    }
    
    public function index(Request $request)
    {
        $movies = Movie::with('comments')->whereNull('serie_id')->searchAndPaginate();

        return response()->json([
            'movies' => $movies,
            'message' => 'Lists movies'
        ], 206);
    }

    public function create()
    {
        //
    }
    
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'video' => 'required|mimes:mp4',
            'image' => 'image|mimes:jpg,png,jpeg,svg',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }
        
        $video = $request->video;
        $image = $request->image;

        unset($request['video']);
        unset($request['image']);

        $movie = Movie::create($request->all());

        if ($video) {
            $videoName = $movie->id.'_'.Str::random(40).'.'.$video->getClientOriginalExtension();

            $directoryVideo = 'public/movie/video';
            $pathVideo = 'movie/video/'.$videoName;

            $video->storeAs($directoryVideo, $videoName);

            $movie->update(['video' => $pathVideo]);
        }

        if ($image) {
            $imageName = $movie->id.'_'.Str::random(40).'.'.$image->getClientOriginalExtension();

            $directoryImage = 'public/movie/image';
            $pathImage = 'movie/image/'.$imageName;

            $image->storeAs($directoryImage, $imageName);

            $movie->update(['image' => $pathImage]);
        }

        return response()->json([
            'movie' => $movie,
            'message' => 'Movie created successfully'
        ], 200);
    }

    public function show(Movie $movie)
    {
        $movie->load('serie');

        return response()->json([
            'movie' => $movie,
            'message' => 'Show movie'
        ], 200);
    }

    public function edit(Movie $movie)
    {
        $movie->load('serie');

        return response()->json([
            'movie' => $movie,
            'message' => 'Show movie'
        ], 200);
    }
    
    public function update(Request $request, Movie $movie)
    {
        $rules = [
            'name' => 'required|max:255'
        ];

        if ($request->video) {
            $rules['video'] = 'mimes:mp4';
        }

        if ($request->image) {
            $rules['image'] = 'image|mimes:jpg,png,jpeg,svg';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }
        
        $video = $request->video;
        $image = $request->image;

        unset($request['video']);
        unset($request['image']);

        $movie->update($request->all());

        if ($video) {
            Storage::delete('public/'.$movie->video);

            $videoName = $movie->id.'_'.Str::random(40).'.'.$video->getClientOriginalExtension();

            $directoryVideo = 'public/movie/video';
            $pathVideo = 'movie/video/'.$videoName;

            $video->storeAs($directoryVideo, $videoName);

            $movie->update(['video' => $pathVideo]);
        }

        if ($image) {
            Storage::delete('public/'.$movie->image);

            $imageName = $movie->id.'_'.Str::random(40).'.'.$image->getClientOriginalExtension();

            $directoryImage = 'public/movie/image';
            $pathImage = 'movie/image/'.$imageName;

            $image->storeAs($directoryImage, $imageName);

            $movie->update(['image' => $pathImage]);
        }

        return response()->json([
            'movie' => $movie,
            'message' => 'Movie edited successfully'
        ], 200);
    }

    public function destroy(Movie $movie)
    {
        if ($movie->video) {
            Storage::delete('public/'.$movie->video);
        }

        if ($movie->image) {
            Storage::delete('public/'.$movie->image);
        }

        $movie->delete();
        
        return response()->json([
            'message' => 'Movie deleted successfully'
        ], 200);
    }
}
