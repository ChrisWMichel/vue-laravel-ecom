<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddSizeRequest;
use App\Http\Requests\UpdateSizeRequest;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/sizes/index')->with([
            'sizes' => Size::latest()->paginate(10)->withQueryString()
         ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/sizes/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddSizeRequest $request)
    {
        if($request->validated()) {
            Size::create($request->validated());

            return redirect()->route('admin.sizes.index')->with('success', 'Size added successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Size $size)
    {
        return Inertia::render('admin/sizes/edit')->with([
            'size' => $size
         ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSizeRequest $request, Size $size)
    {
        if ($size->update($request->validated())) {
            return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully');
        } else {
            return redirect()->route('admin.sizes.index')->with('error', 'Failed to update size');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()->route('admin.sizes.index');
    }
}
