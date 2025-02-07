<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddColorRequest;
use App\Http\Requests\UpdateColorRequest;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/colors/index')->with([
            'colors' => Color::latest()->paginate(10)->withQueryString()
         ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/colors/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddColorRequest $request)
    {
        if($request->validated()) {
            Color::create($request->validated());

            return redirect()->route('admin.colors.index')->with('success', 'Color added successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        return Inertia::render('admin/colors/edit')->with([
            'color' => $color
         ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColorRequest $request, Color $color)
    {

        if ($color->update($request->validated())) {
            return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully');
        } else {
            return redirect()->route('admin.colors.index')->with('error', 'Failed to update color');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        $color->delete();

        return redirect()->route('admin.colors.index');
    }
}
