<?php

namespace App\Http\Controllers;

use App\Http\Resources\FilterCollection;
use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        $filters = Session::get('filters'); // refactor to DTO

        if (! $filters) {
            $models = Filter::factory()->count(2)->create();

            $filters = collect([
                'disabled' => $models,
                'enabled' => collect([]),
            ]);

            Session::put('filters', $filters);
        }

        return new FilterCollection(collect($filters));
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

        Session::put('filters.enabled', collect($enabledFilters));
        Session::put('filters.disabled', collect($disabledFilters));

        return response()->noContent();
    }
}
