<?php

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'min:50'],
            'target_amount' => ['required', 'numeric', 'min:100000', 'max:99999999999'],
            'minimum_donation' => ['nullable', 'numeric', 'min:1000'],
            'id_category' => ['required', 'exists:categories,id_category'],
            'banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'gallery' => ['nullable', 'array', 'max:5'],
            'remove_gallery' => ['nullable', 'array'],
            'remove_gallery.*' => ['integer', 'exists:campaign_galleries,id'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'end_date' => ['required', 'date', 'after:today'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'judul kampanye',
            'short_description' => 'deskripsi singkat',
            'description' => 'deskripsi lengkap',
            'target_amount' => 'target donasi',
            'minimum_donation' => 'minimum donasi',
            'id_category' => 'kategori',
            'banner_image' => 'gambar sampul',
            'gallery' => 'galeri',
            'gallery.*' => 'gambar galeri',
            'remove_gallery' => 'galeri yang dihapus',
            'video_url' => 'URL video',
            'end_date' => 'tanggal berakhir',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'target_amount.min' => 'Target donasi minimal Rp 100.000.',
            'minimum_donation.min' => 'Minimum donasi minimal Rp 1.000.',
            'description.min' => 'Deskripsi lengkap minimal 50 karakter.',
            'end_date.after' => 'Tanggal berakhir harus setelah hari ini.',
            'banner_image.max' => 'Ukuran gambar sampul maksimal 2MB.',
            'gallery.max' => 'Maksimal 5 gambar galeri.',
            'gallery.*.max' => 'Ukuran setiap gambar galeri maksimal 2MB.',
        ];
    }
}
