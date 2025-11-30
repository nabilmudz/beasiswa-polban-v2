@extends('layouts.main')

@section('content')
    @include('component.navbar', [
        'path' => 'Beasiswa KIPK',
        'id' => null
    ])

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">

        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-white">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-indigo-600/5"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                    <!-- Image Section -->
                    <div class="flex justify-center lg:justify-start">
                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur-2xl opacity-20"></div>
                            <img src="{{ asset('assets/img/kipk.png') }}"
                                 class="relative w-full max-w-md h-auto rounded-2xl shadow-2xl border border-white/20"
                                 alt="KIP-Kuliah Logo">
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="text-center lg:text-left space-y-8">
                        <div class="space-y-4">
                            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                🎓 Program Pemerintah Indonesia
                            </div>
                            <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 leading-tight">
                                <span class="text-blue-600">KIP</span>-KULIAH
                            </h1>
                            <p class="text-xl lg:text-2xl text-gray-600 font-medium">
                                Kartu Indonesia Pintar Kuliah Merdeka 2024
                            </p>
                        </div>

                        <!-- Status and CTA -->
                        <div class="space-y-6">
                            @php
                                $status = $beasiswa->tanggal_mulai <= now() && $beasiswa->tanggal_berakhir >= now()
                                    ? 'Berlangsung'
                                    : ($beasiswa->tanggal_mulai > now() ? 'Upcoming' : 'Past');
                            @endphp

                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                @if ($status === 'Berlangsung')
                                    <a href="https://kip-kuliah.kemdikbud.go.id/"
                                       target="_blank"
                                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                        <span>Daftar KIP-Kuliah</span>
                                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif

                                <button class="inline-flex items-center justify-center px-8 py-4 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold rounded-xl transition-all duration-300">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Download Panduan</span>
                                </button>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex justify-center lg:justify-start">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                    @if($status === 'Berlangsung') bg-green-100 text-green-800 border border-green-200
                                    @elseif($status === 'Upcoming') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @else bg-gray-100 text-gray-800 border border-gray-200
                                    @endif">
                                    <div class="w-2 h-2 rounded-full mr-2
                                        @if($status === 'Berlangsung') bg-green-500
                                        @elseif($status === 'Upcoming') bg-yellow-500
                                        @else bg-gray-500
                                        @endif"></div>
                                    {{ $status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            <!-- Program Overview -->
            <div class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Tentang <span class="text-blue-600">KIP-Kuliah</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Description -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                            <div class="space-y-6 text-gray-700 leading-relaxed">
                                <p class="text-lg">
                                    Pemerintah Indonesia terus berkomitmen untuk fokus meningkatkan pembangunan Sumber Daya Manusia melalui berbagai upaya cerdas. <strong class="text-blue-600">Kartu Indonesia Pintar Kuliah (KIP-Kuliah)</strong> adalah salah satu upaya untuk membantu para siswa yang memiliki keterbatasan ekonomi tetapi berprestasi untuk melanjutkan studi di perguruan tinggi.
                                </p>

                                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-xl">
                                    <h4 class="font-semibold text-blue-900 mb-2">KIP-Kuliah Merdeka 2024</h4>
                                    <p>
                                        KIP Kuliah Merdeka Tahun 2024 sudah dibuka! Ayo manfaatkan program KIP Kuliah Merdeka untuk meraih cita-citamu. Pendaftaran KIP Kuliah Merdeka memerlukan data Nomor Induk Siswa Nasional (NISN), Nomor Pokok Sekolah Nasional (NPSN), dan Nomor Induk Kependudukan (NIK).
                                    </p>
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-xl">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-6 h-6 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-yellow-800 mb-1">Penting!</h4>
                                            <p class="text-yellow-700">
                                                Pastikan NISN, NPSN dan NIK dari calon peserta KIP Kuliah 2024 valid, sesuai data yang tercatat di Data Pokok Pendidikan (Dapodik), Kemendikbudristek.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info Sidebar -->
                    <div class="space-y-6">
                        <!-- Timeline Info -->
                        <div class="bg-gradient-to-br from-orange-600 to-yellow-600 rounded-2xl p-6 text-white">
                            <h3 class="font-bold text-white text-xl mb-4">📅 Timeline Pendaftaran</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                    <div>
                                        <p class="text-sm opacity-90">Mulai Pendaftaran</p>
                                        <p class="font-semibold">{{ \Carbon\Carbon::parse($beasiswa->tanggal_mulai)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                    <div>
                                        <p class="text-sm opacity-90">Batas Akhir</p>
                                        <p class="font-semibold">{{ \Carbon\Carbon::parse($beasiswa->tanggal_berakhir)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                            <h3 class="font-bold text-lg text-gray-900 mb-4">📋 Persyaratan Utama</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-gray-700">NISN Valid</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-gray-700">NPSN Valid</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-gray-700">NIK Valid</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-gray-700">Keterbatasan Ekonomi</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-gray-700">Prestasi Akademik</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="bg-gray-50 rounded-2xl p-6 border">
                            <h3 class="font-bold text-lg text-gray-900 mb-4">💬 Butuh Bantuan?</h3>
                            <div class="space-y-3">
                                <a href="https://kip-kuliah.kemdikbud.go.id/"
                                   target="_blank"
                                   class="flex items-center space-x-2 text-blue-600 hover:text-blue-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    <span class="text-sm">Website Resmi</span>
                                </a>
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-sm">Call Center: 126</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Highlights -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 text-center transform hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">💰</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Biaya Kuliah</h3>
                    <p class="text-sm text-gray-600">Bantuan biaya kuliah penuh</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 text-center transform hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🏠</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Biaya Hidup</h3>
                    <p class="text-sm text-gray-600">Tunjangan biaya hidup bulanan</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 text-center transform hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📚</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Buku & Alat</h3>
                    <p class="text-sm text-gray-600">Bantuan buku dan alat tulis</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 text-center transform hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Pendampingan</h3>
                    <p class="text-sm text-gray-600">Bimbingan akademik dan karir</p>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Custom animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Custom gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #3B82F6 0%, #6366F1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    <script>
        // Add smooth animations on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.bg-white, .bg-gradient-to-br').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
@endsection
