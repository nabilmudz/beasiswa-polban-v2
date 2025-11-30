@extends('layouts.filter')
@extends('layouts.main')
@section('content')
    @include('component.navbar', ['path' => 'Pengumuman Beasiswa', 'id' => null])

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-orange-600 via-yellow-700 to-yellow-700 text-white">
        <div class="px-6 py-12">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-3xl text-white md:text-4xl font-bold mb-4">
                    Cari Pengumuman Beasiswa
                </h1>
                <p class="text-xl opacity-90 mb-8">

                </p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto relative">
                    <form method="GET" action="{{ route('beasiswa.index') }}" class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="searchInput"
                               placeholder="Cari nama beasiswa, penyelenggara, atau kata kunci..."
                               class="w-full pl-12 pr-20 py-4 text-gray-900 bg-white rounded-2xl shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-opacity-50 transition-all"
                               value="{{ request('search') }}">
                        <button type="submit" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <span class="bg-blue-600 text-white px-6 py-2 rounded-xl hover:bg-blue-700 transition-colors font-medium">
                                Cari
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Controls -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Beasiswa Tersedia
                    </h2>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        {{ count($beasiswa) }} Program
                    </span>
                    @if($isStaff)
                    <a href="{{ route('beasiswa.import-data-beasiswa') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-orange-500 focus:ring-opacity-30 min-w-[140px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        <span>Import Data</span>
                    </a>
                    @endif
                </div>

                <div class="flex items-center space-x-3">
                   <!-- Quick Filters -->
                    <div class="hidden md:flex items-center space-x-3 bg-white p-2 rounded-xl shadow-sm">
                        <button class="filter-btn px-4 py-2 rounded-full text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 transition active " data-filter="all">
                            Semua
                        </button>
                        <button class="filter-btn px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" data-filter="berlangsung">
                            Berlangsung
                        </button>
                        <button class="filter-btn px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" data-filter="segera">
                            Segera
                        </button>
                        <button class="filter-btn px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" data-filter="full">
                            Full
                        </button>
                        <button class="filter-btn px-4 py-2 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" data-filter="half">
                            Partial
                        </button>
                    </div>

                    <!-- Advanced Filter Button -->
                    <button onclick="showPopup()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scholarship Grid -->
    <div class="bg-gray-50 min-h-screen">
        <div class="px-6 py-8">
            <div id="scholarshipGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @php
                    $isAnyBeasiswaReceived = count($beasiswaUserTipe) > 0;
                    $currentDate = now();
                    $oneYearLater = now()->addYear();
                @endphp

                @foreach ($beasiswa as $ba)
                    @php
                        $status = ($ba->tanggal_mulai <= $currentDate && $ba->tanggal_berakhir >= $currentDate)
                            ? "berlangsung"
                            : ($ba->tanggal_mulai > $currentDate
                                ? "segera"
                                : "berakhir");

                        $isReceived = collect($beasiswaUserTipe)->firstWhere('id', $ba->id);
                        if ($isReceived) {
                            $status = strtolower($isReceived['status']);
                        }

                        if ($isAnyBeasiswaReceived && !($ba->status == 'half' && $ba->tanggal_berakhir > $currentDate && $ba->tanggal_berakhir <= $oneYearLater)) {
                            $status = 'closed';
                        }

                        // CHANGED: Always allow registration for viewing purposes
                        $canRegister = true;

                        // Determine if user can actually apply (different from viewing)
                        $canApply = !$isAnyBeasiswaReceived || ($ba->status == 'half' && $ba->tanggal_berakhir > $currentDate && $ba->tanggal_berakhir <= $oneYearLater);

                        $statusColors = [
                            'berlangsung' => 'bg-green-500 text-white',
                            'segera' => 'bg-blue-500 text-white',
                            'berakhir' => 'bg-gray-500 text-white',
                            'closed' => 'bg-red-500 text-white',
                            'diterima' => 'bg-emerald-500 text-white',
                            'ditolak' => 'bg-red-500 text-white'
                        ];

                        $statusText = [
                            'berlangsung' => 'Berlangsung',
                            'segera' => 'Segera Dibuka',
                            'berakhir' => 'Berakhir',
                            'closed' => 'Ditutup',
                            'diterima' => 'Diterima',
                            'ditolak' => 'Ditolak'
                        ];
                    @endphp

                    <div class="beasiswa-card group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden"
                         data-nama-beasiswa="{{ strtolower($ba->nama_beasiswa) }}"
                         data-status="{{ $status }}"
                         data-type="{{ strtolower($ba->jenis_beasiswa) }}"
                         data-tipe="{{ strtolower($ba->tipe_beasiswa) }}">

                        <!-- Image Container -->
                        <div class="relative overflow-hidden h-48">
                            <img src="{{ $ba->link_poster ? $ba->link_poster : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=400&h=300&fit=crop' }}"
                                 alt="{{ $ba->nama_beasiswa }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                            <!-- Status Overlay -->
                            <div class="absolute top-4 left-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-500 text-white' }} shadow-lg">
                                    <div class="w-2 h-2 rounded-full bg-white mr-2 animate-pulse"></div>
                                    {{ $statusText[$status] ?? ucfirst($status) }}
                                </span>
                            </div>


                                                                <a href="{{ route('beasiswa.pengumuman-beasiswa',['id'=>$ba->id])}}" data-nama-beasiswa="{{ $ba->nama_beasiswa }}"
                                   class="absolute inset-0">

                            </a>

                            <!-- CHANGED: Only show overlay if can't apply, but still allow viewing -->
                            @if(!$canApply && ($status === 'closed' || $status === 'berakhir'))
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 shadow-lg">
                                        Hanya Lihat
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    {{ ucfirst($ba->jenis_beasiswa) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Kuota: {{ $ba->kuota }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ ucfirst($ba->tipe_beasiswa) }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                {{ $ba->nama_beasiswa }}
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                {{ Str::limit($ba->deskripsi, 120, '...') }}
                            </p>

                            <!-- Footer -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ $ba->sumber_logo ? $ba->sumber_logo : 'https://ui-avatars.com/api/?name=' . urlencode($ba->sumber) . '&background=6366f1&color=fff&size=32' }}"
                                         alt="{{ $ba->sumber }}"
                                         class="w-6 h-6 rounded-full object-cover">
                                    <span class="text-xs font-medium text-gray-700">{{ $ba->sumber }}</span>
                                </div>

                                <!-- CHANGED: Always show button, but change text based on status -->
                                <button class="inline-flex items-center hover:text-blue-700 text-sm font-medium group
                                    {{ $canApply ? 'text-blue-600' : 'text-gray-600' }}">
                                    {{ $canApply ? 'Lihat Detail' : 'Lihat Info' }}
                                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Deadline Info -->
                            @if($status === 'berlangsung')
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Berakhir: {{ \Carbon\Carbon::parse($ba->tanggal_berakhir)->format('d M Y') }}
                                    </div>
                                </div>
                            @elseif($status === 'berakhir' || $status === 'closed')
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-xs text-amber-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pendaftaran tidak tersedia
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada beasiswa ditemukan</h3>
                <p class="text-gray-500 mb-6">Coba ubah kata kunci pencarian atau filter yang digunakan</p>
                <button onclick="resetFilters()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Modal -->
    <div id="popup" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-white-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="hidePopup()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900" id="modal-title">
                            Filter Beasiswa
                        </h3>
                        <button onclick="hidePopup()" class="rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-2 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form action="{{ route('beasiswa.index') }}" method="GET" class="px-6 pb-6">
                    <input type="hidden" name="search" value="{{ request('search') }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jenis Beasiswa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Jenis Beasiswa
                            </label>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="jenis_beasiswa[]" value="full" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Full Scholarship</span>
                                </label>
                                <br>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="jenis_beasiswa[]" value="half" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Partial Scholarship</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tipe Beasiswa -->
                        <div>
                            <label for="tipe_beasiswa" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Program
                            </label>
                            <select name="tipe_beasiswa" id="tipe_beasiswa" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Tipe</option>
                                <option value="kipk">KIP Kuliah</option>
                                <option value="eksternal">Eksternal</option>
                                <option value="internal">Internal</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select name="status" id="status_filter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Status</option>
                                <option value="berlangsung">Berlangsung</option>
                                <option value="segera">Segera Dibuka</option>
                                <option value="berakhir">Berakhir</option>
                            </select>
                        </div>

                        <!-- Kuota -->
                        <div>
                            <label for="min_kuota" class="block text-sm font-medium text-gray-700 mb-2">
                                Minimum Kuota
                            </label>
                            <input type="number" name="min_kuota" id="min_kuota" placeholder="Contoh: 10" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-between gap-4 mt-8">
                        <button type="button" onclick="resetModalFilters()" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors font-medium">
                            Reset
                        </button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors font-medium">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .filter-btn {
            @apply px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors;
        }

        .filter-btn.active {
            @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700;
        }

        .beasiswa-card.hidden {
            display: none !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterScholarships();
        });

        // Quick filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterScholarships();
            });
        });

        function filterScholarships() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
            const cards = document.querySelectorAll('.beasiswa-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const namaBeasiswa = card.getAttribute('data-nama-beasiswa');
                const status = card.getAttribute('data-status');
                const type = card.getAttribute('data-type');

                let matchesSearch = !searchTerm || namaBeasiswa.includes(searchTerm);
                let matchesFilter = activeFilter === 'all' ||
                                   status === activeFilter ||
                                   type === activeFilter;

                if (matchesSearch && matchesFilter) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            // Toggle empty state
            document.getElementById('emptyState').classList.toggle('hidden', visibleCount > 0);
        }

        // Modal functions
        function showPopup() {
            document.getElementById('popup').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hidePopup() {
            document.getElementById('popup').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            document.querySelector('[data-filter="all"]').classList.add('active');
            filterScholarships();
        }

        function resetModalFilters() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            document.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
            document.querySelectorAll('input[type="number"]').forEach(inp => inp.value = '');
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hidePopup();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            filterScholarships();
        });
    </script>
@endsection
