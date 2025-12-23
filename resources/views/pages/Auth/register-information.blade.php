@extends('layouts.main')
@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-100 py-8">
        <div class="w-full max-w-3xl p-10 bg-white rounded-3xl shadow-lg">
            <h1 class="text-3xl font-bold text-center mb-8">Ayo Kita Mulai</h1>
            <form method="POST" enctype="multipart/form-data" class="space-y-6" action="{{ route('mahasiswa.insert', ['id' => request()->route('id')]) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Depan -->

                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">Nama Depan</label>
                        <input type="text" name="nama_depan" id="firstName" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Nama Depan" required>
                        @error('nama_depan')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Nama Belakang -->
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Nama Belakang</label>
                        <input type="text" name="nama_belakang" id="lastName" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Nama Belakang" required>
                        @error('nama_belakang')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Jenis Kelamin -->

                    <!-- Nomor Induk Mahasiswa -->
                    <div>
                        <label for="studentId" class="block text-sm font-medium text-gray-700">Nomor Induk Mahasiswa</label>
                        <input type="text" name="nim" id="studentId" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="NIM" required>
                        @error('nim')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Nomor Handphone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Handphone</label>
                        <input type="tel" name="no_hp" id="phone" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Nomor Handphone" required>
                        @error('no_hp')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" required>
                        @error('tgl_lahir')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                        <input type="number" name="semester" id="semester" min="1" max="8" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Semester" required>
                        @error('semester')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="angkatan" class="block text-sm font-medium text-gray-700">Angkatan</label>
                        <input type="number" name="angkatan" id="angkatan" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Angkatan" required>
                        @error('angkatan')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Jurusan -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700">Prodi</label>
                        <select name="prodi_id" id="department" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" required>
                            <option value="">Pilih Prodi</option>
                            @foreach ( $prodi as $p  )
                            <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>

                            @endforeach

                            <!-- Add more options as needed -->
                        </select>
                        @error('prodi_id')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Pria">Pria</option>
                            <option value="Wanita">Wanita</option>
                            <!-- Add more options as needed -->
                        </select>
                        @error('jenis_kelamin')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-300 my-6"></div>
                <h2 class="text-xl font-semibold mb-4">Informasi Beasiswa & Dokumen</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Upload IPK -->
                    <div>
                        <label for="ipk_file" class="block text-sm font-medium text-gray-700">Upload Transkrip IPK (PDF/Gambar)</label>
                        <input type="file" name="ipk_file" id="ipk_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" required>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG (Max: 2MB)</p>
                        @error('ipk_file')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Upload UKT -->
                    <div>
                        <label for="ukt_file" class="block text-sm font-medium text-gray-700">Upload Bukti UKT (PDF/Gambar)</label>
                        <input type="file" name="ukt_file" id="ukt_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" required>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG (Max: 2MB)</p>
                        @error('ukt_file')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status Beasiswa -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Apakah Anda sedang menjalani beasiswa saat ini?</label>
                    <div class="flex items-center space-x-6">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status_beasiswa" id="status_beasiswa_tidak" value="0" class="form-radio h-4 w-4 text-orange-600" checked onchange="toggleBeasiswaName()">
                            <span class="ml-2">Tidak</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status_beasiswa" id="status_beasiswa_ya" value="1" class="form-radio h-4 w-4 text-orange-600" onchange="toggleBeasiswaName()">
                            <span class="ml-2">Ya, sedang menjalani beasiswa</span>
                        </label>
                    </div>
                    @error('status_beasiswa')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror

                    <!-- Nama Beasiswa (conditional) -->
                    <div id="nama_beasiswa_container" class="hidden">
                        <label for="nama_beasiswa_saat_ini" class="block text-sm font-medium text-gray-700 mt-4">Nama Beasiswa yang Sedang Dijalani</label>
                        <input type="text" name="nama_beasiswa_saat_ini" id="nama_beasiswa_saat_ini" class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-orange-500 focus:ring-orange-500" placeholder="Contoh: Beasiswa PPA, Beasiswa Unggulan, dll">
                        @error('nama_beasiswa_saat_ini')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
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

    <script>
        function toggleBeasiswaName() {
            const statusYa = document.getElementById('status_beasiswa_ya').checked;
            const container = document.getElementById('nama_beasiswa_container');
            const input = document.getElementById('nama_beasiswa_saat_ini');
            
            if (statusYa) {
                container.classList.remove('hidden');
                input.required = true;
            } else {
                container.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        }
    </script>
@endsection
