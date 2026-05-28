<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'full_name',
        'username',
        'email',
        'phone_number',
        'password',
        'role',
        'account_status',
        'profile_photo',
        'profile_photo_path',
        'bio',
        'location',
        'address',
        'last_login',
        'social_links',
        'cover_photo_path',
        'is_verified',
        'google_id',
        'github_id',
        'provider',
        'provider_id',
        'avatar_url',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'cover_photo_url',
    ];

    /**
     * Compatibility accessor for name -> full_name.
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['full_name'] = $value;
        unset($this->attributes['name']);
    }

    /**
     * Compatibility accessor for profile_photo_path -> profile_photo.
     */
    public function getProfilePhotoPathAttribute()
    {
        return $this->profile_photo;
    }

    public function setProfilePhotoPathAttribute($value)
    {
        $this->attributes['profile_photo'] = $value;
        unset($this->attributes['profile_photo_path']);
    }

    /**
     * Compatibility accessor for location -> address.
     */
    public function getLocationAttribute()
    {
        return $this->address;
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['address'] = $value;
        unset($this->attributes['location']);
    }

    /**
     * Update the user's cover photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateCoverPhoto($photo)
    {
        tap($this->cover_photo_path, function ($previous) use ($photo) {
            $this->forceFill([
                'cover_photo_path' => $photo->storePublicly(
                    'cover-photos', ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                \Illuminate\Support\Facades\Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's cover photo.
     *
     * @return void
     */
    public function deleteCoverPhoto()
    {
        \Illuminate\Support\Facades\Storage::disk($this->profilePhotoDisk())->delete($this->cover_photo_path);

        $this->forceFill([
            'cover_photo_path' => null,
        ])->save();
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
            'social_links' => 'array',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Get the URL to the user's cover photo.
     *
     * @return string
     */
    public function getCoverPhotoUrlAttribute()
    {
        return $this->cover_photo_path
                    ? \Illuminate\Support\Facades\Storage::disk($this->profilePhotoDisk())->url($this->cover_photo_path)
                    : $this->defaultCoverPhotoUrl();
    }

    /**
     * Get the default cover photo URL if no cover photo has been uploaded.
     *
     * @return string
     */
    protected function defaultCoverPhotoUrl()
    {
        return 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
    }

    /**
     * Get the log entries created by this admin user.
     */
    public function adminLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdminLog::class, 'admin_id', 'id_user');
    }

    /**
     * Get campaigns owned by this user.
     */
    public function campaigns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Campaign::class, 'id_user', 'id_user');
    }

    /**
     * Get donations made by this user.
     */
    public function donations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Donation::class, 'id_user', 'id_user');
    }

    /**
     * Users that this user is following.
     */
    public function following(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id', 'id_user', 'id_user')
            ->withTimestamps();
    }

    /**
     * Users that follow this user.
     */
    public function followers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id', 'id_user', 'id_user')
            ->withTimestamps();
    }

    /**
     * Check if this user is following another user.
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id_user)->exists();
    }

    /**
     * Follow a user.
     */
    public function follow(User $user): void
    {
        if ($this->id_user === $user->id_user) return;
        if (!$this->isFollowing($user)) {
            $this->following()->attach($user->id_user);
            $user->notify(new \App\Notifications\NewFollower($this));
        }
    }

    /**
     * Unfollow a user.
     */
    public function unfollow(User $user): void
    {
        $this->following()->detach($user->id_user);
    }

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the user is an admin (includes super_admin).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Check if the user has a linked social provider.
     */
    public function hasProvider(string $provider): bool
    {
        return !empty($this->{$provider . '_id'});
    }

    /**
     * Get the profile photo URL, falling back to OAuth avatar.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return \Illuminate\Support\Facades\Storage::disk($this->profilePhotoDisk())->url($this->profile_photo);
        }

        if ($this->avatar_url) {
            return $this->avatar_url;
        }

        return $this->defaultProfilePhotoUrl();
    }

    /**
     * Send the email verification notification using custom template.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }
}
