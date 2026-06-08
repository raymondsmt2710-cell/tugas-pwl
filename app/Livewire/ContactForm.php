<?php

namespace App\Livewire;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
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

        try {
            // Kirim email ke email di SiteSetting
            $destination = \App\Models\SiteSetting::first()->email ?? 'tubespwlkel999@gmail.com';
            Mail::to($destination)->send(
                new ContactFormMail(
                    senderName: $this->name,
                    senderEmail: $this->email,
                    messageContent: $this->message
                )
            );

            $this->sent = true;
            $this->reset(['name', 'email', 'message']);
        } catch (\Exception $e) {
            // Log error tapi tetap tampilkan success ke user
            logger()->error('Contact form email failed: ' . $e->getMessage());
            $this->sent = true;
            $this->reset(['name', 'email', 'message']);
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
