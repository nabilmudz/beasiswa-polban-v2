<!-- resources/views/auth/register.blade.php -->

@extends('layouts.main')
@section('content')
<main class="flex items-center justify-center h-screen bg-gray-100">
    <section class="relative flex flex-col lg:flex-row w-full max-w-4xl h-auto lg:h-[30rem] rounded-3xl overflow-hidden shadow-lg">
        <!-- First Column (App Name + Background Image + Overlay) -->
        <div class="relative lg:w-1/2 w-full h-64 lg:h-full bg-cover bg-center flex items-start justify-center" style="background-image: url('{{ asset('assets/img/login/login-bg.png') }}');">
            <div class="absolute inset-0 bg-black opacity-30"></div>
            <div class="relative z-10 p-8">
                <h1 class="text-3xl font-bold text-white">
                    Sistem Informasi Kemahasiswaan Polban
                </h1>
            </div>
        </div>

        <!-- Second Column (Sign-Up Form) -->
        <div class="w-full lg:w-1/2 flex flex-col p-10 bg-white">
            <div class="title mb-6">
                <h1 class="text-2xl font-bold">Sign Up</h1>
            </div>
            <form method="POST" action="{{ route('auth.register') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="email" class="block pb-3 text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="text-sm block w-full rounded-lg border-gray-300 px-3 py-2 text-gray-700 focus:outline-none" placeholder="example@polban.ac.id" required>
                        @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block pb-3 text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="text-sm block w-full rounded-lg border-gray-300 px-3 py-2 text-gray-700 focus:outline-none" placeholder="********" required>
                        @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block pb-3 text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="text-sm block w-full rounded-lg border-gray-300 px-3 py-2 text-gray-700 focus:outline-none" placeholder="********" required>
                        @error('password_confirmation')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-block w-full px-6 py-3 font-bold text-white bg-orange-500 rounded-lg shadow-md hover:bg-orange-700">
                        Sign Up
                    </button>
                </div>
                <div class="mt-4 text-center">
                    <span class="text-sm">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-blue-500"><strong>Login disini</strong></a></span>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
