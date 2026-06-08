<button type="button" wire:click="toggleLike" class="flex items-center gap-2 group focus:outline-none transition duration-200">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-6 h-6 transform transition-all active:scale-125 duration-200 stroke-2 {{ $liked ? 'text-red-500 fill-red-500' : 'text-gray-400 fill-transparent hover:text-red-400' }}"
         viewBox="0 0 24 24"
         stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
    </svg>
    <span class="text-sm font-semibold {{ $liked ? 'text-red-500' : 'text-gray-500' }}">
        {{ $likesCount }} {{ $likesCount === 1 ? 'Like' : 'Likes' }}
    </span>
</button>
