<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use Notifiable, SoftDeletes, HasRoles, HasApiTokens, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Register media collection for the user.
     */

    public function registerMediaCollections(): void {
        $this->addMediaCollection('profile_picture')
             ->useFallbackUrl(asset(config('constants.NO_IMAGE_URL')))
             ->useFallbackPath(public_path(config('constants.NO_IMAGE_URL')))
             ->singleFile();
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }

    // For get user detail
    public function getUserDetail(){
        $user = $this;

        // get user image
        $user->profile_picture = asset($user->getFirstMediaUrl('profile_picture'));

        // remove extra fields
        unset($user->email_verified_at);
        unset($user->is_active);
        unset($user->deleted_at);
        
        return $user;
    }
}
