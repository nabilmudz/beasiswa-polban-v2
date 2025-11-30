@extends('layouts.main')
@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="w-full max-w-3xl p-10 bg-white rounded-3xl shadow-lg">
            <h1 class="text-3xl font-bold text-center mb-8">Ayo Kita Mulai</h1>
            <form method="POST" class="space-y-6" action="{{ route('mahasiswa.insert', ['id' => request()->route('id')]) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Depan -->

                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">Nama Depan</label>
                        <input type="text" name="nama_depan" id="firstName" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Nama Depan">
                    </div>
                    <!-- Nama Belakang -->
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Nama Belakang</label>
                        <input type="text" name="nama_belakang" id="lastName" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Nama Belakang">
                    </div>
                    <!-- Jenis Kelamin -->

                    <!-- Nomor Induk Mahasiswa -->
                    <div>
                        <label for="studentId" class="block text-sm font-medium text-gray-700">Nomor Induk Mahasiswa</label>
                        <input type="text" name="nim" id="studentId" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="NIM">
                    </div>
                    <!-- Nomor Handphone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Handphone</label>
                        <input type="tel" name="no_hp" id="phone" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Nomor Handphone">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Tanggal_lahir</label>
                        <input type="date" name="tgl_lahir" id="phone" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Nomor Handphone">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Semester</label>
                        <input type="number" name="semester" id="phone" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Semester">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Angkatan</label>
                        <input type="year" name="angkatan" id="phone" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Angkatanr">
                    </div>
                    <!-- Jurusan -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700">Prodi</label>
                        <select name="prodi_id" id="department" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500">
                            <option value="">Pilih Prodi</option>
                            @foreach ( $prodi as $p  )
                            <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>

                            @endforeach

                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="department" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Pria">Pria</option>
                            <option value="Wanita">Wanita</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="w-full py-3 font-bold text-white bg-orange-500 rounded-lg shadow-md hover:bg-orange-700 focus:ring-2 focus:ring-orange-400 focus:outline-none">
                        MULAI MENJELAJAH
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
