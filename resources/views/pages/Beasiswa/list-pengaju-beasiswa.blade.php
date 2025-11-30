@extends('layouts.main')
@section('content')
@include('component.navbar', ['path' => 'Daftar Pengajuan Beasiswa', 'id' => null])

    <!-- Controls Bar -->
    <div class="bg-white border-b border-gray-100">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Search Bar -->
                <div class="relative max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text"
                           id="searchInput"
                           placeholder="Cari nama pengaju atau beasiswa..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Filter Controls -->
                <div class="flex items-center gap-3">
                    <!-- Status Filter -->
                    <div class="relative">
                        <select id="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="diproses">Diproses</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Advanced Filter Button -->
                    <button onclick="showFilterModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter Lanjut
                    </button>

                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 bg-gray-50">
        <div class="px-6 py-6">
            <!-- Table Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <span>Pengaju</span>
                                        <svg class="h-3 w-3 text-gray-400 cursor-pointer hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <span>Beasiswa</span>
                                        <svg class="h-3 w-3 text-gray-400 cursor-pointer hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penyelenggara
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <span>Tanggal</span>
                                        <svg class="h-3 w-3 text-gray-400 cursor-pointer hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="applicationsTable">
                            @foreach ($listPengajuan as $index => $pengajuan)
                                <tr class="hover:bg-gray-50 transition-colors" data-search="{{ strtolower($pengajuan->nama_depan . ' ' . $pengajuan->nama_belakang . ' ' . $pengajuan->nama_beasiswa) }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-800">
                                                        {{ substr($pengajuan->nama_depan, 0, 1) }}{{ substr($pengajuan->nama_belakang, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $pengajuan->nama_depan . ' ' . $pengajuan->nama_belakang }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $pengajuan->nama_beasiswa }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Program Beasiswa
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $pengajuan->sumber }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($pengajuan->status <= 9)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800" data-status="diproses">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Diproses
                                            </span>
                                        @elseif ($pengajuan->status == 10)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800" data-status="diterima">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Diterima
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800" data-status="ditolak">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ url('tracking-pengajuan/' . $pengajuan->id_pengajuan) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-orange-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="hidden text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Modal -->
    <div id="filterModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="hidePopup()"
                class="fixed inset-0 bg-white bg-opacity-75 backdrop-blur-sm transition-opacity"
                aria-hidden="true">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                            Filter Lanjutan
                        </h3>
                        <button onclick="hideFilterModal()" class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form action="{{ url('/pengajuan/list-pengajuan') }}" method="GET" class="px-6 pb-6">
                    <div class="space-y-4">
                        <!-- Nama Beasiswa Filter -->
                        <div>
                            <label for="nama_beasiswa" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Beasiswa
                            </label>
                            <select name="nama_beasiswa" id="nama_beasiswa" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Beasiswa</option>
                                @foreach($namaBeasiswa as $beasiswa)
                                    <option value="{{ $beasiswa }}" {{ request('nama_beasiswa') == $beasiswa ? 'selected' : '' }}>
                                        {{ $beasiswa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal Pengajuan Filter -->
                        <div>
                            <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Pengajuan
                            </label>
                            <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan" value="{{ request('tanggal_pengajuan') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dari Tanggal
                                </label>
                                <input type="date" name="tanggal_dari" id="tanggal_dari" value="{{ request('tanggal_dari') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sampai Tanggal
                                </label>
                                <input type="date" name="tanggal_sampai" id="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-between gap-3 mt-6">
                        <button type="button" onclick="resetFilters()" class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            Reset
                        </button>
                        <button type="submit" class="flex-1 bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#applicationsTable tr');
            let visibleRows = 0;

            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                if (searchData && searchData.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Toggle empty state
            document.getElementById('emptyState').classList.toggle('hidden', visibleRows > 0);
        });

        // Status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            const selectedStatus = e.target.value;
            const rows = document.querySelectorAll('#applicationsTable tr');
            let visibleRows = 0;

            rows.forEach(row => {
                const statusElement = row.querySelector('[data-status]');
                if (!selectedStatus || (statusElement && statusElement.getAttribute('data-status') === selectedStatus)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Toggle empty state
            document.getElementById('emptyState').classList.toggle('hidden', visibleRows > 0);
        });

        // Modal functions
        function showFilterModal() {
            document.getElementById('filterModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideFilterModal() {
            document.getElementById('filterModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function resetFilters() {
            document.getElementById('nama_beasiswa').value = '';
            document.getElementById('tanggal_pengajuan').value = '';
            document.getElementById('tanggal_dari').value = '';
            document.getElementById('tanggal_sampai').value = '';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideFilterModal();
            }
        });

        // Combined search and filter functionality
        function applyAllFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#applicationsTable tr');
            let visibleRows = 0;

            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                const statusElement = row.querySelector('[data-status]');

                const matchesSearch = !searchTerm || (searchData && searchData.includes(searchTerm));
                const matchesStatus = !statusFilter || (statusElement && statusElement.getAttribute('data-status') === statusFilter);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Toggle empty state
            document.getElementById('emptyState').classList.toggle('hidden', visibleRows > 0);
        }

        // Update event listeners to use combined filtering
        document.getElementById('searchInput').addEventListener('input', applyAllFilters);
        document.getElementById('statusFilter').addEventListener('change', applyAllFilters);
    </script>
@endsection
