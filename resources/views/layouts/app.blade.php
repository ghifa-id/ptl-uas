<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Welcome {{ Auth::user()->role }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />

</head>

<body>
    <div id="app">
        <nav
            class="flex-no-wrap relative flex w-full items-center justify-between py-2 shadow-dark-mild bg-gray-800 lg:flex-wrap lg:justify-start lg:py-3">
            <div class="container mx-auto px-2 md:px-0">
                <div class="flex items-start">
                    <div class="flex w-full flex-wrap items-center justify-between">
                        <div class="flex">
                            <a class="mr-3 flex items-center text-neutral-200 hover:text-neutral-400 focus:text-neutral-400 lg:mb-0 lg:mt-0"
                                href="#">
                                <img src="{{ asset('img/logo.png') }}" class="w-11" alt="TE Logo" loading="lazy" />
                            </a>
                            <button
                                class="block border-0 bg-transparent px-2 text-black/50 hover:no-underline hover:shadow-none focus:no-underline focus:shadow-none focus:outline-none focus:ring-0 text-neutral-200 lg:hidden"
                                type="button" data-twe-collapse-init data-twe-target="#navbarSupportedContent1"
                                aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="[&>svg]:w-7 [&>svg]:stroke-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                        </div>
    
                        <div class="!visible hidden flex-grow basis-[100%] items-center lg:!flex lg:basis-auto pt-4 md:pt-0"
                            id="navbarSupportedContent1" data-twe-collapse-item>
    
                            <ul class="list-style-none me-auto flex flex-col ps-0 lg:flex-row" data-twe-navbar-nav-ref>
                                <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
                                    <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                        href="#" data-twe-nav-link-ref>Dashboard</a>
                                </li>
                                <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
                                    <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                        href="#" data-twe-nav-link-ref>Team</a>
                                </li>
                                <li class="mb-4 lg:mb-0 lg:pe-2" data-twe-nav-item-ref>
                                    <a class="transition duration-200 hover:ease-in-out motion-reduce:transition-none text-white hover:text-gray-400 focus:text-gray-400 active:text-gray-400 lg:px-2"
                                        href="#" data-twe-nav-link-ref>Projects</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="relative flex items-center mt-[2px] md:mt-0">
                        <div class="relative" data-twe-dropdown-ref data-twe-dropdown-alignment="end">
                            <a class="flex items-center whitespace-nowrap transition duration-150 ease-in-out motion-reduce:transition-none"
                                href="#" id="dropdownMenuButton2" role="button" data-twe-dropdown-toggle-ref
                                aria-expanded="false">
                                <img src="{{ asset('img/avatar.png') }}" class="rounded-full w-11" alt="" loading="lazy" />
                            </a>
                            <ul class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg data-[twe-dropdown-show]:block bg-surface-dark"
                                aria-labelledby="dropdownMenuButton2" data-twe-dropdown-menu-ref>
                                <li>
                                    <span class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline border-b border-b-slate-500"
                                        data-twe-dropdown-item-ref>{{ Auth::user()->name }}</span>
                                </li>
                                <li>
                                    <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline"
                                        href="#" data-twe-dropdown-item-ref>Profil</a>
                                </li>
                                <li>
                                    <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline bg-surface-dark"
                                        href="#" data-twe-dropdown-item-ref>Ganti password</a>
                                </li>
                                <li>
                                    <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 focus:outline-none active:no-underline bg-surface-dark"
                                        href="{{ route('logout') }}" data-twe-dropdown-item-ref
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">Log
                                        out</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <main class="container mx-auto">
            @yield('content')
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialization for ES Users
        import {
            Collapse,
            Dropdown,
            initTWE,
        } from "tw-elements";

        initTWE({
            Collapse,
            Dropdown
        });
    </script>
</body>

</html>
