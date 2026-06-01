<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class FollowButton extends Component
{
    public User $user;
    public bool $isFollowing = false;
    public int $followersCount = 0;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->followersCount = $user->followers()->count();
        $this->isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;
    }

    public function toggle(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        if ($this->isFollowing) {
            auth()->user()->unfollow($this->user);
            $this->isFollowing = false;
            $this->followersCount--;
        } else {
            auth()->user()->follow($this->user);
            $this->isFollowing = true;
            $this->followersCount++;
        }

        $this->dispatch('followUpdated', followersCount: $this->followersCount, isFollowing: $this->isFollowing);
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
