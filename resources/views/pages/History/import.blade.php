@extends('layouts.main')
@section('content')
    @include('component.navbar', ['path' => 'History Penerima', 'id' => null])
    
    <div class="p-3 mt-3 flex flex-row">
        <p class="text-2xl lg:text-3xl font-bold text-black">Import Data Penerima Beasiswa 2024-2025</p>
    </div>

    <form id="uploadForm" action="{{ route('history-penerima.import') }}" method="POST" enctype="multipart/form-data" class="border border-gray-500 rounded-lg p-5 flex flex-col items-center justify-center gap-4 mr-10 ml-3 mt-8">
        @csrf

        @if (session('error'))
            <div class="w-full px-4 py-3 mb-4 text-white bg-red-500 rounded-lg">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="w-full p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h6 class="font-bold text-blue-800 mb-2"><i class="fas fa-info-circle"></i> Informasi Format Excel:</h6>
            <ul class="text-sm text-blue-700 space-y-1">
                <li><strong>Kolom yang diperlukan:</strong>
                    <ul class="ml-4 mt-1">
                        <li>• NIM (wajib)</li>
                        <li>• Nama Mahasiswa</li>
                        <li>• Nama Prodi / Program Studi</li>
                        <li>• Nama Beasiswa / Beasiswa ADIk (wajib)</li>
                        <li>• Tahun (opsional)</li>
                    </ul>
                </li>
                <li><strong>Format file:</strong> .xlsx, .xls, atau .csv</li>
                <li><strong>Ukuran maksimal:</strong> 2 MB</li>
                <li>Data yang sudah ada (duplikat NIM + Nama Beasiswa) akan di-skip</li>
            </ul>
        </div>

        <div id="dropArea" class="w-full h-48 border-2 border-dashed border-gray-400 rounded-lg flex flex-col justify-center items-center p-6 bg-gray-50 hover:bg-gray-100">
            <i class="fas fa-upload text-gray-500 text-3xl mb-3"></i>
            <p class="text-base font-light text-gray-500 text-center">
                Seret dan letakkan atau klik untuk mengunggah berkas Excel
            </p>
            <input id="fileInput" name="excelFile" type="file" class="hidden" accept=".xlsx,.xls,.csv" required />
        </div>

        <!-- Attached Files -->
        <div id="fileList" class="w-full mt-4 text-sm text-gray-600">
            <p class="text-center" id="noFilesMessage">Tidak ada file yang terlampir.</p>
        </div>

        <!-- Buttons -->
        <div class="flex flex-row-reverse gap-4 text-center w-full mt-6">
            <a href="{{ route('history-penerima.index') }}">
                <button type="button"
                    class="border border-orange-500 rounded-lg w-28 h-12 hover:bg-orange-500 hover:text-white text-orange-500">
                    Kembali
                </button>
            </a>
            <button type="submit" class="bg-orange-500 rounded-lg w-28 h-12 text-white hover:bg-orange-600">
                Import File
            </button>
        </div>
    </form>
    
    <script src="{{ asset('assets/js/import-data-pengumuman-beasiswa.js') }}"></script>
@endsection
