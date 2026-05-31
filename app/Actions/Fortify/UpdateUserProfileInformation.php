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
        $input = Validator::make($input, [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'username' => ['sometimes', 'required', 'string', 'alpha_dash', 'max:255', Rule::unique('users', 'username')->ignore($user->getKey(), 'id_user')],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->getKey(), 'id_user')],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'cover_photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_links' => ['sometimes', 'nullable', 'array'],
            'social_links.twitter' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_links.facebook' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_links.instagram' => ['sometimes', 'nullable', 'string', 'max:255'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (isset($input['cover_photo'])) {
            $user->updateCoverPhoto($input['cover_photo']);
        }

        $email = $input['email'] ?? $user->email;

        if ($email !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'] ?? $user->name,
                'username' => $input['username'] ?? $user->username,
                'email' => $email,
                'bio' => $input['bio'] ?? $user->bio,
                'location' => $input['location'] ?? $user->location,
                'social_links' => array_key_exists('social_links', $input) ? $input['social_links'] : $user->social_links,
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
            'name' => $input['name'] ?? $user->name,
            'email' => $input['email'] ?? $user->email,
            'email_verified_at' => null,
            'bio' => $input['bio'] ?? $user->bio,
            'location' => $input['location'] ?? $user->location,
            'social_links' => array_key_exists('social_links', $input) ? $input['social_links'] : $user->social_links,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
