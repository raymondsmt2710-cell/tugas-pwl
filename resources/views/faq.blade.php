@extends('layouts.app')

@section('content')
<section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-8">
        <p class="text-green-600 font-semibold mb-3">FAQ</p>

        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Pertanyaan yang Sering Diajukan
        </h1>

        <p class="text-lg text-gray-600 leading-relaxed mb-10">
            Berikut beberapa pertanyaan umum mengenai Autopahala dan pengembangan project.
        </p>

        <div class="space-y-5">
            @forelse(\App\Models\Faq::where('is_active', true)->orderBy('order', 'asc')->get() as $faq)
                <div class="bg-gray-100 p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold mb-2">
                        {{ $faq->question }}
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $faq->answer }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500 py-6 text-center">Belum ada pertanyaan yang diajukan.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection