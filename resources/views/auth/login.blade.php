@extends('layouts.login')

@section('content')
    <img src="{{ asset('assets/img/logo.png') }}" class="w-28 invert" alt="TE Logo" loading="lazy" />
    <div class="w-full max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
        <h1 class="text-4xl font-bold mb-4 text-center">e-DinasDrive</h1>
        <form method="POST" action="{{ route('authenticate') }}">
            @csrf
            <div class="flex flex-col mb-3">
                {{-- <label for="login" class="text-md @error('login') text-red-500 @enderror">Username / Email</label> --}}
                <div class="flex flex-col">
                    <input id="login" type="text"
                        class="p-4 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full text-xl @error('login') border-red-500 shadow-red-500 @enderror"
                        name="login" value="{{ old('login') }}" placeholder="E-mail / Nama Pengguna" required>
                </div>
            </div>
            <div class="flex flex-col mb-3">
                {{-- <label for="password" class="text-md @error('login') text-red-500 @enderror">Password</label> --}}

                <div class="flex flex-col">
                    <input id="password" type="password"
                        class="p-4 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full text-xl @error('login') border-red-500 shadow-red-500 @enderror"
                        name="password" required placeholder="Kata Sandi">
                </div>
                @error('login')
                    <span class="text-red-500 text-sm text-center mt-3" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="flex flex-col gap-3 justify-center items-center">
                <button type="submit"
                    class="inline-flex w-full justify-center items-center py-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xl text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-300">
                    <span class="mr-4 font-bold">Masuk</span>
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </button>
                <div class="flex gap-1">
                    <span class="text-sm">Belum punya akun? hubungi bendahara</span>
                    <a href="#" class="text-sm text-blue-500">disini!</a>
                </div>
            </div>
        </form>
    </div>
@endsection
