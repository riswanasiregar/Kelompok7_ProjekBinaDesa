<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    // TAMBAHKAN METHOD INI UNTUK URL PROFILE PICTURE
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return Storage::url($this->profile_picture);
        }

        // Default profile picture jika tidak ada
        return asset('images/default-avatar.png');
    }

    // TAMBAHKAN METHOD INI UNTUK HAPUS PROFILE PICTURE
    public function deleteProfilePicture()
    {
        if ($this->profile_picture) {
            Storage::disk('public')->delete($this->profile_picture);
            $this->profile_picture = null;
            $this->save();
        }
    }
}
