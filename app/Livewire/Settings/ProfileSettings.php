<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileSettings extends Component
{
    use WithFileUploads;

    public string $full_name = '';
    public string $username = '';
    public string $email = '';
    public string $phone_number = '';
    public string $nik = '';
    public string $address = '';
    public string $bio = '';
    public string $bank_account_number = '';
    public $avatar = null;
    public $cover_photo = null;

    public function mount(): void
    {
        $user = auth()->user();
        $this->full_name = $user->full_name ?? '';
        $this->username = $user->username ?? '';
        $this->email = $user->email ?? '';
        $this->phone_number = $user->phone_number ?? '';
        $this->nik = $user->nik ?? '';
        $this->address = $user->address ?? '';
        $this->bio = $user->bio ?? '';
        $this->bank_account_number = $user->bank_account_number ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . auth()->user()->id_user . ',id_user',
            'email' => 'required|email|max:100|unique:users,email,' . auth()->user()->id_user . ',id_user',
            'phone_number' => 'nullable|string|max:20',
            'nik' => 'nullable|numeric|digits:16|unique:users,nik,' . auth()->user()->id_user . ',id_user',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|min:50|max:500',
            'bank_account_number' => 'nullable|string|max:30',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'bank_account_number.max' => 'Nomor rekening tidak boleh lebih dari 30 karakter.',
        ]);

        $user = auth()->user();
        $user->full_name = $this->full_name;
        $user->username = $this->username;
        $user->phone_number = $this->phone_number;
        $user->nik = $this->nik;
        $user->address = $this->address;
        $user->bio = $this->bio;
        $user->bank_account_number = $this->bank_account_number;

        if ($this->email !== $user->email) {
            $user->email = $this->email;
            $user->email_verified_at = null;
        }

        if ($this->avatar) {
            $path = $this->avatar->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        if ($this->cover_photo) {
            $path = $this->cover_photo->store('cover-photos', 'public');
            $user->cover_photo_path = $path;
        }

        $user->save();
        $this->avatar = null;
        $this->cover_photo = null;

        $this->dispatch('profile-updated', 
            isComplete: $user->isProfileComplete(),
            missingFields: implode(', ', $user->missingProfileFields())
        );

        $this->dispatch('flash', message: 'Profil berhasil disimpan.', type: 'success');
    }

    public function updatedCoverPhoto(): void
    {
        $this->validateOnly('cover_photo', [
            'cover_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    public function updatedAvatar(): void
    {
        $this->validateOnly('avatar', [
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    public function render()
    {
        return view('livewire.settings.profile-settings');
    }
}
