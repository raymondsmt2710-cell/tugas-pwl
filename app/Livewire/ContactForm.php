<?php

namespace App\Livewire;

use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';
    public bool $sent = false;

    protected $rules = [
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'message' => 'required|string|min:10|max:1000',
    ];

    public function send(): void
    {
        $this->validate();

        // In production, send email or store in database
        // For now, just mark as sent
        $this->sent = true;
        $this->reset(['name', 'email', 'message']);
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
