<?php

namespace App\Livewire; // Sesuaikan menjadi App\Http\Livewire jika di dalam folder App/Http/Livewire

use Livewire\Component;

class FaqSection extends Component
{
    public $openIndex = null;
    public $faqs = [];

    public function mount()
    {
        $this->faqs = \App\Models\Faq::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(fn ($faq) => [
                'q' => $faq->question,
                'a' => $faq->answer,
            ])
            ->toArray();
    }

    public function toggle($index)
    {
        if ($this->openIndex === $index) {
            $this->openIndex = null;
        } else {
            $this->openIndex = $index;
        }
    }

    public function render()
    {
        return view('livewire.faq-section');
    }
}