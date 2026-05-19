<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('users', 'username')->ignore($user->getKey(), 'id_user')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->getKey(), 'id_user')],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'cover_photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'array'],
            'social_links.twitter' => ['nullable', 'string', 'max:255'],
            'social_links.facebook' => ['nullable', 'string', 'max:255'],
            'social_links.instagram' => ['nullable', 'string', 'max:255'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (isset($input['cover_photo'])) {
            $user->updateCoverPhoto($input['cover_photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'username' => $input['username'],
                'email' => $input['email'],
                'bio' => $input['bio'] ?? null,
                'location' => $input['location'] ?? null,
                'social_links' => $input['social_links'] ?? null,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'bio' => $input['bio'] ?? null,
            'location' => $input['location'] ?? null,
            'social_links' => $input['social_links'] ?? null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
