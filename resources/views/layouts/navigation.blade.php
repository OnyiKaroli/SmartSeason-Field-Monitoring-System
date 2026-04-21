<nav class="bg-white border-b border-gray-200" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- Logo / Brand --}}
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()?->isAdmin() ? route('admin.dashboard') : route('agent.dashboard') }}"
                   class="flex items-center gap-2 text-emerald-700 hover:text-emerald-900 transition-colors">
                    {{-- Leaf icon --}}
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3C7.5 3 3 7.5 3 12c0 2.4 1 4.6 2.6 6.2C7.2 19.8 9.5 21 12 21c4.5 0 9-4.5 9-9 0-2.4-1-4.6-2.6-6.2A8.96 8.96 0 0012 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18"/>
                    </svg>
                    <span class="font-semibold text-base tracking-tight">SmartSeason</span>
                </a>

                {{-- Role badge --}}
                @auth
                    @if(auth()->user()->isAdmin())
                        <span class="hidden sm:inline-flex text-xs font-medium bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">
                            Admin
                        </span>
                    @else
                        <span class="hidden sm:inline-flex text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                            Field Agent
                        </span>
                    @endif
                @endauth
            </div>

            {{-- Desktop nav links --}}
            @auth
                <div class="hidden sm:flex items-center gap-6">

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-emerald-700' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            Dashboard
                        </a>
                        {{-- Phase 3+ links will be added here --}}
                    @else
                        <a href="{{ route('agent.dashboard') }}"
                           class="text-sm font-medium {{ request()->routeIs('agent.dashboard') ? 'text-emerald-700' : 'text-gray-600 hover:text-gray-900' }} transition-colors">
                            My Fields
                        </a>
                    @endif

                </div>

                {{-- User menu --}}
                <div class="hidden sm:flex items-center gap-3" x-data="{ userMenuOpen: false }">
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>

                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors text-gray-700 text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>

                        <div x-show="userMenuOpen"
                             @click.outside="userMenuOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-50"
                             style="display:none;">

                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                Profile
                            </a>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Mobile hamburger --}}
                <div class="sm:hidden">
                    <button @click="mobileOpen = !mobileOpen"
                            class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" style="display:none;"/>
                        </svg>
                    </button>
                </div>
            @endauth
        </div>
    </div>

    {{-- Mobile menu --}}
    @auth
        <div x-show="mobileOpen" class="sm:hidden border-t border-gray-200 bg-white" style="display:none;">
            <div class="px-4 py-3 space-y-1">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="block py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-emerald-700' : 'text-gray-600' }}">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('agent.dashboard') }}"
                       class="block py-2 text-sm font-medium {{ request()->routeIs('agent.dashboard') ? 'text-emerald-700' : 'text-gray-600' }}">
                        My Fields
                    </a>
                @endif

                <div class="border-t border-gray-100 pt-2 mt-2">
                    <a href="{{ route('profile.edit') }}" class="block py-2 text-sm text-gray-600">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="py-2 text-sm text-red-600">Sign out</button>
                    </form>
                </div>
            </div>
        </div>
    @endauth
</nav>
