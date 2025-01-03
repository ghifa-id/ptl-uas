@php
    $roles = config('roles');
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | e-DinasDrive</title>
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logo.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div id="app">
        <nav
            class="flex-no-wrap fixed z-[1000] flex w-full items-center justify-between py-2 shadow-dark-mild bg-gray-800 lg:flex-wrap lg:justify-start lg:py-3 min-h-[70px]">
            <div class="container mx-auto px-2 md:px-0">
                <div class="flex items-start my-2 md:my-0">
                    <div class="flex w-full flex-wrap items-center justify-between">
                        <div class="flex">
                            @if(Auth::user()->role !== "staff")
                            <a class="mr-3 flex items-center text-neutral-200 hover:text-neutral-400 focus:text-neutral-400 lg:mb-0 lg:mt-0"
                                href="{{ route($roles[Auth::user()->role] . '.dashboard.index') }}">
                                <img src="{{ asset('assets/img/logo.png') }}" class="w-11" alt="TE Logo"
                                    loading="lazy" />
                            </a>
                            @else
                            <a class="mr-3 flex items-center text-neutral-200 hover:text-neutral-400 focus:text-neutral-400 lg:mb-0 lg:mt-0"
                                href="{{ route('applicant.booking.index') }}">
                                <img src="{{ asset('assets/img/logo.png') }}" class="w-11" alt="TE Logo"
                                    loading="lazy" />
                            </a>
                            @endif
                            <button id="hamburger"
                                class="block border-0 bg-transparent px-2 text-black/50 hover:no-underline hover:shadow-none focus:no-underline focus:shadow-none focus:outline-none focus:ring-0 text-neutral-200 lg:hidden"
                                type="button" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="[&>svg]:w-7 [&>svg]:stroke-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                        </div>

                        <div class="!visible hidden min-h-[calc(100vh-60px)] md:min-h-0 flex-grow basis-[100%] items-center lg:!flex lg:basis-auto pt-4 md:pt-0"
                            id="navbarMenu">
                            <ul
                                class="list-style-none me-auto flex flex-col items-center ps-0 lg:flex-row absolute md:relative right-0 left-0 m-auto bottom-0 top-0 h-fit">
                                @if (Auth::user()->role === 'bendahara')
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('administrator.dashboard.index') }}">Pembayaran BBM</a>
                                    </li>
                                @endif

                                @if (Auth::user()->role === 'kasubag')
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('manager.dashboard.index') }}">Beranda</a>
                                    </li>
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('manager.booking.index') }}">Riwayat Pengajuan</a>
                                    </li>
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="#">Pengembalian Kendaraan</a>
                                    </li>
                                @endif

                                {{-- menu administrator / bendahara --}}
                                @if (Auth::user()->role === 'bendahara')
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('administrator.user.index') }}">Data Pengguna</a>
                                    </li>
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('administrator.department.index') }}">Data Department</a>
                                    </li>
                                    <li class="mb-4 lg:mb-0 lg:pe-2 relative" data-twe-dropdown-ref
                                        data-twe-dropdown-alignment="start">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            id="dropdownMenu2" role="button" data-twe-dropdown-toggle-ref
                                            aria-expanded="false" href="#">Kendaraan <i class="fa fa-angle-down"
                                                aria-hidden="true"></i></a>
                                        <ul class="absolute z-[1000] float-left !top-4 m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg data-[twe-dropdown-show]:block bg-surface-dark"
                                            aria-labelledby="dropdownMenu2" data-twe-dropdown-menu-ref>
                                            <li>
                                                <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline"
                                                    href="{{ route('administrator.vehicle.index') }}" data-twe-dropdown-item-ref>Data Kendaraan</a>
                                            </li>
                                            <li>
                                                <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline bg-surface-dark"
                                                    href="{{ route('administrator.vehicle.type') }}" data-twe-dropdown-item-ref>Type Kendaraan</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- menu superuser --}}
                                @if (Auth::user()->role === 'superuser')
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('superuser.user.index') }}">Data Pengguna</a>
                                    </li>
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('superuser.department.index') }}">Data Department</a>
                                    </li>
                                @endif

                                {{-- menu staff --}}
                                @if (Auth::user()->role === 'staff')
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('applicant.booking.index') }}">Booking Kendaraan</a>
                                    </li>
                                    <li class="mb-4 lg:mb-0 lg:pe-2">
                                        <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                            href="{{ route('applicant.return.index') }}">Pengembalian & Claim BBM</a>
                                    </li>
                                @endif

                                {{-- <li class="mb-4 lg:mb-0 lg:pe-2 relative" data-twe-dropdown-ref
                                    data-twe-dropdown-alignment="start">
                                    <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                        id="dropdownMenu2" role="button" data-twe-dropdown-toggle-ref
                                        aria-expanded="false" href="#">Dropdown <i class="fa fa-angle-down"
                                            aria-hidden="true"></i></a>
                                    <ul class="absolute z-[1000] float-left !top-4 m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg data-[twe-dropdown-show]:block bg-surface-dark"
                                        aria-labelledby="dropdownMenu2" data-twe-dropdown-menu-ref>
                                        <li>
                                            <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline"
                                                href="#" data-twe-dropdown-item-ref>Menu 1</a>
                                        </li>
                                        <li>
                                            <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline bg-surface-dark"
                                                href="#" data-twe-dropdown-item-ref>Menu 2</a>
                                        </li>
                                    </ul>
                                </li> --}}

                            </ul>
                        </div>
                    </div>
                    <div class="relative flex items-center mt-[2px] md:mt-0">
                        <div class="relative" data-twe-dropdown-ref data-twe-dropdown-alignment="end">
                            <a class="flex items-center whitespace-nowrap transition duration-150 ease-in-out motion-reduce:transition-none"
                                href="#" id="dropdownMenuButton1" role="button" data-twe-dropdown-toggle-ref
                                aria-expanded="false">
                                <img src="{{ asset('assets/img/avatar.png') }}" class="rounded-full w-11"
                                    alt="" loading="lazy" />
                            </a>
                            <ul class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg data-[twe-dropdown-show]:block bg-surface-dark"
                                aria-labelledby="dropdownMenuButton1" data-twe-dropdown-menu-ref>
                                <li>
                                    <span
                                        class="block w-full whitespace-nowrap cursor-default bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline border-b border-b-slate-500"
                                        data-twe-dropdown-item-ref>{{ Auth::user()->name }}</span>
                                </li>
                                <li>
                                    <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline bg-surface-dark"
                                        href="{{ route('account.profile') }}" data-twe-dropdown-item-ref>Profil & Kata sandi</a>
                                </li>
                                <li>
                                    <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline bg-surface-dark"
                                        href="{{ route('logout') }}" data-twe-dropdown-item-ref
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">Log
                                        out</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <main class="container mx-auto pt-20 pb-6 px-2 md:px-0">
            @error('error')
                <div id="error-alert"
                    class="absolute z-0 right-4 bg-red-200 border border-red-500 truncate text-sm text-left py-4 px-4 text-nowrap md:mx-0 mt-1 md:mt-0 mb-1 rounded-lg flex items-center justify-between gap-4"
                    role="alert">
                    <span>{{ $message }}</span>
                    <i id="alert-close" class="fa fa-times cursor-pointer" aria-hidden="true"></i>
                </div>
            @enderror
            @error('warning')
                <div id="warning-alert"
                    class="absolute z-0 right-4 bg-yellow-200 border border-yellow-500 truncate text-sm text-left py-4 px-4 text-nowrap md:mx-0 mt-1 md:mt-0 mb-1 rounded-lg flex items-center justify-between gap-4"
                    role="alert">
                    <span>{{ $message }}</span>
                    <i id="alert-close" class="fa fa-times cursor-pointer" aria-hidden="true"></i>
                </div>
            @enderror
            @if (session('success'))
                <div id="success-alert"
                    class="absolute z-0 right-4 bg-green-200 border border-green-500 truncate text-sm text-left py-4 px-4 text-nowrap md:mx-0 mt-1 md:mt-0 mb-1 rounded-lg flex items-center justify-between gap-4"
                    role="alert">
                    <span>{{ session('success') }}</span>
                    <i id="alert-close" class="fa fa-times cursor-pointer" aria-hidden="true"></i>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/style.js') }}"></script>
</body>

</html>
