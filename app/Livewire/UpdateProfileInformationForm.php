<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm as JetstreamUpdateProfileInformationForm;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends JetstreamUpdateProfileInformationForm
{
    use WithFileUploads;

    public $cover_photo;

    /**
     * Prepare the component state.
     *
     * @return void
     */
    public function mount()
    {
        parent::mount();

        $this->state['name'] = $this->user->name;
        $this->state['location'] = $this->user->location;

        if (empty($this->state['social_links'])) {
            $this->state['social_links'] = [
                'twitter' => '',
                'facebook' => '',
                'instagram' => '',
            ];
        } else {
            $this->state['social_links'] = array_merge([
                'twitter' => '',
                'facebook' => '',
                'instagram' => '',
            ], (array) $this->state['social_links']);
        }
    }

    public function updateProfileInformation(\Laravel\Fortify\Contracts\UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        $updater->update(
            $this->user,
            array_merge($this->state, [
                'photo' => $this->photo,
                'cover_photo' => $this->cover_photo,
            ])
        );

        // Always redirect back to refresh images and clear temporary uploads
        return redirect()->route('profile.show');
    }
}
