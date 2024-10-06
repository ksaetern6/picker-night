<?php

namespace App\Http\Controllers;

use App\Models\Options;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $options = Session::get('options', []);
        $filters = Session::get('filters', []);

        return Inertia::render('Home', [
            'options' => data_get($options, 'options', []),
            'optionsId' => data_get($options, 'id', ''),
            'filters' => $filters,
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'options' => 'required|array'
        ]);

        $model = Options::create($validated);

        Session::put('options', [
            'options' => $model->options,
            'id' => $model->id
        ]);
        Session::save();

        return new JsonResponse(
            data: ['id' => $model->id],
            status: 201);
    }

    public function update(Request $request)
    { 
        $validated = $request->validate([
            'options' => 'required|array',
            'options_id' => 'required|string',
        ]);

        $model = Options::where('id', $validated['options_id'])->first();
        $model->options = $validated['options'];
        $model->save();
        
        Session::put('options', [
            'options' => $validated['options'],
            'id' => $validated['options_id']
        ]);
        Session::save();
    
        return new JsonResponse(
            data: ['id' => $model->id],
            status: 201);
    }
}
