@extends('layouts.main')

@section('title', 'Madding Beasiswa - Portal Beasiswa Terpercaya')

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in {
        animation: slideIn 1s ease-out;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-50px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .stats-counter {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="hero-gradient text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="animate-slide-in">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        Wujudkan
                        <span class="text-yellow-300">Impian</span>
                        Pendidikanmu
                    </h1>
                    <p class="text-xl mb-8 text-gray-200 leading-relaxed">
                        Temukan ribuan kesempatan beasiswa dari berbagai institusi terpercaya.
                        Mulai perjalanan pendidikan yang lebih baik bersama kami.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('beasiswa.index') }}"
                           class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 text-center shadow-lg hover:shadow-xl">
                            Jelajahi Beasiswa
                        </a>
                        <a href="#features"
                           class="border-2 border-white hover:bg-white hover:text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 text-center">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>

                <!-- Right Content - Hero Image -->
                <div class="animate-fade-in">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                             alt="Students celebrating"
                             class="rounded-2xl shadow-2xl">
                        <div class="absolute -bottom-6 -right-6 bg-yellow-500 text-gray-900 p-6 rounded-2xl shadow-xl">
                            <div class="text-3xl font-bold">{{ $totalBeasiswa ?? '100+' }}</div>
                            <div class="text-sm font-semibold">Beasiswa Tersedia</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center stats-counter rounded-2xl p-6">
                <div class="text-4xl font-bold text-yellow-400 mb-2">{{ $totalMahasiswa ?? '1,000+' }}</div>
                <div class="text-gray-300">Mahasiswa Terdaftar</div>
            </div>
            <div class="text-center stats-counter rounded-2xl p-6">
                <div class="text-4xl font-bold text-yellow-400 mb-2">{{ $totalPenerima ?? '500+' }}</div>
                <div class="text-gray-300">Penerima Beasiswa</div>
            </div>
            <div class="text-center stats-counter rounded-2xl p-6">
                <div class="text-4xl font-bold text-yellow-400 mb-2">{{ $totalUniversitas ?? '50+' }}</div>
                <div class="text-gray-300">Universitas Partner</div>
            </div>
            <div class="text-center stats-counter rounded-2xl p-6">
                <div class="text-4xl font-bold text-yellow-400 mb-2">95%</div>
                <div class="text-gray-300">Tingkat Kepuasan</div>
            </div>
        </div>
    </div>
</section>

<!-- Beasiswa Terbaru Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Beasiswa Terbaru</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Jangan sampai terlewat! Temukan beasiswa terbaru yang sesuai dengan kebutuhanmu
                </p>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex justify-center mb-10">
                <div class="bg-white rounded-lg p-2 shadow-lg">
                    <button onclick="showTab('newest')"
                            id="tab-newest"
                            class="px-6 py-3 rounded-lg font-semibold transition-all duration-300 bg-yellow-500 text-gray-900">
                        Terbaru
                    </button>
                    <button onclick="showTab('upcoming')"
                            id="tab-upcoming"
                            class="px-6 py-3 rounded-lg font-semibold transition-all duration-300 text-gray-600 hover:text-gray-900">
                        Upcoming
                    </button>
                </div>
            </div>

            <!-- Newest Tab Content -->
            <div id="content-newest" class="tab-content">
                @if($newestBeasiswa && $newestBeasiswa->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($newestBeasiswa->take(6) as $beasiswa)
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                                <div class="relative">
                                    <img src="{{ $beasiswa->link_poster ?? 'https://images.unsplash.com/photo-1607013251379-e6eecfffe234?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' }}"
                                         alt="{{ $beasiswa->nama_beasiswa }}"
                                         class="w-full h-48 object-cover">
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-{{ $beasiswa->tipe_beasiswa === 'kipk' ? 'blue' : 'green' }}-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ ucfirst($beasiswa->tipe_beasiswa) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="mb-4">
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                            {{ $beasiswa->jenis_beasiswa }}
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                                        {{ $beasiswa->nama_beasiswa }}
                                    </h3>
                                    <div class="flex items-center text-gray-600 mb-4">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        <span class="text-sm">
                                            {{ \Carbon\Carbon::parse($beasiswa->tanggal_mulai)->format('d M Y') }} -
                                            {{ \Carbon\Carbon::parse($beasiswa->tanggal_berakhir)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 text-sm mb-6 line-clamp-3">
                                        {{ $beasiswa->short_description }}
                                    </p>
                                    {{-- @if($beasiswa->tipe_beasiswa === 'kipk')
                                        <a href="{{ route('beasiswa.kipk.detail', $beasiswa->id) }}"
                                           class="block w-full bg-yellow-500 hover:bg-yellow-600 text-center text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                                            Lihat Detail
                                        </a>
                                    @elseif($beasiswa->tipe_beasiswa === 'eksternal')
                                        <a href="{{ route('beasiswa.eksternal.detail', $beasiswa->id) }}"
                                           class="block w-full bg-yellow-500 hover:bg-yellow-600 text-center text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                                            Lihat Detail
                                        </a>
                                    @else
                                        <a href="{{ route('beasiswa.detail', $beasiswa->id) }}"
                                           class="block w-full bg-yellow-500 hover:bg-yellow-600 text-center text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                                            Lihat Detail
                                        </a>
                                    @endif --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-600 mb-2">Belum Ada Beasiswa Terbaru</h3>
                        <p class="text-gray-500 mb-6">Pantau terus halaman ini untuk mendapatkan update beasiswa terbaru</p>
                        <a href="{{ route('beasiswa.index') }}"
                           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                            Jelajahi Semua Beasiswa
                        </a>
                    </div>
                @endif
            </div>

            <!-- Upcoming Tab Content -->
            <div id="content-upcoming" class="tab-content hidden">
                @if($upcomingBeasiswa && $upcomingBeasiswa->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($upcomingBeasiswa->take(6) as $beasiswa)
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                                <div class="relative">
                                    <img src="{{ $beasiswa->link_poster ?? 'https://images.unsplash.com/photo-1607013251379-e6eecfffe234?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' }}"
                                         alt="{{ $beasiswa->nama_beasiswa }}"
                                         class="w-full h-48 object-cover">
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            Segera Dibuka
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="mb-4">
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                            {{ $beasiswa->jenis_beasiswa }}
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                                        {{ $beasiswa->nama_beasiswa }}
                                    </h3>
                                    <div class="flex items-center text-gray-600 mb-4">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span class="text-sm">
                                            Dibuka: {{ \Carbon\Carbon::parse($beasiswa->tanggal_mulai)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 text-sm mb-6 line-clamp-3">
                                        {{ $beasiswa->short_description }}
                                    </p>
                                    <button class="block w-full bg-gray-300 text-gray-600 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                                        Coming Soon
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <i class="fas fa-clock text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-600 mb-2">Belum Ada Beasiswa Upcoming</h3>
                        <p class="text-gray-500 mb-6">Periksa tab "Terbaru" untuk melihat beasiswa yang sedang tersedia</p>
                    </div>
                @endif
            </div>

            <!-- View More Button -->
            <div class="text-center mt-12">
                <a href="{{ route('beasiswa.index') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 shadow-lg hover:shadow-xl">
                    Lihat Lebih Banyak Beasiswa
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Mereka Bisa, Kamu Juga Bisa!</h2>
                    <p class="text-xl text-gray-600">
                        Beberapa mahasiswa yang berhasil mendapatkan beasiswa bulan ini
                    </p>
                </div>
                <div class="mt-6 md:mt-0">
                    <a href="{{ route('pengumuman-beasiswa.index') }}"
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                        Lihat Pengumuman Lengkap
                    </a>
                </div>
            </div>

            <!-- Success Stories Cards -->
            @if($mahasiswaAccepted && $mahasiswaAccepted->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($mahasiswaAccepted->take(4) as $penerima)
                        <div class="bg-white border-2 border-gray-200 rounded-2xl shadow-lg hover:shadow-xl card-hover p-6 text-center">
                            <div class="relative mb-6">
                                <img src="{{ $penerima->foto ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' }}"
                                     alt="{{ $penerima->nama_depan }}"
                                     class="w-20 h-20 mx-auto rounded-full object-cover shadow-lg">
                                <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-2">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-1">
                                {{ $penerima->nama_depan }} {{ $penerima->nama_belakang }}
                            </h3>

                            <p class="text-sm text-gray-600 mb-4">
                                {{ $penerima->nama_prodi }} • Angkatan {{ $penerima->angkatan }}
                            </p>

                            <div class="bg-yellow-50 rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-1">Penerima Beasiswa</p>
                                <p class="font-semibold text-yellow-700 text-sm">
                                    {{ $penerima->nama_beasiswa }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 rounded-2xl">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-600 mb-2">Jadilah Yang Pertama!</h3>
                    <p class="text-gray-500 mb-6">Daftar beasiswa sekarang dan mungkin kamu akan muncul di sini!</p>
                    <a href="{{ route('beasiswa.index') }}"
                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                        Mulai Daftar Beasiswa
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Mengapa Memilih Madding Beasiswa?</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Platform terpercaya dengan fitur lengkap untuk membantu perjalanan beasiswa Anda
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg card-hover text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pencarian Mudah</h3>
                    <p class="text-gray-600">
                        Temukan beasiswa yang sesuai dengan kriteria dan kebutuhanmu dengan sistem pencarian yang canggih
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg card-hover text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Terpercaya</h3>
                    <p class="text-gray-600">
                        Semua beasiswa yang tersedia telah diverifikasi dan berasal dari institusi terpercaya
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg card-hover text-center">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Update Terkini</h3>
                    <p class="text-gray-600">
                        Dapatkan informasi beasiswa terbaru dan jangan sampai terlewat deadline penting
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg card-hover text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-graduate text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Panduan Lengkap</h3>
                    <p class="text-gray-600">
                        Dapatkan panduan lengkap mulai dari persiapan dokumen hingga tips sukses mendapatkan beasiswa
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg card-hover text-center">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-headset text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Dukungan 24/7</h3>
                    <p class="text-gray-600">
                        Tim support kami siap membantu menjawab pertanyaan dan memberikan bantuan kapan saja
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg card-hover text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tracking Aplikasi</h3>
                    <p class="text-gray-600">
                        Pantau status aplikasi beasiswa kamu secara real-time dan dapatkan notifikasi penting
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 hero-gradient text-white">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                Siap Memulai Perjalanan Beasiswa Anda?
            </h2>
            <p class="text-xl mb-10 text-gray-200 max-w-2xl mx-auto">
                Ribuan mahasiswa telah merasakan manfaatnya. Sekarang giliran Anda untuk meraih impian pendidikan yang lebih tinggi.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('auth.showregister') }}"
                   class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 shadow-lg hover:shadow-xl">
                    Daftar Sekarang
                </a>
                <a href="{{ route('beasiswa.index') }}"
                   class="border-2 border-white hover:bg-white hover:text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300">
                    Jelajahi Beasiswa
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tab buttons
    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
        tab.classList.remove('bg-yellow-500', 'text-gray-900');
        tab.classList.add('text-gray-600', 'hover:text-gray-900');
    });

    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');

    // Add active class to selected tab button
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('bg-yellow-500', 'text-gray-900');
    activeTab.classList.remove('text-gray-600', 'hover:text-gray-900');
}

// Initialize with newest tab active
document.addEventListener('DOMContentLoaded', function() {
    showTab('newest');
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add scroll animations
window.addEventListener('scroll', function() {
    const elements = document.querySelectorAll('.card-hover');
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;

        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('animate-fade-in');
        }
    });
});
</script>
@endpush
