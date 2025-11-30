@extends('layouts.main2')

@section('content')
    @include('component.navbar',['path'=>"Tambah Beasiswa",'id'=>null])

    <div id="popup" class="fixed inset-0 bg-opacity-50 backdrop-blur-md hidden flex items-center justify-center z-50">
        <div class="bg-white w-full sm:w-3/4 p-6 sm:p-8 rounded-3xl shadow-xl max-w-lg mx-auto relative">
            {{-- Tombol Close --}}
            <div class="absolute top-4 right-4">
                <button onclick="hidePopup()" aria-label="Close" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Header --}}
            <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">Pilih Tipe Beasiswa</h2>

            {{-- Daftar Template Beasiswa --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="mb-4">
                    <label for="internal" class="flex items-center space-x-3">
                        <input type="radio" id="internal" name="tipe_beasiswa" value="internal"
                            class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                            {{ old('tipe_beasiswa', $beasiswa->tipe_beasiswa) == 'internal' ? 'checked' : '' }}>
                        <span class="text-gray-600">Internal</span>
                    </label>
                </div>
                <div class="mb-4">
                    <label for="kipk" class="flex items-center space-x-3">
                        <input type="radio" id="kipk" name="tipe_beasiswa" value="kipk"
                            class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                            {{ old('tipe_beasiswa', $beasiswa->tipe_beasiswa) == 'kipk' ? 'checked' : '' }}>
                        <span class="text-gray-600">KIPK</span>
                    </label>
                </div>
                <div class="mb-4">
                    <label for="eksternal" class="flex items-center space-x-3">
                        <input type="radio" id="eksternal" name="tipe_beasiswa" value="eksternal"
                            class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                            {{ old('tipe_beasiswa', $beasiswa->tipe_beasiswa) == 'eksternal' ? 'checked' : '' }}>
                        <span class="text-gray-600">Eksternal</span>
                    </label>
                </div>
            </div>

            <div id="pagination-controls" class="py-3 hidden"></div>
        </div>
    </div>