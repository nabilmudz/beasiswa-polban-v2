
@extends('layouts.main')
@section('content')
    @include('component.navbar', [
        'path' => 'List Beasiswa',
        'id' => null
    ])
    <div class="p-3 px-8 mt-10">
        <div class="flex flex-col lg:flex-row">
            <div class="flex flex-col gap-3 mt-16 lg:basis-1/2">
                <p class="text-2xl lg:text-3xl font-bold text-black">Pengumuman {{ $beasiswa->nama_beasiswa }}</p>
                <p class="text-xs lg:text-sm font-normal text-gray-500">
                    Berikut merupakan daftar mahasiswa penerima {{ $beasiswa->nama_beasiswa }}. Selamat dan
                    semangat untuk seluruh mahasiswa!
                </p>
                @if(isset($reviewer->role_id) && $reviewer->role_id == 1)
                <div class="flex flex-col md:flex-row gap-2 mt-5">
                    <form action="{{ route('beasiswa.export-data-beasiswa', ['id' => request()->route('id')]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="text-center bg-orange-500 rounded-lg shadow-lg p-2 basis-1/2">
                            <p class="text-white">Download Excel</p>
                        </button>
                    </form>

                </div>
                @endif
            </div>
            <div class="flex justify-center lg:basis-1/2 mt-5 lg:mt-0">
                <img src="{{ asset('assets/img/penerima.png') }}" class="w-full max-w-sm lg:max-h-80 rounded-lg"
                    alt="beasiswa">
            </div>
        </div>
        <div class="flex space-x-4 items-center justify-center mt-10">
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="px-2 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="font-medium">Penerima Beasiswa</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Prodi</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penerima_beasiswa as $pb)
                                        <tr>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <div>
                                                        <img src="../assets/img/profile.jpg"
                                                            class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-soft-in-out h-9 w-9 rounded-xl"
                                                            alt="user1" />
                                                    </div>
                                                    <div class="flex flex-col justify-center">
                                                        <h5 class="mb-0 text-sm leading-normal"> {{ isset($pb->nama_depan) ? $pb->nama_depan :  $pb->nama_mahasiswa }}
                                                            {{ isset($pb->nama_belakang) ? $pb->nama_belakang : null }}</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-xs font-semibold leading-tight"> {{ $pb->nama_prodi }}
                                                </p>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
