@extends('layouts.main')
@section('content')

@include('component.navbar', [
    'path' => "Madding",
    'id' => null
])

<div class="madding-wrapper mx-auto py-6 sm:px-6 lg:px-10 max-[799px]:px-10">
    <div class="madding-1">
        <div class="madding-header pb-4 flex flex-wrap items-start justify-between">
            <div class="w-full md:w-auto">
                <h1 class="text-2xl font-bold">Madding Beasiswa</h1>
                <p>Tempat kamu mendapatkan info terbaru mengenai beasiswa</p>
            </div>
            <div class="w-full max-[799px]:w-full md:w-auto max-[799px]:mt-4 max-[799px]:mb-2">
                <a href="{{ route('beasiswa.index') }}"
                   type="button"
                   class="block w-full px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Lihat Lebih Banyak
                </a>
            </div>
        </div>
        <div class="madding-content">
            <div class="madding-content-1">
                <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center"
                        id="default-styled-tab"
                        data-tabs-toggle="#default-styled-tab-content"
                        data-tabs-active-classes="text-black-600 bg-yellow-300 hover:text-black-600 dark:text-black-500 dark:hover:text-yellow-500 border-yellow-600 dark:border-yellow-500"
                        data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-black-600 dark:text-gray-400 border-black-100 hover:border-black-300 dark:border-black-700 dark:hover:text-gray-300"
                        role="tablist">
                        <li class="w-full sm:w-auto flex-1 max-[799px]:w-1/2" role="presentation">
                            <button
                                class="w-full p-4 border-b-2 rounded-t-lg"
                                id="newest-styled-tab"
                                data-tabs-target="#styled-newest"
                                type="button"
                                role="tab"
                                aria-controls="newest"
                                aria-selected="false">
                                Terbaru
                            </button>
                        </li>
                        <li class="w-full sm:w-auto flex-1 max-[799px]:w-1/2" role="presentation">
                            <button
                                class="w-full p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                id="upcoming-styled-tab"
                                data-tabs-target="#styled-upcoming"
                                type="button"
                                role="tab"
                                aria-controls="upcoming"
                                aria-selected="false">
                                Upcoming
                            </button>
                        </li>
                    </ul>
                </div>
                <div id="default-styled-tab-content">
                    <div class="hidden p-4 rounded-lg bg-gray-100 rounded-xl grid grid-rows-[auto 1fr 1fr 1fr] grid-cols-4 max-[440px]:grid-cols-1 max-[440px]:gap-6 gap-4 shadow-lg" id="styled-newest" role="tabpanel" aria-labelledby="newest-tab">
                        @php
                            $totalCards = 7;
                            $comingSoonDisplayed = false;
                        @endphp

                        @if ($newestBeasiswa->isEmpty())
                            <div class="flex items-center justify-center h-full col-span-1">
                                <p class="text-lg text-gray-700">
                                    Oops! Sepertinya belum ada beasiswa terbaru nih, mungkin kamu bisa lihat ke
                                    <span class="font-semibold">beasiswa yang akan datang (upcoming)</span>
                                </p>
                            </div>
                        @else
                            @for ($index = 0; $index < $totalCards; $index++)
                                @if ($index < $newestBeasiswa->count())
                                    @php
                                        $beasiswa = $newestBeasiswa[$index];
                                    @endphp

                                    @if ($index === 0)
                                        <div class="row-span-2 col-span-2 max-[440px]:col-span-1 max-[440px]:h-auto max-[1280px]:col-span-2 flex flex-col rounded-2xl h-full">
                                            <div class="flex flex-col bg-white border-2 border-gray-600 rounded-lg shadow flex-1">
                                                <div class="flex flex-col md:flex-row items-stretch rounded-lg hover:bg-[#fffdf4] flex-1">
                                                    <img class="w-full md:w-1/2 object-cover rounded-t-lg md:rounded-none md:rounded-l-lg max-[440px]:hidden max-[1280px]:hidden"
                                                        src="{{ $beasiswa->link_poster }}"
                                                        alt="Poster Beasiswa">

                                                    <div class="flex flex-col justify-between p-4 md:p-6 leading-normal">
                                                        <div class="mb-4">
                                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $beasiswa->tipe_beasiswa }}</span>
                                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ $beasiswa->jenis_beasiswa }}</span>
                                                        </div>

                                                        <h5 class="mb-4 text-xl md:text-2xl font-bold tracking-tight text-gray-900">
                                                            {{ $beasiswa->nama_beasiswa }}
                                                        </h5>

                                                        <div class="mb-4">
                                                            <span class="bg-yellow-500 text-gray-900 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 border border-yellow-500">
                                                                <svg class="w-3 h-3 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                                                                </svg>
                                                                {{ $beasiswa->tanggal_mulai }} - {{ $beasiswa->tanggal_berakhir }}
                                                            </span>
                                                        </div>

                                                        <p class="mb-4 text-sm font-normal text-gray-900">
                                                            {{ $beasiswa->deskripsi }}
                                                        </p>

                                                        @if ($beasiswa->tipe_beasiswa === "kipk")
                                                            <a href="{{ url('detail-beasiswa-kipk/'. $beasiswa->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 mt-auto">
                                                                Lihat Selengkapnya
                                                            </a>
                                                        @elseif($beasiswa->tipe_beasiswa === "eksternal")
                                                            <a href="{{ url('detail-beasiswa-eksternal/'. $beasiswa->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 mt-auto">
                                                                Lihat Selengkapnya
                                                            </a>
                                                        @else
                                                            <a href="{{ url('beasiswa/'. $beasiswa->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 mt-auto">
                                                                Lihat Selengkapnya
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row-span-2 max-[440px]:col-span-1 max-[440px]:h-auto max-[1280px]:col-span-2 flex flex-col rounded-2xl h-full">
                                            <div class="flex-1 bg-white border-2 border-gray-600 rounded-lg shadow flex flex-col hover:bg-[#fffdf4] h-full">
                                                <div class="rounded-t-lg overflow-hidden max-[799px]:hidden">
                                                    <img class="h-48 w-full object-cover max-[440px]:hidden max-[1280px]:hidden"
                                                        src="{{ $beasiswa->link_poster }}"
                                                        alt="Poster Beasiswa">
                                                </div>
                                                <div class="p-5 flex-1 flex flex-col justify-between">
                                                    <div class="flex flex-wrap gap-2 mb-4">
                                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $beasiswa->tipe_beasiswa }}</span>
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ $beasiswa->jenis_beasiswa }}</span>
                                                    </div>
                                                    <h5 class="mb-4 text-xl font-bold tracking-tight text-gray-900">
                                                        {{ $beasiswa->nama_beasiswa }}
                                                    </h5>
                                                    <div>
                                                        <span class="bg-yellow-500 text-gray-900 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 border border-yellow-500">
                                                            <svg class="w-3 h-3 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                                                            </svg>
                                                            {{ $beasiswa->tanggal_mulai }} - {{ $beasiswa->tanggal_berakhir }}
                                                        </span>
                                                    </div>
                                                    <p class="flex-grow text-sm font-normal text-gray-900 mt-4">
                                                        {{ $beasiswa->deskripsi}}
                                                    </p>
                                                    <a href="{{ url('beasiswa/'. $beasiswa->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 mt-4">
                                                        Lihat Selengkapnya
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    @if (!$comingSoonDisplayed)
                                        <div class="row-span-2 max-[440px]:col-span-1 max-[1280px]:col-span-2 bg-gray-400 flex flex-col rounded-2xl justify-center items-center">
                                            <p class="text-lg font-bold text-white">Coming Soon</p>
                                        </div>
                                        @php
                                            $comingSoonDisplayed = true;
                                        @endphp
                                    @endif
                                @endif
                            @endfor
                        @endif
                    </div>
                    <div class="hidden p-4 rounded-lg bg-gray-100 rounded-xl grid grid-rows-[auto 1fr 1fr 1fr] grid-cols-4 max-[440px]:grid-cols-1 max-[440px]:gap-6 gap-4 shadow-lg" id="styled-upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                        @php
                            $totalCards = 7;
                            $comingSoonDisplayed = false;
                        @endphp

                        @if ($upcomingBeasiswa->isEmpty())
                            <div class="flex items-center justify-center h-full col-span-1">
                                <p class="text-lg text-gray-700">Sepertinya belum ada beasiswa yang akan datang, Mungkin tertarik dengan <span class="font-semibold">beasiswa terbaru?</span></p>
                            </div>
                        @else
                            @for ($index = 0; $index < $totalCards; $index++)
                                @if ($index < $upcomingBeasiswa->count())
                                    @php
                                        $beasiswa = $upcomingBeasiswa[$index];
                                    @endphp

                                    @if ($index === 0)
                                        <div class="row-span-2 col-span-2 max-[440px]:col-span-1 max-[440px]:h-auto max-[1280px]:col-span-2 flex flex-col rounded-2xl h-full">
                                            <div class="flex flex-col bg-white border-2 border-gray-600 rounded-lg shadow flex-1">
                                                <div class="flex flex-col md:flex-row items-stretch rounded-lg hover:bg-[#fffdf4] flex-1">
                                                    <img class="w-full md:w-1/2 object-cover rounded-t-lg md:rounded-none md:rounded-l-lg max-[440px]:hidden max-[1280px]:hidden"
                                                        src="{{ $beasiswa->link_poster }}"
                                                        alt="Poster Beasiswa">

                                                    <div class="flex flex-col justify-between p-4 md:p-6 leading-normal">
                                                        <div class="mb-4">
                                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $beasiswa->tipe_beasiswa }}</span>
                                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ $beasiswa->jenis_beasiswa }}</span>
                                                        </div>

                                                        <h5 class="mb-4 text-xl md:text-2xl font-bold tracking-tight text-gray-900">
                                                            {{ $beasiswa->nama_beasiswa }}
                                                        </h5>

                                                        <div class="mb-4">
                                                            <span class="bg-yellow-500 text-gray-900 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 border border-yellow-500">
                                                                <svg class="w-3 h-3 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                                                                </svg>
                                                                {{ $beasiswa->tanggal_mulai }} - {{ $beasiswa->tanggal_berakhir }}
                                                            </span>
                                                        </div>

                                                        <p class="mb-4 text-sm font-normal text-gray-900">
                                                            {{ $beasiswa->short_description }}
                                                        </p>

                                                        @if ($beasiswa->tipe_beasiswa === "kipk")
                                                            <a href="{{ url('detail-beasiswa-kipk/'. $beasiswa->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 mt-auto">
                                                                Lihat Selengkapnya
                                                            </a>
                                                        @elseif($beasiswa->tipe_beasiswa === "eksternal")
                                                            <a href="{{ url('detail-beasiswa-eksternal/'. $beasiswa->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 mt-auto">
                                                                Lihat Selengkapnya
                                                            </a>
                                                        @else
                                                            <a href="{{ url('beasiswa/'. $beasiswa->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 mt-auto">
                                                                Lihat Selengkapnya
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    <div class="row-span-2 max-[440px]:col-span-1 max-[440px]:h-auto max-[1280px]:col-span-2 flex flex-col rounded-2xl h-full">
                                        <div class="flex-1 bg-white border-2 border-gray-600 rounded-lg shadow flex flex-col hover:bg-[#fffdf4] h-full">
                                            <div class="rounded-t-lg overflow-hidden max-[799px]:hidden">
                                                <img class="h-48 w-full object-cover max-[440px]:hidden max-[1280px]:hidden" src="{{ $beasiswa->link_poster }}" alt="Poster Beasiswa">
                                            </div>
                                            <div class="p-5 flex-1 flex flex-col justify-between">
                                                <div class="flex flex-wrap gap-2 mb-4">
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $beasiswa->tipe_beasiswa }}</span>
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ $beasiswa->jenis_beasiswa }}</span>
                                                </div>
                                                <h5 class="mb-4 text-xl font-bold tracking-tight text-gray-900">
                                                    {{ $beasiswa->nama_beasiswa }}
                                                </h5>
                                                <div>
                                                    <span class="bg-yellow-500 text-gray-900 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 border border-yellow-500">
                                                        <svg class="w-3 h-3 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                                                        </svg>
                                                        {{ $beasiswa->tanggal_mulai }} - {{ $beasiswa->tanggal_berakhir }}
                                                    </span>
                                                </div>
                                                <p class="flex-grow text-sm font-normal text-gray-900 mt-4">
                                                    {{ $beasiswa->short_description }}
                                                </p>
                                                <a href="{{ url('beasiswa/'. $beasiswa->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-700 mt-4">
                                                    Lihat Selengkapnya
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @else
                                    @if (!$comingSoonDisplayed)
                                    <div class="row-span-2 max-[440px]:col-span-1 max-[1280px]:col-span-2 bg-gray-400 flex flex-col rounded-2xl justify-center items-center">
                                        <p class="text-lg font-bold text-white">Coming Soon</p>
                                    </div>
                                    @php
                                        $comingSoonDisplayed = true;
                                    @endphp
                                    @endif
                                    @endif
                            @endfor
                        @endif
                    </div>
                </div>
            </div>
            <div class="madding-content-2"></div>
        </div>
    </div>

    <div class="madding-2 mt-[3em]">
        <div class="madding-header pb-4 flex flex-wrap items-start justify-between">
            <div class="w-full md:w-auto">
                <h1 class="class="text-2xl font-bold"">
                    Mereka Bisa, Kamu Juga Bisa!
                </h1>
                <p>
                    Beberapa mahasiswa yang berhasil mendapatkan beasiswa bulan ini
                </p>
            </div>
            <div class="w-full max-[799px]:w-full md:w-auto max-[799px]:mt-4 max-[799px]:mb-2">
                <a href="{{ route('pengumuman-beasiswa.index') }}"
                   type="button"
                   class="block w-full px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Pengumuman Lebih Lengkap
                </a>
            </div>
        </div>

        <div class="madding-content grid grid-cols-4 gap-4 max-[1279px]:grid-cols-3 max-[799px]:grid-cols-1 max-[768px]:grid-cols-auto-fit max-[768px]:gap-4"
             style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            @forelse ($mahasiswaAccepted as $penerima)
                <div class="w-full bg-white border-2 border-gray-900 rounded-lg shadow-xl hover:bg-gray-100 max-[768px]:p-6">
                    <div class="flex flex-col items-center p-10 max-[768px]:p-6">
                        <img class="w-24 h-24 mb-3 rounded-full shadow-lg mb-5 max-[768px]:w-20 max-[768px]:h-20"
                             src="{{ $penerima->foto }}"
                             alt="Foto User"/>

                        <h5 class="mb-1 text-xl font-medium text-gray-900 text-center max-[1279px]:text-lg max-[799px]:text-base max-[768px]:text-sm">
                            {{ $penerima->nama_depan }} {{ $penerima->nama_belakang }}
                        </h5>

                        <span class="text-sm text-gray-500 max-[1279px]:text-xs max-[799px]:text-xs max-[768px]:text-[10px]">
                            {{ $penerima->nama_prodi }} @ {{ $penerima->angkatan }}
                        </span>

                        <div class="flex mt-2 md:mt-4 flex flex-col gap-1 justify-center items-center text-center">
                            <p class="text-gray-900 max-[1279px]:text-sm max-[799px]:text-xs max-[768px]:text-[10px]">
                                Penerima Beasiswa
                            </p>
                            <h1 class="text-gray-900 font-bold max-[1279px]:text-base max-[799px]:text-sm max-[768px]:text-xs">
                                {{ $penerima->nama_beasiswa }}
                            </h1>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex items-center justify-center h-full">
                    <p class="text-xl text-gray-700 max-[1279px]:text-lg max-[799px]:text-sm max-[768px]:text-xs">
                        Ayo daftar beasiswa, Kalo diterima, Kamu bakal muncul disini!
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination Section -->
        <div class="py-5">
            {{ $mahasiswaAccepted->links() }}
        </div>
    </div>
</div>

@endsection
