<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\DestinationRequest;

class DestinationController extends Controller
{
   
    public function index(): View
    {
        $destinations = Destination::get();

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        return view('admin.destinations.create');
    }

    public function store(DestinationRequest $request): RedirectResponse
    {
        if($request->validated()) {
            $image = $request->file('image')->store('asset/destination','public');
            Destination::create($request->except('image') + ['image' =>$image]);
        }
        

        return redirect()->route('admin.destinations.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

    public function show(Destination $destination): View
    {
        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(DestinationRequest $request, Destination $destination): RedirectResponse
    {
        if($request->validated()){
            if($request->image){
                File::delete('storage/'. $destination->image);
                $image = $request-> file('image')->store('assets/destination','public');
                $destination->update($request->except('image') + ['image' => $image]);
            } else {
                $destination->update($request->validated());
            }
        }
        return redirect()->route('admin.destinations.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        File::delete('storage/'. $destination->image);
        $destination->delete();

        return back()->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
}