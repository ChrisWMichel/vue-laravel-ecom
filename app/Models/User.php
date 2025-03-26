<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'profile_image', 'address', 'city', 'country', 'zip_code', 'phone_number', 'email_verified_at', 'profile_completed', 'remember_token'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'image_path',
    ];

    public function getImagePathAttribute()
    {
        if($this->profile_image) {
            return  asset($this->profile_image);
        }
        return "https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?t=st=1737485719~exp=1737489319~hmac=a05e79c263ee8756510c45f0aed52759ef11f61f24a80f34c1a6543a977d74a5&w=996";
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class)
        ->with('products')
        ->latest();
    }

    public static function removeProfileImageFromStorage($imagePath)
    {
        if (!$imagePath) {
            return;
        }
        
        // Check if it's an S3 URL
        if (strpos($imagePath, 's3.') !== false) {
            // Extract the path from the full URL
            $path = parse_url($imagePath, PHP_URL_PATH);
            $path = ltrim($path, '/');
            
            // Delete from S3
            if (Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }
        } else {
            // Handle local storage (legacy code)
            $relativePath = str_replace('images/', '', $imagePath);
            if ($relativePath && file_exists(public_path('images/'.$relativePath))) {
                unlink(public_path('images/'.$relativePath));
            }
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('F j, Y');
    }
}
