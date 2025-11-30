@extends('layouts.main2')

@section('content')
    @include('component.navbar',['path'=>"Tambah Beasiswa",'id'=>null])
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
        <strong class="font-bold">Terjadi Kesalahan!</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
        <strong class="font-bold">Sukses!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
@if ($beasiswa != null)
    <div class="max-w-10xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white rounded-lg p-6">
                <form id="beasiswa-form" action="{{ route('beasiswa.update',['id'=>$beasiswa->id]) }}" method="POST" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Nama Beasiswa -->
                        <div class="mb-4">
                            <label for="nama_beasiswa" class="block text-sm font-medium text-gray-700">Nama POLBAN Beasiswa</label>
                            <input type="text" id="nama_beasiswa" name="nama_beasiswa" value="{{old('nama_beasiswa',$beasiswa->nama_beasiswa)}}" placeholder="Nama Beasiswa"
                                class="block w-full border @error('nama_beasiswa') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            @error('nama_beasiswa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sumber Beasiswa -->
                        <div>
                            <label for="sumber_beasiswa" class="block text-sm font-medium text-gray-700">Sumber Beasiswa</label>
                            <input type="text" id="sumber_beasiswa" name="sumber_beasiswa" value="{{old('sumber_beasiswa', $beasiswa->sumber)}}" placeholder="Sumber Beasiswa"
                                class="block w-full border @error('sumber_beasiswa') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            @error('sumber_beasiswa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Beasiswa</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full px-3 py-2 border @error('deskripsi') border-red-500 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo old('deskripsi', $beasiswa->deskripsi)?></textarea>
                        @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publish Penerima Beasiswa -->
                    <div>
                        <p class="block text-sm font-medium text-gray-700">Terbitkan Penerima Beasiswa?</p>
                        @error('publish_beasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="mb-4">
                                <label for="publish" class="flex items-center space-x-3">
                                    <input type="radio" id="publish" name="publish_beasiswa" value="1"
                                        class="form-radio h-5 w-5 text-blue-500 rounded-publish @error('publish_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                        {{ old('publish_beasiswa', $beasiswa->publish) == 'publish' ? 'checked' : '' }}>
                                    <span class="text-gray-600">Ya</span>
                                </label>
                            </div>
                            <div class="mb-4">
                                <label for="unpublish" class="flex items-center space-x-3">
                                    <input type="radio" id="unpublish" name="publish_beasiswa" value="0"
                                        class="form-radio h-5 w-5 text-blue-500 rounded-full @error('publish_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                        {{ old('publish_beasiswa', $beasiswa->publish) == '0' ? 'checked' : '' }}>
                                    <span class="text-gray-600">Tidak</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Beasiswa -->
                    <div>
                        <p class="block text-sm font-medium text-gray-700">Jenis Beasiswa</p>
                        @error('jenis_beasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="mb-4">
                                <label for="full" class="flex items-center space-x-3">
                                    <input type="radio" id="full" name="jenis_beasiswa" value="full"
                                        class="form-radio h-5 w-5 text-blue-500 rounded-full @error('jenis_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                        {{ old('jenis_beasiswa', $beasiswa->jenis_beasiswa) == 'full' ? 'checked' : '' }}>
                                    <span class="text-gray-600">Full</span>
                                </label>
                            </div>
                            <div class="mb-4">
                                <label for="half" class="flex items-center space-x-3">
                                    <input type="radio" id="half" name="jenis_beasiswa" value="half"
                                        class="form-radio h-5 w-5 text-blue-500 rounded-full @error('jenis_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                        {{ old('jenis_beasiswa', $beasiswa->jenis_beasiswa) == 'half' ? 'checked' : '' }}>
                                    <span class="text-gray-600">Half</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <p class="block text-sm font-medium text-gray-700">Tipe Beasiswa</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="mb-4">
                            <label for="internal" class="flex items-center space-x-3">
                                <input type="radio" id="internal" name="tipe_beasiswa" value="internal"
                                    class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                    {{ old('tipe_beasiswa', $beasiswa->tipe_beasiswa) == 'internal' ? 'checked' : '' }} disabled>
                                <span class="text-gray-600">Internal</span>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label for="kipk" class="flex items-center space-x-3">
                                <input type="radio" id="kipk" name="tipe_beasiswa" value="kipk"
                                    class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                    {{ old('tipe_beasiswa', $beasiswa->tipe_beasiswa) == 'kipk' ? 'checked' : '' }} disabled>
                                <span class="text-gray-600">KIPK</span>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label for="eksternal" class="flex items-center space-x-3">
                                <input type="radio" id="eksternal" name="tipe_beasiswa" value="eksternal"
                                    class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                    {{ old('tipe_beasiswa', $beasiswa->tipe_beasiswa) == 'eksternal' ? 'checked' : '' }} disabled>
                                <span class="text-gray-600">Eksternal</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Tanggal Mulai -->
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <div class="relative mt-1">
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{old('tanggal_mulai', $beasiswa->tanggal_mulai)}}"
                                    class="block w-full border @error('tanggal_mulai') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            </div>
                            @error('tanggal_mulai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div>
                            <label for="tanggal_berakhir" class="block text-sm font-medium text-gray-700">Tanggal Berakhir</label>
                            <div class="relative mt-1">
                                <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" value="{{old('tanggal_berakhir', $beasiswa->tanggal_berakhir)}}"
                                class="block w-full border @error('tanggal_berakhir') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            </div>
                            @error('tanggal_berakhir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div id="beasiswa-internal" class="hidden">
                        <!-- Kuota Beasiswa -->
                        <div>
                            <label for="kuota_beasiswa" class="block text-sm font-medium text-gray-700">Kuota Beasiswa</label>
                            <input type="number" id="kuota_beasiswa" name="kuota_beasiswa" placeholder="Kuota Beasiswa" value="{{old('kuota_beasiswa',$beasiswa->kuota)}}"
                                class="block w-full border @error('kuota_beasiswa') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            @error('kuota_beasiswa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <br>

                        <!-- Jenjang Pendidikan -->
                        <div class="relative">
                            <label for="jenjang_pendidikan" class="block text-sm font-medium text-gray-700">Jenjang Pendidikan</label>
                            <div id="selected-tags-jenjang" class="flex flex-wrap gap-2 mb-2"></div>
                            <input type="search" id="jenjang_pendidikan" name="input_jenjang_pendidikan" placeholder="Jenjang Pendidikan"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                            oninput="fetchJenjangTags()" autocomplete="off" onkeydown="if (event.keyCode === 13) { event.preventDefault(); }">
                            <div id="jenjang-suggestions" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto "></div>
                            <div id="tag-counter-jenjang" class="mb-2 text-sm text-gray-600">Jumlah jenjang yang dipilih: 0</div>
                        </div>

                        <!-- Syarat Beasiswa -->
                        <div class="relative">
                            <label for="syarat_beasiswa" class="block text-sm font-medium text-gray-700">Syarat-Syarat Beasiswa</label>
                            <div id="selected-tags-syarat" class="flex flex-wrap gap-2 mb-2"></div>
                            <input type="search" id="syarat_beasiswa" name="input_syarat_beasiswa" placeholder="Syarat-syarat Beasiswa"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                            oninput="fetchBeasiswaTags()" autocomplete="off" onkeydown="if (event.keyCode === 13) { event.preventDefault(); addBeasiswaTag(this.value); this.nextElementSibling.classList.add('hidden');}">
                            <div id="syarat-suggestions-beasiswa" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            <div id="tag-counter-beasiswa" class="mb-2 text-sm text-gray-600">Jumlah syarat yang dipilih: 0</div>
                        </div>

                        <!-- Benefit Beasiswa -->
                        <div class="relative">
                            <label for="benefit_beasiswa" class="block text-sm font-medium text-gray-700">Benefit Beasiswa</label>
                            <div id="selected-tags-benefit" class="flex flex-wrap gap-2 mb-2"></div>
                            <input type="search" id="benefit_beasiswa" name="input_benefit_beasiswa" placeholder="Benefit Beasiswa"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                            oninput="fetchBenefitTags()" autocomplete="off" onkeydown="if (event.keyCode === 13) { event.preventDefault(); addBenefitTag(this.value); this.nextElementSibling.classList.add('hidden');}">
                            <div id="benefit-suggestions-beasiswa" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            <div id="tag-counter-benefit" class="mb-2 text-sm text-gray-600">Jumlah benefit yang dipilih: 0</div>
                        </div>

                        <!-- Syarat Dokumen Beasiswa -->
                        <div id="form-container">
                            <div class="grid grid-cols-12 gap-4 items-center" id="form-row-1">
                                <!-- Input Syarat Dokumen -->
                                <div class="col-span-6 relative">
                                    <label for="dokumen-1" class="block text-sm font-medium text-gray-700 mb-1">Syarat Dokumen</label>
                                    <input
                                        type="text"
                                        id="dokumen-1"
                                        name="nama_dokumen[]"

                                        placeholder="Masukkan dokumen"
                                        class="syarat_dokumen col-span-2 w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        oninput="fetchDokumenTags(1)"
                                        onkeydown="handleDokumenKeydown(event, 1)"
                                    />

                                    <div id="syarat-suggestions-dokumen-1"
                                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                                </div>

                                <!-- Input Unggah Format Dokumen -->
                                <div class="col-span-5">
                                    <label for="unggah-1" class="block text-sm font-medium text-gray-700 mb-1">Unggah Format Dokumen</label>
                                    <input
                                        type="file"
                                        id="unggah-1"
                                        class="w-1/3 text-gray-500 file:mr-6 file:py-2 file:px-4 file:border-0 file:bg-orange-400 hover:file:bg-blue-100"
                                        name="dokumen_file[]"
                                        onchange="addDokumenFile(this.files[0], 1)"
                                    />
                                    <span id="dokumen-name-1" class="w-2/3 text-gray-500 ml-[-15px] bg-white">Belum ada file yang dipilih</span>
                                </div>

                                <div class="col-span-1 justify-center flex items-center mt-7">
                                    <div class="bg-red-400 hover:bg-red-600 rounded">
                                        <button
                                            type="button"
                                            class="px-3 text-sm font-medium"
                                            onclick="removeFormRow(1)"
                                        >
                                            X
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah Syarat Dokumen -->
                        <div class="mt-4">
                            <button
                                type="button"
                                id="add-button"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium"
                                onclick="createFormRow()"
                                >
                                <span class="text-xl mr-1">+</span> Tambahkan Syarat Dokumen
                            </button>
                        </div>
                    </div>
                    <div id="beasiswa-eksternal">
                        <div>
                            <label for="link_beasiswa" class="block text-sm font-medium text-gray-700">Link Beasiswa</label>
                            <input type="text" id="link_beasiswa" name="link_beasiswa" placeholder="link Beasiswa" value="{{old('link_beasiswa', $link_beasiswa)}}"
                                class="block w-full border @error('link_beasiswa') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            @error('link_beasiswa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <p class="@error('poster') border-red-500 @enderror block text-sm font-medium text-gray-700">Poster Beasiswa</p>
                        <div class="mb-4">
                            <label for="poster_beasiswa" class="cursor-pointer block w-full px-3 py-3 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <i class="fa-duotone fa-solid fa-paperclip"></i>
                                <span id="file-name" class="ml-2 text-gray-600">Pilih file</span>
                                <input type="file" id="poster_beasiswa" name="input_poster[]" class="hidden" accept="image/*" multiple onchange="displayFileNamesAndPreview()">
                            </label>
                            @error('poster')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('poster.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button onclick="event.preventDefault(); removeAllFiles();" class="text-red-500 ml-2 hover:underline">Hapus Semua</button>
                        <div id="preview-container" class="flex flex-wrap gap-4 mt-2"></div> <!-- Preview gambar -->

                        <div id="hidden-input-container"></div>

                        <!-- Modal untuk menampilkan gambar besar -->
                        <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
                            <div class="relative max-w-full max-h-full">
                                <img id="modal-image" class="max-w-screen max-h-screen object-contain rounded-md shadow-lg">
                                <button id="close-modal" class="absolute top-2 right-2 bg-white text-black rounded-full p-1" onclick="event.preventDefault()">X</button>
                            </div>
                        </div>
                    <div>
                        <button type="submit" style="background-color: #FF8E07" class="block w-full items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white  hover:bg-[#D97600] ">Submit</button>
                        {{-- <button type="button" onclick="createHiddenInput()" style="background-color: #FF8E07" class="block w-full items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white  hover:bg-[#D97600] ">Buttton</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

@else


    <div class="max-w-10xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px4 py-6 sm:px-0">
            <div class="bg-white rounded-lg p-6">
                <p>Gunakan Data Beasiswa yang sudah dibuat?</p>
                <div class="bg-[#FF8E07] rounded cursor-pointer p-1 mb-2 hover:cursor-pointer flex items-center" onclick="showPopup()">
                    <span class="text-xl mx-2">+</span> Template Data Beasiswa
                </div>

                <form id="beasiswa-form" action="{{ route('beasiswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Nama Beasiswa -->
                        <div class="mb-4">
                            <label for="nama_beasiswa" class="block text-sm font-medium text-gray-700">Nama Beasiswa</label>
                            <input
                            type="text"
                                id="nama_beasiswa"
                                name="nama_beasiswa"
                                placeholder="Nama Beasiswa"
                                value="{{old('nama_beasiswa')}}"
                                class="block w-full border @error('nama_beasiswa') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                            >
                            @error('nama_beasiswa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Sumber Beasiswa -->
                        <div>
                            <label for="sumber_beasiswa" class="block text-sm font-medium text-gray-700">Sumber Beasiswa</label>
                            <input type="text" id="sumber_beasiswa" name="sumber_beasiswa" placeholder="Sumber Beasiswa" value="{{old('sumber_beasiswa')}}"
                                class="block w-full border @error('sumber_beasiswa') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            @error('sumber_beasiswa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Beasiswa</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                                    class="mt-1 block w-full px-3 py-2 border @error('deskripsi') border-red-500 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" autocomplete="on">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publish Penerima Beasiswa -->
                    <div>
                        <p class="block text-sm font-medium text-gray-700">Terbitkan Penerima Beasiswa?</p>
                        @error('publish_beasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="mb-4">
                                <label for="publish" class="flex items-center space-x-3">
                                    <input type="radio" id="publish" name="publish_beasiswa" value="1"
                                        class="form-radio h-5 w-5 text-blue-500 rounded-publish @error('publish_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                        {{ old('publish_beasiswa') == '1' ? 'checked' : '' }}>
                                    <span class="text-gray-600">Ya</span>
                                </label>
                            </div>
                            <div class="mb-4">
                                <label for="unpublish" class="flex items-center space-x-3">
                                    <input type="radio" id="unpublish" name="publish_beasiswa" value="0"
                                        class="form-radio h-5 w-5 text-blue-500 rounded-full @error('publish_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                        {{ old('publish_beasiswa') == '0' ? 'checked' : '' }}>
                                    <span class="text-gray-600">Tidak</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Beasiswa -->
                    <div>
                        <p class="block text-sm font-medium text-gray-700">Jenis Beasiswa</p>
                        @error('jenis_beasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="mb-4">
                                <label for="full" class="flex items-center space-x-3">
                                    <input type="radio" id="full" name="jenis_beasiswa" value="full"
                                        class="form-radio h-5 w-5 text-blue-500 rounded-full @error('jenis_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                        {{ old('jenis_beasiswa') == 'full' ? 'checked' : '' }}>
                                    <span class="text-gray-600">Full</span>
                                </label>
                            </div>
                            <div class="mb-4">
                                <label for="half" class="flex items-center space-x-3">
                                    <input type="radio" id="half" name="jenis_beasiswa" value="half"
                                        class="form-radio h-5 w-5 text-blue-500 rounded-full @error('jenis_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                        {{ old('jenis_beasiswa') == 'half' ? 'checked' : '' }}>
                                    <span class="text-gray-600">Half</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <p class="block text-sm font-medium text-gray-700">Tipe Beasiswa</p>
                    @error('tipe_beasiswa')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="mb-4">
                            <label for="internal" class="flex items-center space-x-3">
                                <input type="radio" id="internal" name="tipe_beasiswa" value="internal"
                                    class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                    {{ old('tipe_beasiswa') == 'internal' ? 'checked' : '' }} onclick="showForm(this.value)">
                                <span class="text-gray-600">Internal</span>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label for="kipk" class="flex items-center space-x-3">
                                <input type="radio" id="kipk" name="tipe_beasiswa" value="kipk"
                                    class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                    {{ old('tipe_beasiswa') == 'kipk' ? 'checked' : '' }} onclick="showForm(this.value)">
                                <span class="text-gray-600">KIPK</span>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label for="eksternal" class="flex items-center space-x-3">
                                <input type="radio" id="eksternal" name="tipe_beasiswa" value="eksternal"
                                    class="form-radio h-5 w-5 text-blue-500 rounded-full @error('tipe_beasiswa') border-red-500 @enderror focus:ring-blue-500"
                                    {{ old('tipe_beasiswa') == 'eksternal' ? 'checked' : '' }} onclick="showForm(this.value)">
                                <span class="text-gray-600">Eksternal</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Tanggal Mulai -->
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <div class="relative mt-1">
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                    class="block w-full border @error('tanggal_mulai')
                        border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                                        value="{{old('tanggal_mulai')}}"
                                        autocomplete="on">
                            </div>
                            @error('tanggal_mulai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div>
                            <label for="tanggal_berakhir" class="block text-sm font-medium text-gray-700">Tanggal Berakhir</label>
                            <div class="relative mt-1">
                                <input type="date" id="tanggal_berakhir" name="tanggal_berakhir"
                                class="block w-full border @error('tanggal_berakhir')
                border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                                value="{{old('tanggal_berakhir')}}"
                                autocomplete="on">
                            </div>
                            @error('tanggal_berakhir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="beasiswa-internal" class="hidden">
                        <!-- Kuota Beasiswa -->
                        <div>
                            <label for="kuota_beasiswa" class="block text-sm font-medium text-gray-700">Kuota Beasiswa</label>
                            <input type="number" id="kuota_beasiswa" name="kuota_beasiswa" value="{{old('kuota_beasiswa')}}" placeholder="Kuota Beasiswa"
                                class="block w-full border @error('kuota_beasiswa') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                        </div>
                        @error('kuota_beasiswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <br>


                        <!-- Jenjang Pendidikan -->
                        <div class="relative">
                            <label for="jenjang_pendidikan" class="block text-sm font-medium text-gray-700">Jenjang Pendidikan</label>
                            <div id="selected-tags-jenjang" class="flex flex-wrap gap-2 mb-2">
                            </div>
                            <input type="search" id="jenjang_pendidikan" name="input_jenjang_pendidikan" placeholder="Jenjang Pendidikan"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                            oninput="fetchJenjangTags()" autocomplete="off" onkeydown="if (event.keyCode === 13) { event.preventDefault(); }">
                            <div id="jenjang-suggestions" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto "></div>
                            <div id="tag-counter-jenjang" class="mb-2 text-sm text-gray-600">Jumlah jenjang yang dipilih: 0</div>
                        </div>


                        <!-- Syarat Beasiswa -->
                        <div class="relative">
                            <label for="syarat_beasiswa" class="block text-sm font-medium text-gray-700">Syarat-Syarat Beasiswa</label>
                            <div id="selected-tags-syarat" class="flex flex-wrap gap-2 mb-2">
                            </div>

                            <input type="search" id="syarat_beasiswa" name="input_syarat_beasiswa" placeholder="Syarat-syarat Beasiswa"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                            oninput="fetchBeasiswaTags()" autocomplete="off" onkeydown="if (event.keyCode === 13) { event.preventDefault(); addBeasiswaTag(this.value); this.nextElementSibling.classList.add('hidden');}">
                            <div id="syarat-suggestions-beasiswa" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            <div id="tag-counter-beasiswa" class="mb-2 text-sm text-gray-600">Jumlah syarat yang dipilih: 0</div>
                        </div>

                        <!-- Benefit Beasiswa -->
                        <div class="relative">
                            <label for="benefit_beasiswa" class="block text-sm font-medium text-gray-700">Benefit Beasiswa</label>
                            <div id="selected-tags-benefit" class="flex flex-wrap gap-2 mb-2">
                            </div>

                            <input type="search" id="benefit_beasiswa" name="input_benefit_beasiswa" placeholder="Benefit Beasiswa"
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                            oninput="fetchBenefitTags()" autocomplete="off" onkeydown="if (event.keyCode === 13) { event.preventDefault(); addBenefitTag(this.value); this.nextElementSibling.classList.add('hidden');}">
                            <div id="benefit-suggestions-beasiswa" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            <div id="tag-counter-benefit" class="mb-2 text-sm text-gray-600">Jumlah benefit yang dipilih: 0</div>
                        </div>

                        <div id="form-container">
                            <div class="grid grid-cols-12 gap-4 items-center" id="form-row-1">
                                <!-- Input Syarat Dokumen -->
                                <div class="col-span-6 relative">
                                    <label for="dokumen-1" class="block text-sm font-medium text-gray-700 mb-1">Syarat Dokumen</label>
                                    <input
                                        type="text"
                                        id="dokumen-1"
                                        name="nama_dokumen[]"
                                        placeholder="Masukkan dokumen"
                                        class="syarat_dokumen col-span-2 w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        oninput="fetchDokumenTags(1)"
                                        onkeydown="handleDokumenKeydown(event, 1)"
                                    />
                                    <div id="syarat-suggestions-dokumen-1"
                                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                                </div>
                                <!-- Input Unggah Format Dokumen -->
                                <div class="col-span-5">
                                    <label for="unggah-1" class="block text-sm font-medium text-gray-700 mb-1">Unggah Format Dokumen</label>
                                    <input
                                        type="file"
                                        id="unggah-1"
                                        class="w-1/3 text-gray-500 file:mr-6 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        name="dokumen_file[]"
                                        onchange="addDokumenFile(this.files[0], 1)"
                                    />
                                    <span id="dokumen-name-1" class="w-2/3 text-gray-500 ml-[-15px] bg-white">Belum ada file yang dipilih</span>
                                </div>
                                <div class="col-span-1 justify-center flex items-center mt-7">
                                    <div class="bg-red-400 hover:bg-red-600 rounded">
                                        <button
                                            type="button"
                                            class="px-3 text-sm font-medium"
                                            onclick="removeFormRow(1)"
                                        >
                                            X
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tombol Tambah Syarat Dokumen -->
                        <div class="mt-4">
                            <button
                                type="button"
                                id="add-button"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium"
                                onclick="createFormRow()"
                            >
                                <span class="text-xl mr-1">+</span> Tambahkan Syarat Dokumen
                            </button>
                        </div>
                    </div>
                    <div id="beasiswa-eksternal" class="hidden">
                        <div>
                            <label for="link_beasiswa" class="block text-sm font-medium text-gray-700">Link Beasiswa</label>
                            <input type="text" id="link_beasiswa" name="link_beasiswa" placeholder="link Beasiswa" value="{{old('link_beasiswa')}}"
                                class="block w-full border @error('link_beasiswa') border-red-500 @enderror rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                            @error('Link_beasiswa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <p class="@error('poster') border-red-500 @enderror block text-sm font-medium text-gray-700">Poster Beasiswa</p>
                    <div class="mb-4">
                        <label for="poster_beasiswa" class="cursor-pointer block w-full px-3 py-3 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <span id="file-name" class="ml-2 text-gray-600">Pilih file</span>
                            <input type="file" id="poster_beasiswa" name="poster[]" class="hidden" accept="image/*" multiple onchange="displayFileNamesAndPreview()">
                        </label>
                        @error('poster')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('poster.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button onclick="event.preventDefault(); removeAllFiles();" class="text-red-500 ml-2 hover:underline">Hapus Semua</button>
                    <div id="preview-container" class="flex flex-wrap gap-4 mt-2"></div> <!-- Preview gambar -->

                    <!-- Modal untuk menampilkan gambar besar -->
                    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
                        <div class="relative max-w-full max-h-full">
                            <img id="modal-image" class="max-w-screen max-h-screen object-contain rounded-md shadow-lg">
                            <button id="close-modal" class="absolute top-2 right-2 bg-white text-black rounded-full p-1" onclick="event.preventDefault()">X</button>
                        </div>
                    </div>
                    <div id="hidden-input-container"></div>
                    <div>
                        <button type="submit" style="background-color: #FF8E07" class="block w-full items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white  hover:bg-[#D97600] ">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- popup --}}
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
            <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">Pilih Template Beasiswa</h2>

            {{-- Daftar Template Beasiswa --}}
            <ul id="template-list" class="space-y-4">
                <li id="loading-indicator" class="text-center text-gray-600">Memuat template...</li>
            </ul>

            <div id="pagination-controls" class="py-3 hidden"></div>
        </div>
    </div>

@endif

@section('scripts')
    <script>
        const routes = {
            searchJenjang: "{{ route('Beasiswa.search_jenjang') }}",
            searchSyarat: "{{ route('Beasiswa.search_syarat') }}",
            searchBenefit: "{{ route('Beasiswa.search_benefit') }}",
            searchDokumen: "{{ route('Beasiswa.search_dokumen') }}",
        };
        </script>
    <script src="{{asset('assets/js/form-beasiswa.js')}}"></script>
    <script>
        @foreach (old('syarat_dokumen', []) as $old_dokumen_tag )
            addDokumenTag(@json(@$old_dokumen_tag));
        @endforeach
        @foreach (old('syarat_beasiswa', []) as $old_syarat_tag )
            addBeasiswaTag(@json(@$old_syarat_tag));
        @endforeach
        @foreach (old('benefit_beasiswa', []) as $old_benefit_tag )
            addBenefitTag(@json(@$old_benefit_tag));
        @endforeach
        @foreach (old('jenjang_pendidikan', []) as $old_jenjang_tag )
            addJenjangTag(@json(@$old_jenjang_tag));
        @endforeach
    </script>
@endsection

@section('scriptsForEditBeasiswa')
@if($beasiswa != null)
<script>

    var beasiswa =  {!! json_encode($beasiswa, JSON_HEX_TAG) !!}
    var syarat =  {!! json_encode($syarat, JSON_HEX_TAG) !!}
    var jenjang =  {!! json_encode($jenjang, JSON_HEX_TAG) !!}
    var dokumen =  {!! json_encode($dokumen, JSON_HEX_TAG) !!}
    var link_dokumen =  {!! json_encode($link_dokumen, JSON_HEX_TAG) !!}
    var benefit =  {!! json_encode($benefit, JSON_HEX_TAG) !!}
    var poster =  {!! json_encode($poster, JSON_HEX_TAG) !!}

    window.addEventListener('load', function() {
    loadBeasiswaData();
    });
</script>
@endif
@endsection

