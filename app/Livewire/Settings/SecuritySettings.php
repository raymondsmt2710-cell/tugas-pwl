<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class SecuritySettings extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $passwordUpdated = false;
    public bool $resetSent = false;

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'Password saat ini tidak valid.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->passwordUpdated = true;
    }

    public function sendResetLink(): void
    {
        $email = auth()->user()->email;

        // Send reset link
        Password::sendResetLink(['email' => $email]);

        // Logout so user can access the reset password page from email
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        session()->flash('status', 'Link reset password telah dikirim ke ' . $email . '. Silakan cek email Anda.');
        $this->redirect('/login');
    }

    public function render()
    {
        return view('livewire.settings.security-settings');
    }
}
