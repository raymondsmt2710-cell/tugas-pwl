@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-2 border-gray-200 bg-white text-gray-900 focus:border-brand-500 focus:ring-4 focus:ring-brand-100/50 rounded-xl shadow-sm transition-all px-4 py-3']) !!}>
