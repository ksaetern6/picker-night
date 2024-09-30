<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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
        info('wtf');

        $validated = $request->validate([
            'filters' => 'required|array',
            'filters.*.name' => 'required|string',
            'filters.*.options' => 'present|array',
        ]);

        $filters = collect($validated['filters'])->map(function ($filter) {
            return [
                'id' => Str::uuid(),
                'name' => $filter['name'],
                'options' => json_encode($filter['options']),
            ];
        });

        Filter::insert($filters->toArray());

        $data = $filters->map(function ($f) {
            return [
                'id' => $f['id'],
                'name' => $f['name'],
                'options' => json_decode($f['options']),
            ];
        });

        // session
        $disabledFilters = Session::get('filters.disabled');
        $enabledFilters = Session::get('filters.enabled');

        Session::put(
            'filters.disabled',
            $disabledFilters->merge($data)
        );
        Session::put('filters.enabled', $enabledFilters);

        Session::save();

        return response()->json(data: $data, status: 201);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'disabled' => 'array|present',
            'enabled' => 'present',
        ]);

        $enabledIds = collect($validated['enabled'])->pluck('id');
        $disabledIds = collect($validated['disabled'])->pluck('id');

        $enabledFilters = Filter::whereIn('id', $enabledIds)->get();
        $disabledFilters = Filter::whereIn('id', $disabledIds)->get();

        // session
        Session::put('filters.enabled', collect($enabledFilters));
        Session::put('filters.disabled', collect($disabledFilters));

        return response()->noContent();
    }
}
