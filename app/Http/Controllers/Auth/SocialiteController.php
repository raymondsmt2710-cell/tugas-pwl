<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    /**
     * Supported OAuth providers.
     */
    protected array $providers = ['google'];

    /**
     * Redirect the user to the OAuth provider.
     */
    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the OAuth provider.
     *
     * Logic:
     * 1. Try to find user by provider_id (google_id / github_id)
     * 2. If not found, try to find by email (account linking)
     * 3. If found by email, link the provider to existing account
     * 4. If not found at all, create a new user
     * 5. Sync avatar from provider
     * 6. Mark email as verified
     */
    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::warning("OAuth callback failed for provider [{$provider}]", [
                'error' => $e->getMessage(),
            ]);

            return redirect('/login')->with('error', 'Autentikasi gagal. Silakan coba lagi.');
        }

        // Validate that we received an email from the provider
        if (empty($socialUser->getEmail())) {
            return redirect('/login')->with('error', 'Tidak dapat mengambil email dari akun ' . ucfirst($provider) . '. Pastikan email Anda publik.');
        }

        $user = DB::transaction(function () use ($socialUser, $provider) {
            return $this->findOrCreateUser($socialUser, $provider);
        });

        // Check if account is suspended
        if ($user->account_status === 'suspended') {
            return redirect('/login')->with('error', 'Akun Anda telah ditangguhkan. Hubungi administrator.');
        }

        // Login the user
        Auth::login($user, remember: true);

        // Update last login timestamp
        $user->forceFill(['last_login' => now()])->save();

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Find an existing user or create a new one.
     * Handles account linking when email matches.
     */
    protected function findOrCreateUser($socialUser, string $provider): User
    {
        $providerIdColumn = $provider . '_id';
        $providerId = $socialUser->getId();
        $email = $socialUser->getEmail();

        // Step 1: Find by provider ID (exact match — user has logged in with this provider before)
        $user = User::where($providerIdColumn, $providerId)->first();

        if ($user) {
            $this->syncUserFromProvider($user, $socialUser, $provider);
            return $user;
        }

        // Step 2: Find by email (account linking — user registered via email/password or another provider)
        $user = User::where('email', $email)->first();

        if ($user) {
            // Link this provider to the existing account
            $user->forceFill([
                $providerIdColumn => $providerId,
                'provider' => $provider,
                'provider_id' => $providerId,
            ])->save();

            $this->syncUserFromProvider($user, $socialUser, $provider);
            return $user;
        }

        // Step 3: No existing user found — create a new account
        return $this->createUserFromProvider($socialUser, $provider);
    }

    /**
     * Create a new user from OAuth provider data.
     */
    protected function createUserFromProvider($socialUser, string $provider): User
    {
        $providerIdColumn = $provider . '_id';
        $name = $socialUser->getName() ?? $socialUser->getNickname() ?? 'User';

        $user = User::create([
            'full_name' => $name,
            'email' => $socialUser->getEmail(),
            'username' => $this->generateUniqueUsername($name),
            'password' => Hash::make(Str::random(32)),
            'role' => 'user',
            'account_status' => 'active',
            $providerIdColumn => $socialUser->getId(),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar_url' => $socialUser->getAvatar(),
            'email_verified_at' => now(),
            'is_verified' => false,
        ]);

        return $user;
    }

    /**
     * Sync user profile data from the OAuth provider.
     * Updates avatar and marks email as verified.
     */
    protected function syncUserFromProvider(User $user, $socialUser, string $provider): void
    {
        $updates = [];

        // Sync avatar if user hasn't uploaded a custom profile photo
        $avatar = $socialUser->getAvatar();
        if ($avatar && !$user->profile_photo) {
            $updates['avatar_url'] = $avatar;
        }

        // Mark email as verified (provider has already verified it)
        if (!$user->email_verified_at) {
            $updates['email_verified_at'] = now();
        }

        // Update provider tracking fields
        $updates['provider'] = $provider;
        $updates['provider_id'] = $socialUser->getId();

        if (!empty($updates)) {
            $user->forceFill($updates)->save();
        }
    }

    /**
     * Generate a unique username from the user's name.
     */
    protected function generateUniqueUsername(string $name): string
    {
        $baseUsername = Str::slug($name);

        // Ensure base username is not empty
        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }

        // Truncate to reasonable length
        $baseUsername = Str::limit($baseUsername, 50, '');

        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '-' . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Redirect user based on their role after login.
     */
    protected function redirectBasedOnRole(User $user)
    {
        return redirect('/');
    }
}
