<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Category;
use App\Services\CampaignService;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCampaignForm extends Component
{
    use WithFileUploads;

    // Form fields
    public string $title = '';
    public string $short_description = '';
    public string $description = '';
    public string $target_amount = '';
    public string $minimum_donation = '';
    public string $id_category = '';
    public string $video_url = '';
    public string $end_date = '';

    // File uploads
    public $banner_image = null;
    public array $gallery_images = [];
    public array $documents = [];

    // State
    public bool $saved = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string|min:50',
            'target_amount' => 'required|numeric|min:100000|max:99999999999',
            'minimum_donation' => 'nullable|numeric|min:1000',
            'id_category' => 'required|exists:categories,id_category',
            'video_url' => 'nullable|url|max:500',
            'end_date' => 'required|date|after:today',
            'banner_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'documents.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'Judul kampanye wajib diisi.',
            'short_description.required' => 'Deskripsi singkat wajib diisi.',
            'description.min' => 'Deskripsi lengkap minimal 50 karakter.',
            'target_amount.min' => 'Target donasi minimal Rp 100.000.',
            'minimum_donation.min' => 'Minimum donasi minimal Rp 1.000.',
            'end_date.after' => 'Tanggal berakhir harus setelah hari ini.',
            'banner_image.required' => 'Gambar sampul wajib diunggah.',
            'banner_image.max' => 'Gambar sampul maksimal 2MB.',
            'gallery_images.*.max' => 'Setiap gambar galeri maksimal 2MB.',
            'documents.*.max' => 'Setiap dokumen maksimal 5MB.',
            'documents.*.mimes' => 'Format dokumen: PDF, Word, Excel, atau PowerPoint.',
        ];
    }

    public function updatedBannerImage(): void
    {
        $this->validateOnly('banner_image');
    }

    public function updatedGalleryImages(): void
    {
        $this->validateOnly('gallery_images.*');
        // Limit to 5
        if (count($this->gallery_images) > 5) {
            $this->gallery_images = array_slice($this->gallery_images, 0, 5);
            $this->addError('gallery_images', 'Maksimal 5 gambar galeri.');
        }
    }

    public function updatedDocuments(): void
    {
        $this->validateOnly('documents.*');
        if (count($this->documents) > 5) {
            $this->documents = array_slice($this->documents, 0, 5);
            $this->addError('documents', 'Maksimal 5 dokumen.');
        }
    }

    public function removeGalleryImage(int $index): void
    {
        unset($this->gallery_images[$index]);
        $this->gallery_images = array_values($this->gallery_images);
    }

    public function removeDocument(int $index): void
    {
        unset($this->documents[$index]);
        $this->documents = array_values($this->documents);
    }

    public function removeBanner(): void
    {
        $this->banner_image = null;
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
        ];

        $service = app(CampaignService::class);
        $service->create($data, auth()->user());

        $this->saved = true;
        session()->flash('success', 'Kampanye berhasil dibuat! Silakan ajukan untuk review.');
        $this->redirect(url('/my-campaigns'));
    }

    public function render()
    {
        return view('livewire.create-campaign-form', [
            'categories' => Category::all(),
        ]);
    }
}
