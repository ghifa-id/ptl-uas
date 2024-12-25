@extends('layouts.app')
@section('title', 'Profil Pengguna')

@section('content')
    <div class="container md:max-w-[1000px] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 md:gap-10">
            <div class="col-span-2 flex flex-col gap-4 px-4 md:px-0">
                <div class="flex justify-between">
                    <h1 class="text-2xl font-semibold mb-4">Profil Pengguna</h1>
                </div>
                <form action="{{ route('account.profile.update') }}" method="POST" id="dataForm" autocomplete="off">
                    @csrf
                    <div class="flex flex-col w-full mb-2">
                        <label for="name" class="block text-gray-700">Nama</label>
                        <input type="text" id="name" name="name" value="{{ $user->name }}"
                            class="border shadow-sm rounded-md py-2 px-3">
                    </div>

                    <div class="flex flex-col w-full mb-2">
                        <label for="username" class="block text-gray-700">Nama Pengguna</label>
                        <span class="text-xs text-red-500">(Nama Pengguna tidak bisa diganti)</span>
                        <input type="text" disabled id="username" name="username" value="{{ $user->username }}"
                            class="border shadow-sm rounded-md py-2 px-3">
                    </div>

                    <div class="flex flex-col w-full mb-2">
                        <label for="email" class="block text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ $user->email }}"
                            class="border shadow-sm rounded-md py-2 px-3">
                    </div>

                    <div class="flex flex-col w-full mb-4">
                        <label for="phone_number" class="block text-gray-700">Nomor Telepon</label>
                        <input type="text" id="phone_number" maxlength="15" name="phone_number"
                            value="{{ $user->phone_number }}" class="border shadow-sm rounded-md py-2 px-3">
                    </div>

                    <!-- Modal Buttons -->
                    <div class="flex justify-end md:justify-start">
                        <button type="button" class="bg-gray-500 text-white rounded-lg px-4 py-2 mr-2"
                            onclick="window.history.back()">Batal</button>
                        <button type="submit" id="save"
                            class="bg-blue-500 text-white rounded-lg px-4 py-2">Simpan</button>
                    </div>
                </form>
            </div>
            <div class="flex flex-col gap-4 mt-10 md:mt-0 px-4 md:px-0">
                <div class="flex justify-between">
                    <h1 class="text-2xl font-semibold mb-4">Ganti Kata Sandi</h1>
                </div>
                <form action="{{ route('account.password.change') }}" method="POST" id="dataForm" autocomplete="off">
                    @csrf
                    <div class="flex flex-col w-full mb-2">
                        <label for="old_password" class="block text-gray-700">Kata Sandi Lama</label>
                        <div class="relative">
                            <input type="password" id="old_password" name="old_password" placeholder="********"
                                class="border shadow-sm rounded-md py-2 px-3 w-full">
                            <div id="seenOldPassword" class="absolute right-3 top-0 bottom-1 h-fit my-auto cursor-pointer">
                                <i id="oldPasswordEye" class="fa fa-eye leading-3" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col w-full mb-2">
                        <label for="password" class="block text-gray-700">Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="********"
                                class="border shadow-sm rounded-md py-2 px-3 w-full">
                            <div id="seenPassword" class="absolute right-3 top-0 bottom-1 h-fit my-auto cursor-pointer">
                                <i id="passwordEye" class="fa fa-eye leading-3" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col w-full mb-4">
                        <label for="password_confirmation" class="block text-gray-700">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="********" class="border shadow-sm rounded-md py-2 px-3 w-full">
                            <div id="seenConfirmPassword"
                                class="absolute right-3 top-0 bottom-1 h-fit my-auto cursor-pointer">
                                <i id="confirmPasswordEye" class="fa fa-eye leading-3" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Buttons -->
                    <div class="flex justify-end">
                        <button type="submit" id="save"
                            class="bg-blue-500 text-white rounded-lg px-4 py-2">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#seenOldPassword').click(function() {
            const passwordField = $("#old_password");
            const fieldType = passwordField.attr("type") === "password" ? "text" : "password";
            passwordField.attr("type", fieldType);
            $('#oldPasswordEye').toggleClass('fa-eye-slash');
        });
        $('#seenPassword').click(function() {
            const passwordField = $("#password");
            const fieldType = passwordField.attr("type") === "password" ? "text" : "password";
            passwordField.attr("type", fieldType);
            $('#passwordEye').toggleClass('fa-eye-slash');
        });
        $('#seenConfirmPassword').click(function() {
            const passwordField = $("#password_confirmation");
            const fieldType = passwordField.attr("type") === "password" ? "text" : "password";
            passwordField.attr("type", fieldType);
            $('#confirmPasswordEye').toggleClass('fa-eye-slash');
        });
    </script>
@endsection
