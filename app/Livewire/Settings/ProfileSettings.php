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
    public string $bio = '';
    public $avatar = null;
    public $cover_photo = null;

    public function mount(): void
    {
        $user = auth()->user();
        $this->full_name = $user->full_name ?? '';
        $this->username = $user->username ?? '';
        $this->email = $user->email ?? '';
        $this->phone_number = $user->phone_number ?? '';
        $this->bio = $user->bio ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . auth()->user()->id_user . ',id_user',
            'email' => 'required|email|max:100|unique:users,email,' . auth()->user()->id_user . ',id_user',
            'phone_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $user = auth()->user();
        $user->full_name = $this->full_name;
        $user->username = $this->username;
        $user->phone_number = $this->phone_number;
        $user->bio = $this->bio;

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

        session()->flash('profile_saved', true);
    }

    public function render()
    {
        return view('livewire.settings.profile-settings');
    }
}
