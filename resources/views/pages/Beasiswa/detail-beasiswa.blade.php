@extends('layouts.main')
@section('content')
    @include('component.navbar', [
        'path' => 'Detail Beasiswa',
        'id' => $beasiswa->nama_beasiswa
    ])

    <div class="">
        <!-- Hero Section -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    <!-- Poster Section -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm border p-6">
                            <div class="swiper swiper-container aspect-[3/4] max-w-sm mx-auto">
                                <div class="swiper-wrapper">
                                    @foreach ($poster as $post)
                                        <div class="swiper-slide">
                                            <img src="{{ $post }}"
                                                 class="w-full h-full object-cover rounded-xl"
                                                 alt="Poster Beasiswa">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination mt-4"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Info Section -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Header -->
                        <div class="space-y-4">
                            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">
                                {{ $beasiswa->nama_beasiswa }}
                            </h1>

                            <!-- Status Tags -->
                            <div class="flex flex-wrap gap-3">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $beasiswa->tipe_beasiswa }}
                                </span>
                                @php
                                    $status = $beasiswa->tanggal_mulai <= now() && $beasiswa->tanggal_berakhir >= now()
                                        ? 'Berlangsung'
                                        : ($beasiswa->tanggal_mulai > now() ? 'Upcoming' : 'Past');
                                    $statusColor = $status === 'Berlangsung' ? 'green' : ($status === 'Upcoming' ? 'yellow' : 'gray');
                                @endphp
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                    @if($status === 'Berlangsung') bg-green-50 text-green-700 border border-green-200
                                    @elseif($status === 'Upcoming') bg-yellow-50 text-yellow-700 border border-yellow-200
                                    @else bg-gray-50 text-gray-700 border border-gray-200
                                    @endif">
                                    {{ $status }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $beasiswa->deskripsi }}</p>
                        </div>

                        <!-- Apply Button -->
                        @if (isset($isMhs) && !$isMengajukan && $status === 'Berlangsung')
                            <div class="pt-2">
                                <a href="{{ route('pengajuan.create',['id'=> $id]) }}"
                                   class="inline-flex items-center justify-center px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <span>Apply Sekarang</span>
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        @elseif (isset($isKajur) && $isKajur && $status === 'Berlangsung')
                            <div class="pt-2">
                                <a href="{{ route('pengajuan.create-kajur',['id'=> $id]) }}"
                                   class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <span>Ajukan untuk Mahasiswa</span>
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

            <!-- YouTube Video Section -->
            @if($beasiswa->youtube_url)
            <section class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="px-8 py-6 border-b bg-gradient-to-r from-red-50 to-pink-50">
                    <h2 class="text-2xl font-bold text-gray-900">Video Penjelasan</h2>
                    <p class="text-gray-600 mt-1">Tonton video untuk informasi lebih lanjut</p>
                </div>
                <div class="p-8">
                    <div class="relative w-full" style="padding-bottom: 56.25%;">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full rounded-xl shadow-lg"
                            src="{{ str_replace(['watch?v=', 'youtu.be/'], ['embed/', 'youtube.com/embed/'], $beasiswa->youtube_url) }}"
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </section>
            @endif

            <!-- Benefits Section -->
            <section class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="px-8 py-6 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h2 class="text-2xl font-bold text-gray-900">Benefits</h2>
                    <p class="text-gray-600 mt-1">Keuntungan yang akan Anda dapatkan</p>
                </div>
                <div class="p-8">
                    @include('component.slider', ['beasiswa' => $beasiswa, 'isBenefit' => true])
                </div>
            </section>

            <!-- Requirements Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Syarat Section -->
                <section class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                    <div class="px-6 py-5 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                        <h2 class="text-xl font-bold text-gray-900">Persyaratan</h2>
                        <p class="text-gray-600 text-sm mt-1">Syarat yang harus dipenuhi</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach ($beasiswa->syaratBeasiswa as $index => $syarat)
                                <div class="flex items-start space-x-3 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                                        <span class="text-xs font-semibold text-green-700">{{ $index + 1 }}</span>
                                    </div>
                                    <p class="text-gray-700 font-medium leading-relaxed">{{ $syarat->syarat }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- Syarat Dokumen Section -->
                <section class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                    <div class="px-6 py-5 border-b bg-gradient-to-r from-purple-50 to-violet-50">
                        <h2 class="text-xl font-bold text-gray-900">Dokumen Persyaratan</h2>
                        <p class="text-gray-600 text-sm mt-1">Dokumen yang perlu disiapkan</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($beasiswa->syaratDokumen as $syarat)
                                <div class="p-5 rounded-xl border border-gray-200 hover:border-purple-200 hover:bg-purple-50/30 transition-all duration-200">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 mb-1">{{ $syarat->dokumen }}</h3>
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $syarat->deskripsi_dokumen }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>

            <!-- Timeline atau Deadline Info (Optional enhancement) -->
            <section class="bg-gradient-to-r from-orange-600 to-yellow-600 rounded-2xl shadow-lg overflow-hidden text-white">
                <div class="px-8 py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                        <div>
                            <h3 class="text-xl text-white font-bold mb-2">Timeline Pendaftaran</h3>
                            <div class="space-y-2 text-blue-100">
                                <p>📅 Mulai: {{ \Carbon\Carbon::parse($beasiswa->tanggal_mulai)->format('d M Y') }}</p>
                                <p>⏰ Berakhir: {{ \Carbon\Carbon::parse($beasiswa->tanggal_berakhir)->format('d M Y') }}</p>
                            </div>
                        </div>
                        @if($status === 'Berlangsung')
                            <div class="mt-4 sm:mt-0 text-center">
                                <div class="bg-white/20 backdrop-blur rounded-xl p-4">
                                    <p class="text-sm font-medium mb-1">Sisa Waktu</p>
                                    <p class="text-2xl font-bold">
                                        {{ ceil(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($beasiswa->tanggal_berakhir))) }} Hari
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Enhanced Swiper Script -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('.swiper-container', {
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            // Smooth scroll animations
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

            document.querySelectorAll('section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Swiper pagination custom styling */
        .swiper-pagination-bullet {
            background: #e2e8f0;
            opacity: 1;
        }

        .swiper-pagination-bullet-active {
            background: #3b82f6;
        }
    </style>
@endsection
