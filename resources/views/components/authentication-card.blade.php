<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-brand-50/50 via-white to-brand-100/20 px-4">
    <div class="transition-transform duration-300 hover:scale-105">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-xl border border-gray-100/50 overflow-hidden rounded-3xl">
        {{ $slot }}
    </div>
</div>
