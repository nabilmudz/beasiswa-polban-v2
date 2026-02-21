@extends('layouts.main')
@section('content')
    @include('component.navbar', ['path' => 'History Penerima', 'id' => null])

    <!-- Main Content -->
    <div class="w-full px-6 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center">
                            <h6 class="font-bold">Data Penerima Beasiswa 2024-2025</h6>
                            <div class="flex gap-2">
                                <a href="{{ route('history-penerima.import-form') }}" 
                                   class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-orange-600 to-orange-400 rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-upload mr-2"></i>Import Data
                                </a>
                                <a href="{{ route('history-penerima.export') }}" 
                                   class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-tl from-green-600 to-green-400 rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-download mr-2"></i>Export Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="mx-6 mt-4 px-4 py-3 mb-4 text-white bg-green-500 rounded-lg">
                            <strong>Berhasil!</strong> {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mx-6 mt-4 px-4 py-3 mb-4 text-white bg-red-500 rounded-lg">
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex-auto px-0 pt-0 pb-2">
                                <!-- Search Form -->
                                <form method="GET" action="{{ route('history-penerima.index') }}" class="flex flex-wrap gap-2 mb-4 px-6">
                                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari NIM, Nama Mahasiswa, Beasiswa, Prodi, Tahun..." class="w-64 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-300 text-xs" />
                                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded text-xs font-semibold hover:bg-orange-600"><i class="fas fa-search mr-1"></i>Cari</button>
                                    @if(request('q'))
                                        <a href="{{ route('history-penerima.index') }}" class="px-3 py-2 text-xs text-gray-500 hover:underline">Reset</a>
                                    @endif
                                </form>
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">NIM</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama Mahasiswa</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Prodi</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama Beasiswa</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tahun</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tanggal Input</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($history as $index => $item)
                                        <tr>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 font-semibold leading-tight text-xs text-slate-400 px-4">{{ $history->firstItem() + $index }}</p>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 font-semibold leading-tight text-xs px-4">{{ $item->nim }}</p>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 font-semibold leading-tight text-xs px-4">{{ $item->nama_mahasiswa ?? '-' }}</p>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 font-semibold leading-tight text-xs px-4">{{ $item->nama_prodi ?? '-' }}</p>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 font-semibold leading-tight text-xs px-4">{{ $item->nama_beasiswa }}</p>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="font-semibold leading-tight text-xs text-slate-400">{{ $item->tahun ?? '-' }}</span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <span class="font-semibold leading-tight text-xs text-slate-400">{{ $item->created_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <form action="{{ route('history-penerima.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus data ini?');"
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-semibold leading-tight text-xs text-red-500 hover:text-red-700">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="p-4 text-center align-middle bg-transparent border-b">
                                                <p class="text-sm text-slate-400">Belum ada data. <a href="{{ route('history-penerima.import-form') }}" class="text-orange-600 hover:underline">Import data sekarang</a></p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4">
                            {{ $history->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
