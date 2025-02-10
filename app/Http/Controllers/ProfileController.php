<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Inertia\Response;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserAddressUpdateRequest;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
      
        return Inertia::render('Profile/Edit', [
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $request->user()->fill($request->validated());
        
        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    public function updateAddress(UserAddressUpdateRequest $request): RedirectResponse
    { 
        // $validated = $request->validated();

        // $user = $request->user();

        // $user->fill($validated);
        $request->user()->fill($request->validated());
        
        // if ($request->hasFile('profile_image')) {
        //     User::removeProfileImageFromStorage($user->profile_image);
        //     // Save new image
        //     $user->profile_image = $this->saveImage($request->file('profile_image'));
        // }

        // $user->save();
        if ($request->hasFile('profile_image')) {
            User::removeProfileImageFromStorage($request->user()->profile_image);
            $request->user()->profile_image = $this->saveImage($request->file('profile_image'));
        }
    
        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Address updated successfully.');
    }



    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function saveImage($image)
    {
        $image_name = time().'_'.$image->getClientOriginalName();
        $image->storeAs('images/profile',$image_name,'public');
        return 'storage/images/profile/'.$image_name;
    }
}
