<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $showDropdown = false;

    private function user(): ?User
    {
        return Auth::user();
    }

    #[Computed]
    public function unreadCount(): int
    {
        $user = $this->user();

        if (! $user) {
            return 0;
        }

        return $user->unreadNotifications()->count();
    }

    #[Computed]
    public function notifications()
    {
        $user = $this->user();

        if (! $user) {
            return collect();
        }

        return $user->notifications()->take(5)->get();
    }

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead(string $id): void
    {
        $user = $this->user();
        if (! $user) {
            return;
        }

        $notification = $user->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        $this->redirect(url('/notifications'));
    }

    public function markAllAsRead(): void
    {
        $user = $this->user();
        if (! $user) {
            return;
        }

        $user->unreadNotifications->markAsRead();
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
