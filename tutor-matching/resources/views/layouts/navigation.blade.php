<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::check() ? route('dashboard') : route('/') }}" class="flex items-center" style="text-decoration:none;">
                        <img src="{{ asset('images/logo.png') }}" alt="CowTeacherロゴ" style="height:80px;width:auto;" class="me-2" />
                        <span class="fw-bold fs-4 d-none d-sm-inline" style="color:#222; letter-spacing:0.02em;">{{ config('app.name', 'サービス名') }}</span>
                    </a>
                    <style>
                    @media (max-width: 600px) {
                        .navbar .fs-4,
                        .fs-4.d-none.d-sm-inline {
                            display: none !important;
                        }
                    }
                    </style>
                </div>

                <!-- Central Page Title -->
                <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center" style="min-width:120px;">
                    <span class="text-lg font-semibold text-gray-700" style="letter-spacing:0.03em;">@yield('page_title')</span>
                </div>


            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(Auth::check())
<x-dropdown align="right" width="48">
    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            @if(Auth::check())
    @php
      $lastName = Auth::user()->last_name ?? '';
      $userType = Auth::user()->user_type ?? '';
    @endphp
    <div>
      @php
        $firstName = Auth::user()->first_name ?? '';
        $lastName = Auth::user()->last_name ?? '';
        $userType = Auth::user()->user_type ?? '';
      @endphp
      @if(!empty($firstName))
        {{ $firstName }}
      @elseif(!empty($lastName))
        {{ $lastName }}
      @elseif(!empty($userType))
        {{ $userType }}
      @endif
    </div>
@endif

                            <div class="ms-1">
                                
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link> --}}

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
@endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">


        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                @php
  $lastName = Auth::user()->last_name ?? '';
  $userType = Auth::user()->user_type ?? '';
@endphp
<div class="font-medium text-base text-gray-800">
  @if(!empty($lastName))
    {{ $lastName }}
  @elseif(!empty($userType))
    {{ $userType }}
  @else
    ゲスト
  @endif
</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()?->email ?? '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- <x-responsive-nav-link :href="route('profile.edit')">
    {{ __('Profile') }}
</x-responsive-nav-link> --}}

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
