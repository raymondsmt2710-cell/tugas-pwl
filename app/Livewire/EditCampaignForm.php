<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Category;
use App\Services\CampaignService;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCampaignForm extends Component
{
    use WithFileUploads;

    public Campaign $campaign;
    public string $title = '';
    public string $short_description = '';
    public string $description = '';
    public string $target_amount = '';
    public string $minimum_donation = '';
    public string $id_category = '';
    public string $video_url = '';
    public string $end_date = '';
    public $banner_image = null;
    public array $gallery_images = [];
    public array $documents = [];
    public array $remove_gallery = [];
    public array $remove_documents = [];

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign->load(['galleries', 'documents']);
        $this->title = $campaign->title;
        $this->short_description = $campaign->short_description ?? '';
        $this->description = $campaign->description ?? '';
        $this->target_amount = (string) $campaign->target_amount;
        $this->minimum_donation = (string) ($campaign->minimum_donation ?: '');
        $this->id_category = (string) $campaign->id_category;
        $this->video_url = $campaign->video_url ?? '';
        $this->end_date = $campaign->end_date?->format('Y-m-d') ?? '';
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string|min:50',
            'target_amount' => 'required|numeric|min:100000',
            'minimum_donation' => 'nullable|numeric|min:1000',
            'id_category' => 'required|exists:categories,id_category',
            'video_url' => 'nullable|url|max:500',
            'end_date' => 'required|date|after:today',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'documents.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
        ];
    }

    public function toggleRemoveGallery(int $id): void
    {
        if (in_array($id, $this->remove_gallery)) {
            $this->remove_gallery = array_diff($this->remove_gallery, [$id]);
        } else {
            $this->remove_gallery[] = $id;
        }
    }

    public function toggleRemoveDocument(int $id): void
    {
        if (in_array($id, $this->remove_documents)) {
            $this->remove_documents = array_diff($this->remove_documents, [$id]);
        } else {
            $this->remove_documents[] = $id;
        }
    }

    public function removeNewGallery(int $index): void
    {
        unset($this->gallery_images[$index]);
        $this->gallery_images = array_values($this->gallery_images);
    }

    public function removeNewDocument(int $index): void
    {
        unset($this->documents[$index]);
        $this->documents = array_values($this->documents);
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'target_amount' => $this->target_amount,
            'minimum_donation' => $this->minimum_donation ?: null,
            'id_category' => $this->id_category,
            'video_url' => $this->video_url ?: null,
            'end_date' => $this->end_date,
            'banner_image' => $this->banner_image,
            'gallery' => $this->gallery_images,
            'documents' => $this->documents,
            'remove_gallery' => $this->remove_gallery,
            'remove_documents' => $this->remove_documents,
        ];

        $service = app(CampaignService::class);
        $campaign = $service->update($this->campaign, $data);

        session()->flash('success', 'Kampanye berhasil diperbarui!');
        $this->redirect(url('/campaigns/' . $campaign->slug));
    }

    public function render()
    {
        return view('livewire.edit-campaign-form', [
            'categories' => Category::all(),
        ]);
    }
}
