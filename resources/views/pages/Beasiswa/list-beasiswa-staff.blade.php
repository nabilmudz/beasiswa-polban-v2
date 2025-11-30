@extends('layouts.main')

@section('content')
    @include('component.navbar', [
        'path' => 'List Beasiswa',
        'id' => null
    ])
<style>
.btn-logout {
        background-color: #d33 !important; /* merah */
        color: white !important;
        border-radius: 6px;
        padding: 8px 16px;
        border: none;
        margin-right: 1rem;
    }

    .btn-logout:hover {
        background-color: #b71c1c !important;
    }

    .btn-cancel {
        background-color: #3085d6 !important; /* biru */
        color: white !important;
        border-radius: 6px;
        padding: 8px 16px;
        border: none;
    }

    .btn-cancel:hover {
        background-color: #2563eb !important;
    }

</style>

    <div class="min-h-screen bg-gray-50">
        <!-- Page Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Manajemen Beasiswa</h1>
                        <p class="text-gray-600 mt-1">Kelola semua program beasiswa dari satu tempat</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Bar -->
                        <div class="relative">
                            <form method="GET" action="{{ route('beasiswa.list-beasiswa-staff') }}">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input type="text"
                                           name="search"
                                           placeholder="Cari beasiswa..."
                                           value="{{ request('search') }}"
                                           class="pl-10 pr-4 py-2.5 w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>
                            </form>
                        </div>

                        <!-- Filter Button -->
                        <button onclick="showPopup()"
                                class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                            </svg>
                            Filter
                        </button>

                        <!-- Add Button -->
                        <a href="{{ route('beasiswa.create') }}"
                           class="inline-flex items-center px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-all shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Beasiswa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards (Optional) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                @php
                    $totalBeasiswa = $beasiswa->total();
                    $aktifCount = $beasiswa->filter(function($b) {
                        return $b->tanggal_mulai <= now() && $b->tanggal_berakhir >= now();
                    })->count();
                    $upcomingCount = $beasiswa->where('tanggal_mulai', '>', now())->count();
                    $expiredCount = $beasiswa->where('tanggal_berakhir', '<', now())->count();
                @endphp

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Beasiswa</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalBeasiswa }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Sedang Berjalan</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $aktifCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Segera Dibuka</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $upcomingCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Berakhir</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $expiredCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Beasiswa
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penyelenggara
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Timeline
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($beasiswa as $b)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Nama Beasiswa -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $b->nama_beasiswa }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $b->tipe_beasiswa }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Penyelenggara -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $b->sumber }}</div>
                                    </td>

                                    <!-- Timeline -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="space-y-1">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($b->tanggal_mulai)->format('d M Y') }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($b->tanggal_berakhir)->format('d M Y') }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = $b->tanggal_mulai <= now() && $b->tanggal_berakhir >= now()
                                                ? 'Berlangsung'
                                                : ($b->tanggal_mulai > now() ? 'Segera' : 'Berakhir');

                                            $statusConfig = [
                                                'Berlangsung' => [
                                                    'class' => 'bg-green-100 text-green-800 border-green-200',
                                                    'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                                                ],
                                                'Segera' => [
                                                    'class' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                    'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>'
                                                ],
                                                'Berakhir' => [
                                                    'class' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                    'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                                                ]
                                            ];
                                        @endphp

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $statusConfig[$status]['class'] }}">
                                            {!! $statusConfig[$status]['icon'] !!}
                                            <span class="ml-1">{{ $status }}</span>
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">

                                                                                    @php
                                            $route = match($b->tipe_beasiswa) {
                                                'kipk' => route('beasiswa.detail-beasiswa-kipk', ['id' => $b->id]),
                                                'eksternal' => route('beasiswa.detail-beasiswa-eksternal', ['id' => $b->id]),
                                                default => route('beasiswa.show', ['id' => $b->id]),
                                            };
                                        @endphp

                                        <a href="{{ $route }}" class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                        </a>

                                            <!-- Edit -->
                                            <a href="{{ route('beasiswa.edit', ['id' => $b->id]) }}"
                                               class="text-blue-600 hover:text-blue-900 transition-colors"
                                               title="Edit Beasiswa">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                           <form action="{{ route('beasiswa.destroy', $b->id) }}"
      method="POST"
      class="inline delete-beasiswa-form">
    @csrf
    @method('DELETE')
    <button type="button"
            class="text-red-600 hover:text-red-900 transition-colors delete-beasiswa-btn"
            title="Hapus Beasiswa">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
    </button>
</form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada beasiswa ditemukan</h3>
                                            <p class="text-gray-500">Mulai dengan menambahkan beasiswa baru atau ubah filter pencarian Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $beasiswa->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <!-- Filter Modal -->
<div id="popup" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div onclick="hidePopup()" class="fixed inset-0 bg-white bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Trick to center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
            <!-- Close Button -->
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button"
                        onclick="hidePopup()"
                        class="bg-white rounded-md text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="sm:flex sm:items-start">
                <div class="w-full">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6" id="modal-title">
                            Filter Beasiswa
                        </h3>

                        <!-- Form Filter -->
                        <form action="{{ route('beasiswa.list-beasiswa-staff') }}" method="GET" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <!-- Jenis Beasiswa -->
                                    <div>
                                        <label class="text-base font-medium text-gray-900">Jenis Beasiswa</label>
                                        <div class="mt-4 space-y-3">
                                            <div class="flex items-center">
                                                <input id="half" name="jenis_beasiswa[]" type="checkbox" value="half"
                                                       {{ in_array('half', request('jenis_beasiswa', [])) ? 'checked' : '' }}
                                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <label for="half" class="ml-3 text-sm text-gray-700">Half Scholarship</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="full" name="jenis_beasiswa[]" type="checkbox" value="full"
                                                       {{ in_array('full', request('jenis_beasiswa', [])) ? 'checked' : '' }}
                                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <label for="full" class="ml-3 text-sm text-gray-700">Full Scholarship</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Jenjang Pendidikan -->
                                    <div>
                                        <label class="text-base font-medium text-gray-900">Jenjang Pendidikan</label>
                                        <div class="mt-4 space-y-3">
                                            <div class="flex items-center">
                                                <input id="d3" name="jenjang_pendidikan[]" type="checkbox" value="D3"
                                                       {{ in_array('D3', request('jenjang_pendidikan', [])) ? 'checked' : '' }}
                                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <label for="d3" class="ml-3 text-sm text-gray-700">Diploma III (D3)</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="d4" name="jenjang_pendidikan[]" type="checkbox" value="D4"
                                                       {{ in_array('D4', request('jenjang_pendidikan', [])) ? 'checked' : '' }}
                                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <label for="d4" class="ml-3 text-sm text-gray-700">Diploma IV (D4)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Tipe Beasiswa -->
                                    <div>
                                        <label for="tipe_beasiswa" class="block text-base font-medium text-gray-900">Tipe Beasiswa</label>
                                        <select name="tipe_beasiswa" id="tipe_beasiswa"
                                                class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Semua Tipe</option>
                                            <option value="kipk" {{ request('tipe_beasiswa') == 'kipk' ? 'selected' : '' }}>KIP-K</option>
                                            <option value="internal" {{ request('tipe_beasiswa') == 'internal' ? 'selected' : '' }}>Internal</option>
                                            <option value="eksternal" {{ request('tipe_beasiswa') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                                        </select>
                                    </div>

                                    <!-- Jurusan -->
                                    <div>
                                        <label for="jurusan" class="block text-base font-medium text-gray-900">Jurusan Khusus</label>
                                        <select name="jurusan" id="jurusan"
                                                class="mt-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Semua Jurusan</option>
                                            <option value="Teknik Informatika" {{ request('jurusan') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                                            <option value="Teknik Sipil" {{ request('jurusan') == 'Teknik Sipil' ? 'selected' : '' }}>Teknik Sipil</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="mt-6 flex justify-end space-x-4">
                                <!-- Tombol Reset -->
                                <a href="{{ route('beasiswa.list-beasiswa-staff') }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                                    Reset
                                </a>

                                <!-- Tombol Cari -->
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-beasiswa-btn');

        deleteButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const form = btn.closest('.delete-beasiswa-form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Beasiswa ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                                    customClass: {
                    confirmButton: 'btn-logout',
                    cancelButton: 'btn-cancel'
                },
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

<!-- JS untuk Toggle Modal -->
<script>
    function showPopup() {
        document.getElementById('popup').classList.remove('hidden');
    }
    function hidePopup() {
        document.getElementById('popup').classList.add('hidden');
    }
</script>


@endsection
