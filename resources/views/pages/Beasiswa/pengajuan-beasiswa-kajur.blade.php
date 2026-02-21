@extends('layouts.main')
@section('content')
@include('component.navbar', [
'path' => 'Pengajuan Beasiswa untuk Mahasiswa',
'id' => null
])

<div class="max-w-10xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="container px-4 py-6 sm:px-0">
        <h2 class="text-3xl font-bold mb-6">Pengajuan Beasiswa untuk Mahasiswa</h2>
        
        <!-- Info Ketua Jurusan -->
        <div class="bg-white p-6 rounded-lg border border-gray-300 mb-6">
            <div class="flex items-center">
                @if ($user->foto)
                <div class="w-20 h-20 rounded-full bg-gray-300 mr-6 overflow-hidden">
                    <img src="{{ $user->foto }}" alt="" class="w-full h-full object-cover">
                </div>
                @else
                <div class="w-20 h-20 rounded-full bg-gray-300 mr-6"></div>
                @endif
                <div>
                    <h3 class="text-xl font-bold">{{ $user->nama_depan }} {{ $user->nama_belakang }}</h3>
                    <p class="text-lg text-gray-700">Ketua Jurusan</p>
                    <p class="text-sm text-gray-500">{{ $jurusan->nama_jurusan }}</p>
                </div>
            </div>
        </div>

        <!-- Notification -->
        <div class="bg-blue-100 p-4 border border-blue-300 rounded-lg text-sm text-blue-800 mb-6">
            Sebagai Ketua Jurusan, Anda dapat mengajukan beasiswa untuk mahasiswa di jurusan Anda.
            Silakan pilih mahasiswa yang akan diajukan dan upload dokumen yang diperlukan.
        </div>

        <!-- Form Pengajuan -->
        <form action="{{ route('pengajuan.store-kajur', ['id' => $beasiswa_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Pilih Mahasiswa -->
            <div class="bg-white p-6 rounded-lg border border-gray-300 mb-6">
                <h3 class="text-xl font-bold mb-4">Pilih Mahasiswa</h3>
                <div class="mb-4">
                    <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">NIM Mahasiswa <span class="text-red-500">*</span></label>
                    <select name="nim" id="nim" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="updateMahasiswaInfo()">
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswaList as $mhs)
                            <option value="{{ $mhs->nim }}" 
                                data-nama="{{ $mhs->user->nama_depan }} {{ $mhs->user->nama_belakang }}"
                                data-email="{{ $mhs->user->email }}"
                                data-prodi="{{ $mhs->prodi->nama_prodi }}"
                                data-hp="{{ $mhs->no_hp }}">
                                {{ $mhs->nim }} - {{ $mhs->user->nama_depan }} {{ $mhs->user->nama_belakang }}
                            </option>
                        @endforeach
                    </select>
                    @error('nim')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Mahasiswa yang dipilih -->
                <div id="mahasiswa-info" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-md font-semibold mb-2">Informasi Mahasiswa</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <p id="info-nama" class="mt-1 text-base text-gray-800">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Email</label>
                            <p id="info-email" class="mt-1 text-base text-gray-800">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Program Studi</label>
                            <p id="info-prodi" class="mt-1 text-base text-gray-800">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">No. HP</label>
                            <p id="info-hp" class="mt-1 text-base text-gray-800">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Dokumen -->
            <h2 class="text-3xl font-bold mb-6">Lampiran Dokumen</h2>
            
            <div class="container">
                @php $index = 1; @endphp
                @foreach ($dokumen as $item)
                <div id="group-{{ $index }}" class="group">
                    <div class="flex items-center border p-4 bg-white rounded-lg drop-shadow-lg relative cursor-pointer mt-8"
                        onclick="toggleUpload({{ $index }})">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAAAXNSR0IArs4c6QAAAodJREFUWEftlkuojVEUx383Bt4GJl55pZDERKSUDIiBgfI2MpPE0GPgMfAaYaCQEgojYYBSpJQBE+UtRSTd65VIUey/1lfL7uzzfft+hy6dVaf2/vZae//OWmuvtTvoYdLRw3hoA5VFpO2h/9pDfYDpwHhgJNCr7N+69e/APeA+8KiZXZUcmgbsAhZkADRTfQosB+40UioDOgSsbRFIvM1G4ED8MQU0GLgCzIgMnjjXf8sEHRDCtQIY7uwWhjMu+X1SQDeA2U7xIrAeeJ4JEasPCkBHgGW28AaYBLwvFBsBbbacKXT2AptqgsTmt5z3NwAHU0D9gBfAEFM4Y24u9EfYXO7PkeuAfoXMBy7b5DSwMgW0Bjhmi2+BscAnm68GDgOCzpUdwWC7M1LoPtpcDhidAhK16CVbgN02ngXczKVw+jHQUOC1rb+yuvZrGueQknaUKU4Irnxs42vB5XNs/A444f5hFc44ZIuA86aoC6N5Q6Afbve+IZm/2tx/1614WIWiic5dYIqt7wS2pYAUV8VX0h/4YuMPgGqTpA6Q9jgaatkSt6/AXqaAHgATbXGy9R5NVSTn2fcu4DjwOcNLvUOYx1nYhzm7xcA5v0+cQ6eAVaaw1dUjFUkVy1ZKpdahpqe6IOm0a194QmuqsgNrUj2zSn270T6xh/TEUDdWAZT8VrSsD80NIRsDKAxVRX1Pzw+lRPbzQwXwpDtpD6B28lck1VzPhoRe6gguhBCu87fhT9GlgHTlr4Z+MzM6WIWyePnpFVhV4sKYtCt7oO0PTwN147oSt45uA8lwamio+1wd6g5cS4EKAHVkXX21lFxpWchyD66tX5ZDtQ/I3aANVOaxtof+OQ/9BMebaiVc4cz0AAAAAElFTkSuQmCC"
                            class="h-6 w-6 mr-4" alt="Upload Icon" />
                        <p class="text-gray-700">{{ $item->dokumen }} <span class="text-red-500">*</span></p>
                    </div>

                    <!-- Upload Section -->
                    <div class="hidden" id="upload-section-{{ $index }}">
                        <div class="border-t-0 border border-gray-400 p-4 mx-6 mt-0 text-center">
                            <div class="border border-dashed border-2 border-gray-400 p-3 mx-6 mt-0 rounded-lg text-center">
                                <label for="file-upload-{{ $index }}" class="cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v4a2 2 0 002 2h12a2 2 0 002-2v-4m-4-4l-4-4m0 0l-4 4m4-4v12" />
                                    </svg>
                                    <span class="text-gray-600 text-sm">Pilih File</span>
                                    <input id="file-upload-{{ $index }}" name="file_{{ $index }}" type="file"
                                        accept=".pdf,.jpg,.png" class="hidden"
                                        onchange="showFileName({{ $index }})" required />
                                </label>
                            </div>
                            <p id="file-name-{{ $index }}" class="text-gray-500 text-xs mt-2">Tidak ada file yang dipilih
                            </p>
                            <p class="text-gray-500 text-xs mt-2">Max Size: 2MB | Format: PDF, JPG, PNG</p>
                            
                            @if($item->link_dokumen)
                            <div class="mt-2">
                                <a href="{{ $item->link_dokumen }}" target="_blank" 
                                   class="text-blue-500 hover:text-blue-700 text-sm underline">
                                    Lihat Contoh Dokumen
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @php $index++; @endphp
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-8">
                <a href="{{ route('beasiswa.list-beasiswa-staff') }}" 
                   class="mr-4 px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Ajukan Beasiswa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleUpload(index) {
        const uploadSection = document.getElementById('upload-section-' + index);
        uploadSection.classList.toggle('hidden');
    }

    function showFileName(index) {
        const input = document.getElementById('file-upload-' + index);
        const fileNameDisplay = document.getElementById('file-name-' + index);
        
        if (input.files.length > 0) {
            fileNameDisplay.textContent = input.files[0].name;
            fileNameDisplay.classList.remove('text-gray-500');
            fileNameDisplay.classList.add('text-green-600', 'font-semibold');
        } else {
            fileNameDisplay.textContent = 'Tidak ada file yang dipilih';
            fileNameDisplay.classList.remove('text-green-600', 'font-semibold');
            fileNameDisplay.classList.add('text-gray-500');
        }
    }

    function updateMahasiswaInfo() {
        const select = document.getElementById('nim');
        const selectedOption = select.options[select.selectedIndex];
        const infoDiv = document.getElementById('mahasiswa-info');
        
        if (selectedOption.value) {
            document.getElementById('info-nama').textContent = selectedOption.dataset.nama;
            document.getElementById('info-email').textContent = selectedOption.dataset.email;
            document.getElementById('info-prodi').textContent = selectedOption.dataset.prodi;
            document.getElementById('info-hp').textContent = selectedOption.dataset.hp;
            infoDiv.classList.remove('hidden');
        } else {
            infoDiv.classList.add('hidden');
        }
    }
</script>

@endsection
