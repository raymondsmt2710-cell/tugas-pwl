<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifikasi</h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Tandai semua dibaca</button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($notifications->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                    </svg>
                    <h3 class="mt-3 text-base font-medium text-gray-900">Belum ada notifikasi</h3>
                    <p class="mt-1 text-sm text-gray-500">Notifikasi akan muncul di sini saat ada aktivitas.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($notifications as $notification)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left block rounded-xl border p-4 transition-colors
                                {{ $notification->read_at ? 'bg-white border-gray-100' : 'bg-indigo-50 border-indigo-100' }}
                                hover:bg-gray-50">
                                <div class="flex items-start gap-3">
                                    {{-- Icon --}}
                                    <div class="shrink-0 mt-0.5">
                                        @switch($notification->data['type'] ?? '')
                                            @case('donation_received')
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                </div>
                                                @break
                                            @case('new_follower')
                                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                                                </div>
                                                @break
                                            @case('new_comment') @case('comment_reply')
                                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                                                </div>
                                                @break
                                            @case('withdrawal_status')
                                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
                                                </div>
                                                @break
                                            @default
                                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                                                </div>
                                        @endswitch
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                        <p class="text-sm text-gray-600 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>

                                    {{-- Unread dot --}}
                                    @if(!$notification->read_at)
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 shrink-0 mt-2"></div>
                                    @endif
                                </div>
                            </button>
                        </form>
                    @endforeach
                </div>

                <div class="mt-6">{{ $notifications->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
