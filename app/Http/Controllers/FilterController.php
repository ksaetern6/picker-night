<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        $filters = Session::get('filters'); // refactor to DTO

        if (! $filters) {
            $filters = collect([
                'disabled' => collect([]),
                'enabled' => collect([]),
            ]);

            Session::put('filters', $filters);
        }

        return new JsonResource($filters);
    }

    public function post(Request $request)
    {
        $validated = $request->validate([
            'filter' => 'required|array',
            'filter.name' => 'required|string',
            'filter.enabled' => 'required|boolean',
            'filter.options' => 'present|array',
        ]);

        $filter = Filter::create($validated['filter']);

        // session
        Session::push('filters', array_merge(
            ['id' => $filter->id],
            $validated['filter'],
        ));

        Session::save();

        return response()->json(data: $filter, status: 201);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'sometimes|boolean',
            'options' => 'sometimes|array',
            'id' => 'required|string',
        ]);

        // model update
        $filter = Filter::where('id', $validated['id'])
            ->update(Arr::except($validated, 'id'));

        // session update
        $sessionFilters = Session::get('filters', []);
        if (!$sessionFilters) {
            // return error
        }

        // possible de-sync issue?
        $newFilters = collect($sessionFilters)->map(function ($f) use ($validated) {
            if ($f['id'] == $validated['id']) {
                foreach(array_keys(Arr::except($validated, 'id')) as $key) {
                    $f[$key] = $validated[$key];
                }
            }
            return $f;
        });

        Session::put('filters', $newFilters);
        Session::save();

        return response()->json(data: $filter, status: 201);
    }
}
