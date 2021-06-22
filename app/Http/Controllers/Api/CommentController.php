<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Movie;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(Movie $movie)
    {
        //
    }

    public function create(Movie $movie)
    {
        //
    }

    public function store(Request $request, Movie $movie)
    {
        $rules = [
            'user_id' => 'required',
            'comment' => 'required|max:255',
            'assessment' => 'required|numeric|min:1|max:5'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $comment = new Comment($request->all());
        $movie->comments()->save($comment);

        return response()->json([
            'message' => 'Comment created successfully'
        ], 200);
    }

    public function show(Movie $movie)
    {
        //
    }

    public function edit(Movie $movie)
    {
        //
    }

    public function update(Request $request, Movie $movie, Comment $comment)
    {
        $rules = [
            'user_id' => 'required',
            'comment' => 'required|max:255',
            'assessment' => 'required|numeric|min:1|max:5'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $comment->update($request->all());

        return response()->json([
            'message' => 'Comment edited successfully'
        ], 200);
    }

    public function destroy(Movie $movie, Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
