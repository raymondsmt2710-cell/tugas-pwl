<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Livewire\Component;

class DeleteAccount extends Component
{
    public string $password = '';
    public bool $confirming = false;

    public function confirmDeletion(): void
    {
        $this->confirming = true;
    }

    public function cancel(): void
    {
        $this->confirming = false;
        $this->password = '';
    }

    public function deleteAccount(): void
    {
        $this->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'Password tidak valid.');
            return;
        }

        $deleter = app(DeletesUsers::class);
        $deleter->delete(auth()->user()->fresh());

        Auth::logout();
        $this->redirect('/');
    }

    public function render()
    {
        return view('livewire.settings.delete-account');
    }
}
