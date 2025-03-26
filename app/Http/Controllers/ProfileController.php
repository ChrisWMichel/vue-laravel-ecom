<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Inertia\Response;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserAddressUpdateRequest;
//use Illuminate\Support\Facades\Log;


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
        $user = $request->user();
        $user->fill($request->validated());
        $user->profile_completed = 1;
            
        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Address updated successfully.');
    }

    public function updateImage(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            // Remove old image if exists
            if ($request->user()->profile_image) {
                User::removeProfileImageFromStorage($request->user()->profile_image);
            }
            
            // Save new image
            $request->user()->profile_image = $this->saveImage($request->file('profile_image'));
            $request->user()->save();
        }

        return Redirect::route('profile.edit')->with('success', 'Image updated successfully.');
    }

    public function orders()
    {
        if (Auth::check()) {
            $userId = Auth::id();

            $user = User::with('orders')->find($userId);
            
            return Inertia::render('Profile/orderHistory', [
                'orders' => $user->orders
            ]);

        }

        // Redirect to login if the user is not authenticated
        return redirect()->route('user-login-view');
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
        $path = 'images/profiles';
        $image_name = time().'_'.$image->getClientOriginalName();
        $fullPath = $path.'/'.$image_name;
        
        // Get bucket name from environment
        $bucket = env('AWS_BUCKET');
        
        // Log bucket name to verify it's set
        Log::info("Using S3 bucket: " . $bucket);
        
        if (empty($bucket)) {
           // Log::error("AWS_BUCKET environment variable is not set!");
            throw new \Exception("AWS bucket name is not configured");
        }
        
        try {
            // Try a different approach to upload the file
            $fileContents = file_get_contents($image->getRealPath());
            
            // Get the S3 client directly to access more detailed error information
            $s3Client = Storage::disk('s3')->getClient();
            
            try {
                // Use the S3 client directly with explicit bucket name
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key'    => $fullPath,
                    'Body'   => $fileContents,
                    'ContentType' => $image->getMimeType()
                ]);
                
                //Log::info("S3 put successful. RequestId: " . ($result['RequestId'] ?? 'N/A'));
                
                // Construct the URL manually
                $url = "https://{$bucket}.s3." . env('AWS_DEFAULT_REGION') . ".amazonaws.com/{$fullPath}";
                
                //Log::info('Generated S3 image URL: ' . $url);
                
                return $url;
            } catch (\Aws\S3\Exception\S3Exception $e) {
                // This will catch specific AWS S3 exceptions
                Log::error("AWS S3 Exception: " . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error("General exception: " . $e->getMessage());
            throw $e;
        }
    }
}
