<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-green-600 font-bold text-xl flex items-center">
                        <i class="fa-solid fa-house-medical mr-2"></i> CLINIC
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 hover:text-green-600">
                        <i class="fa-solid fa-gauge-high mr-1"></i> {{ __('Dashboard') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('booking.booked')" :active="request()->routeIs('booking.booked')" class="text-gray-700 hover:text-green-600">
                            <i class="fa-solid fa-clipboard-list mr-1"></i> {{ __('Bookings') }}
                        </x-nav-link>

                        <x-nav-link :href="route('patient.patients')" :active="request()->routeIs('patient.patients')" class="text-gray-700 hover:text-green-600">
                            <i class="fa-solid fa-hospital-user mr-1"></i> {{ __('Patients') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-5">

                @auth
                    <div class="relative flex items-center">
                        <x-dropdown align="right" width="64">
                            <x-slot name="trigger">
                                <button class="relative text-gray-500 hover:text-green-600 transition focus:outline-none mt-1">
                                    <i class="fa-solid fa-bell text-xl"></i>
                                    @if (auth()->user()->unreadNotifications->count() > 0)
                                        <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b flex justify-between items-center bg-gray-50">
                                    <span class="text-xs font-bold text-gray-600">Notifications</span>
                                    <a href="{{ route('notifications.markAllRead') }}"
                                        class="text-[10px] text-blue-600 hover:underline">Mark all read</a>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <x-dropdown-link :href="route('booking.booked')" class="border-b hover:bg-green-50">
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $notification->data['patient_name'] ?? 'New Patient' }}</p>
                                                    <p class="text-[10px] text-gray-500">New booking in
                                                        {{ $notification->data['clinic_name'] ?? 'Clinic' }}</p>
                                                </div>
                                            </div>
                                        </x-dropdown-link>
                                    @empty
                                        <div class="p-4 text-center text-xs text-gray-500 italic">No new notifications</div>
                                    @endforelse
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-600 hover:text-green-600 transition focus:outline-none">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                        <i class="fa-solid fa-user text-green-600"></i>
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="fa-solid fa-chevron-down ml-2 text-[10px]"></i>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                                        <i class="fa-solid fa-right-from-bracket mr-2"></i> {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth

            </div>
        </div>
    </div>
</nav>