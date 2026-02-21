@extends('layouts.main')

@section('content')
@include('component.navbar', ['path' => 'Pengaturan', 'id' => null])

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pengaturan Akun</h1>
            <p class="text-gray-600">Kelola informasi profil dan preferensi akun Anda</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-8">
            <nav class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit">
                <button class="tab-btn active flex items-center px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200" data-target="profile">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil
                </button>
                <button class="tab-btn flex items-center px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200" data-target="notifications">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v.93z"></path>
                    </svg>
                    Notifikasi
                </button>
            </nav>
        </div>

        <!-- Profile Tab Content -->
        <div id="profile" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Picture Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                        <div class="text-center">
                            <div class="relative  inline-block">
                                <img src="{{ $user_img }}" alt="Profile" class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-gray-100">
                                <button onclick="showChangePhotoModal()" class="absolute bottom-5 right-10 bg-orange-600 text-white p-2 rounded-full hover:bg-orange-700 transition-colors shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $nama_depan . ' ' . $nama_belakang }}</h3>
                            <p class="text-gray-600 text-sm">{{ $role_name }}</p>
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">{{ $mahasiswa ? 'NIM' : 'NIP' }}</p>
                                <p class="text-sm font-medium text-gray-900">{{ $nim ?: $nip }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Information Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Personal</h3>
                            <button onclick="showEditProfileModal()" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profil
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Nama Depan</label>
                                <p class="text-gray-900 font-medium">{{ $nama_depan }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Nama Belakang</label>
                                <p class="text-gray-900 font-medium">{{ $nama_belakang }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Email</label>
                                <p class="text-gray-900 font-medium">{{ $email }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Jenis Kelamin</label>
                                <p class="text-gray-900 font-medium">{{ $jk }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Nomor Handphone</label>
                                <p class="text-gray-900 font-medium">{{ $no_hp ?? 'Belum diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scholarship Section (Only for Students) -->
            @if($mahasiswa)
            <div class="mt-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Beasiswa</h3>
                    @if($beasiswa->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($beasiswa as $item)
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $item->beasiswa->nama_beasiswa }}</h4>
                                            <p class="text-sm text-gray-600">{{ ucfirst($item->beasiswa->jenis_beasiswa) }} Scholarship</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Diterima
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $item->beasiswa->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada beasiswa</h4>
                            <p class="text-gray-600">Anda belum menerima beasiswa apapun</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Notifications Tab Content -->
        <div id="notifications" class="tab-content hidden">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Preferensi Notifikasi</h3>
                    <p class="text-gray-600">Kelola bagaimana Anda menerima notifikasi dari sistem</p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <input id="enable-notifications" name="notification" type="radio" class="mt-1 w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                        <div class="flex-1">
                            <label for="enable-notifications" class="block font-medium text-gray-900 mb-1">
                                Aktifkan Notifikasi
                            </label>
                            <p class="text-sm text-gray-600">
                                Terima notifikasi untuk update beasiswa, pengumuman penting, dan status aplikasi Anda.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <input id="disable-notifications" name="notification" type="radio" class="mt-1 w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <div class="flex-1">
                            <label for="disable-notifications" class="block font-medium text-gray-900 mb-1">
                                Nonaktifkan Notifikasi
                            </label>
                            <p class="text-sm text-gray-600">
                                Hentikan semua notifikasi push dan email dari sistem.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <button class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Photo Modal -->
<div id="change-photo-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-white-500 bg-opacity-75 backdrop-blur-sm" onclick="hideChangePhotoModal()"></div>

        <div class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Ganti Foto Profil</h3>
                <button onclick="hideChangePhotoModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('pengaturan.updatefoto', $user_id) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="mb-6">
                    <label for="new_img" class="block text-sm font-medium text-gray-700 mb-2">Pilih Foto Baru</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="new_img" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG atau JPEG (MAX. 2MB)</p>
                            </div>
                            <input id="new_img" name="new_img" type="file" class="hidden" accept="image/*">
                        </label>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="hideChangePhotoModal()" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Update Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="edit-profile-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-white-500 bg-opacity-75 backdrop-blur-sm" onclick="hideEditProfileModal()"></div>

        <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Edit Profil</h3>
                <button onclick="hideEditProfileModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('pengaturan.updateprofil', $user_id) }}" method="POST" class="space-y-4">
                @method('PATCH')
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nama_depan" class="block text-sm font-medium text-gray-700 mb-1">Nama Depan</label>
                        <input id="nama_depan" name="nama_depan" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ $nama_depan }}">
                    </div>
                    <div>
                        <label for="nama_belakang" class="block text-sm font-medium text-gray-700 mb-1">Nama Belakang</label>
                        <input id="nama_belakang" name="nama_belakang" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ $nama_belakang }}">
                    </div>
                </div>

               @if(in_array($user->reviewer?->role->id, [1,2,4]))
                <div>
                    <label for="nama_depan" class="block text-sm font-medium text-gray-700 mb-1">email</label>
                    <input id="nama_depan" name="email" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ $email }}">
                </div>
                @endif

                <div>
                    <label for="jk" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select id="jk" name="jk" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="Pria" {{ old('jk', $jk) == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ old('jk', $jk) == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>

                @if($mahasiswa)
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                    <input id="nim" name="nim" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ $nim }}">
                </div>
                @endif

                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor Handphone</label>
                    <input id="no_hp" name="no_hp" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ $no_hp ?? '' }}">
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="hideEditProfileModal()" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .tab-btn {
        @apply text-gray-600 bg-transparent;
    }

    .tab-btn.active {
        @apply text-blue-600 bg-white shadow-sm;
    }

    .tab-btn:hover:not(.active) {
        @apply text-gray-800 bg-gray-50;
    }
</style>

<script>
    // Tab functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.getAttribute('data-target');

            // Remove active class from all buttons
            tabBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            // Hide all tab contents
            tabContents.forEach(content => content.classList.add('hidden'));
            // Show target content
            document.getElementById(target).classList.remove('hidden');
        });
    });

    // Modal functions
    function showChangePhotoModal() {
        document.getElementById('change-photo-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function hideChangePhotoModal() {
        document.getElementById('change-photo-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function showEditProfileModal() {
        document.getElementById('edit-profile-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function hideEditProfileModal() {
        document.getElementById('edit-profile-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // File upload preview
    document.getElementById('new_img').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const label = e.target.closest('label');
            const fileName = file.name;
            label.querySelector('p').innerHTML = `<span class="font-semibold">File dipilih:</span> ${fileName}`;
        }
    });

    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideChangePhotoModal();
            hideEditProfileModal();
        }
    });
</script>
@endsection
