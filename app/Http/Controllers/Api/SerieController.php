<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Serie;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::with('categories')->paginate(20);

        return response()->json([
            'series' => $series,
            'message' => 'Lists series'
        ], 206);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,svg',
            'director' => 'required|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $image = $request->image;
        $categories = $request->categories;
        unset($request['image']);
        unset($request['categories']);
        
        $serie = Serie::create($request->all());
        
        // Se le asignan las categorias a la serie
        $serie->categories()->attach($categories);

        // Nombre de la imagen
        $imageName = $serie->id.'_'.Str::random(40).'.'.$image->getClientOriginalExtension();

        // Ubicacion donde se guardara la imagen
        $directory = 'public/serie';
        $path = 'serie/'.$imageName;

        $image->storeAs($directory, $imageName);

        $serie->update(['image' => $path]);

        return response()->json([
            'serie' => $serie,
            'message' => 'Serie created successfully'
        ], 201);
    }

    public function show(Serie $series)
    {
        $series->load('categories');

        return response()->json([
            'serie' => $series,
            'message' => 'Show serie'
        ], 200);
    }

    public function edit(Serie $series)
    {
        $series->load('categories');

        return response()->json([
            'serie' => $series,
            'message' => 'Show serie'
        ], 200);
    }

    public function update(Request $request, Serie $series)
    {
        $rules = [
            'name' => 'required|max:255',
            'description' => 'required',
            'director' => 'required|max:255'
        ];

        if ($request->image) {
            $rules['image'] = 'image|mimes:jpg,jpeg,png,svg';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $image = $request->image;
        $categories = $request->categories;
        unset($request['image']);
        unset($request['categories']);
        
        $series->update($request->all());

        if ($image) {
            // Se elimina imagen actual
            Storage::delete('public/'.$series->image);
            
            // Se agrega nueva imagen
            $imageName = $series->id.'_'.Str::random(40).'.'.$image->getClientOriginalExtension();

            // Ubicacion donde se guardara la imagen
            $directory = 'public/serie';
            $path = $directory.'/'.$imageName;
            
            $image->storeAs($directory, $imageName);

            $series->update(['image' => $path]);
        }

        if (count($categories) > 0) {
            $series->categories()->sync($categories);
        }

        return response()->json([
            'serie' => $series,
            'message' => 'Serie edited successfully'
        ], 200);
    }

    public function destroy(Serie $series)
    {
        $series->categories()->detach();

        if ($series->image) {
            Storage::delete('public/'.$series->image);
        }

        $series->delete();
        
        return response()->json([
            'message' => 'Serie deleted successfully'
        ], 200);
    }
}
