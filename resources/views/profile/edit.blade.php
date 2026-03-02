@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- ================= PROFILE INFORMATION ================= -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">

                <div class="flex items-center gap-6 mb-8">
                    
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                            {{ Auth::user()->name }}
                        </h3>
                        <p class="text-gray-500 text-sm">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', Auth::user()->name) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email', Auth::user()->email) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>


            <!-- ================= UPDATE PASSWORD ================= -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">

                <h3 class="text-lg font-semibold mb-6 text-gray-800 dark:text-gray-100">
                    Ubah Password
                </h3>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password Lama
                        </label>
                        <input type="password" name="current_password"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password Baru
                        </label>
                        <input type="password" name="password"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-md transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>


            <!-- ================= DELETE ACCOUNT ================= -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 border border-red-200">

                <h3 class="text-lg font-semibold mb-6 text-red-600">
                    Hapus Akun
                </h3>

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Masukkan Password Untuk Konfirmasi
                        </label>
                        <input type="password" name="password"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow-md transition">
                        Hapus Akun
                    </button>
                </form>
            </div>

        </div>
    </div>


@endsection
