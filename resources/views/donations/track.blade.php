<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:donation-tracker :order-id="$donation->order_id" />
        </div>
    </div>
</x-app-layout>
