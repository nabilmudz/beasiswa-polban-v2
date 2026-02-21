@extends('layouts.main')
@section('content')
@include('component.navbar', ['path' => 'Tracking Beasiswa', 'id' => $dataPengajuan->nama_beasiswa])

<!-- Header Section -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $dataPengajuan->nim . ' ' }}-{{ ' ' . $dataPengajuan->nama_depan . ' ' . $dataPengajuan->nama_belakang }} - {{ $dataPengajuan->nama_beasiswa }}</h1>
                    <p class="text-gray-600 mt-1">Application Status Tracking</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if($dataPengajuan->status == 8)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1.5"></i>
                            Approved
                        </span>
                    @elseif($dataPengajuan->status == 9)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1.5"></i>
                            Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-clock mr-1.5"></i>
                            In Progress
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Progress Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Application Progress</h2>

                @php
                    $displayStatuses = [1, 2, 4, 6, 8]; // Diajukan, Ketua Jurusan, Staff Kema, WD3, Diterima
                    $filteredStatuses = $dataStatus->filter(function($status) use ($displayStatuses) {
                        return in_array($status->id, $displayStatuses);
                    })->values();
                @endphp

                <div class="relative">
                    @foreach($filteredStatuses as $index => $step)
                            <div class="flex items-start mb-8 last:mb-0 relative">
                                <!-- Timeline dot -->
                                <div class="flex-shrink-0 relative z-10">
                                    @if($dataPengajuan->status == 8)
                                        <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </div>
                                    @elseif($dataPengajuan->status == 9)
                                        <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center">
                                            <i class="fas fa-times text-white text-sm"></i>
                                        </div>
                                    @else
                                        @if($step->id == $dataPengajuan->status || ($dataPengajuan->status == 3 && $step->id == 2) || ($dataPengajuan->status == 5 && $step->id == 4) || ($dataPengajuan->status == 7 && $step->id == 6))
                                            <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center animate-pulse">
                                                <i class="fas fa-clock text-white text-sm"></i>
                                            </div>
                                        @elseif($step->id < $dataPengajuan->status)
                                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-sm"></i>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- Timeline line -->
                                @if($index < $filteredStatuses->count() - 1)
                                    <div class="absolute left-5 top-10 w-0.5 h-16
                                        @if($dataPengajuan->status == 8 || $dataPengajuan->status == 9 || $step->id < $dataPengajuan->status)
                                            bg-green-300
                                        @else
                                            bg-gray-200
                                        @endif">
                                    </div>
                                @endif

                                <!-- Content -->
                                <div class="ml-4 min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $step->isi_status }}</div>
                                    @if($dataPengajuan->status == 9)
                                        <div class="text-xs text-red-600 mt-1 font-medium">Ditolak</div>
                                    @elseif(in_array($dataPengajuan->status, [3, 5, 7]))
                                        @if(($dataPengajuan->status == 3 && $step->id == 2) || ($dataPengajuan->status == 5 && $step->id == 4) || ($dataPengajuan->status == 7 && $step->id == 6))
                                            <div class="text-xs text-yellow-600 mt-1 font-medium">Needs Revision</div>
                                        @elseif($step->id < $dataPengajuan->status)
                                            <div class="text-xs text-green-600 mt-1">Completed</div>
                                        @else
                                            <div class="text-xs text-gray-500 mt-1">Pending</div>
                                        @endif
                                    @elseif($dataPengajuan->status == 8)
                                        <div class="text-xs text-green-600 mt-1">Completed</div>
                                    @else
                                        @if($step->id == $dataPengajuan->status)
                                            <div class="text-xs text-blue-600 mt-1 font-medium">Current Step</div>
                                        @elseif($step->id < $dataPengajuan->status)
                                            <div class="text-xs text-green-600 mt-1">Completed</div>
                                        @else
                                            <div class="text-xs text-gray-500 mt-1">Pending</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>

            <!-- Revision Alert -->
            @if($dataReviewer == null && in_array($dataPengajuan->status, [3, 5, 7]))
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-amber-500 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-amber-800">Revision Required</h3>
                            <p class="text-sm text-amber-700 mt-1">Please check the comments section for revision details.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Documents Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Submitted Documents</h2>
                <div class="space-y-3">
                    @php $docIndex = 0; @endphp
                    @foreach($documents as $document)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <button class="accordion-toggle w-full px-4 py-3 text-left flex items-center justify-between hover:bg-gray-50 transition-colors"
                                    onclick="toggleDocument({{ $loop->index }})">
                                <div class="flex items-center">
                                    @if(isset($dataDokumenPengajuan[$docIndex]))
                                        <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                        <span class="text-sm font-medium text-gray-900">{{ $document->dokumen }}</span>
                                    @else
                                        <i class="fas fa-file-pdf text-gray-400 mr-3"></i>
                                        <span class="text-sm font-medium text-gray-500">{{ $document->dokumen }}</span>
                                        <span class="ml-2 text-xs text-gray-400">(Belum diupload)</span>
                                    @endif
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform document-chevron-{{ $loop->index }}"></i>
                            </button>
                            <div class="document-content-{{ $loop->index }} hidden border-t border-gray-200 bg-gray-50 p-4">
                                @if(isset($dataDokumenPengajuan[$docIndex]))
                                    <div class="rounded-lg overflow-hidden bg-white">
                                        <embed src="{{ $dataDokumenPengajuan[$docIndex]->link_dokumen }}"
                                               width="100%" height="500" type="application/pdf" class="border-0">
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fas fa-file-upload text-4xl mb-2"></i>
                                        <p class="text-sm">Dokumen belum diupload</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @php $docIndex++; @endphp
                    @endforeach
                </div>
            </div>

            <!-- Comments Section -->
            @if($dataPengajuan->komentar && $dataReviewer != null)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Latest Comments</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $dataPengajuan->komentar }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Time Estimation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Time Remaining</h3>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-gray-900" id="days">0</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Days</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-gray-900" id="hours">0</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Hours</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-gray-900" id="minutes">0</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Minutes</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-gray-900" id="seconds">0</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Seconds</div>
                    </div>
                </div>
            </div>

            <!-- Scholarship Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="aspect-w-16 aspect-h-9">
                    <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEik4McHhDC2otgAFVVxX1_9KI4xqY0KLdkThGiFYjsfN720_z_kIvi2TARm24mA68XO1CbMBSILOHFfy0HIQVO9Hn1qXFxSVfTC54ZaoHKLi6Yj-fd6Lm02syaeQ_Q3nkaGu4LpM6JSk-MwEEzzYqjZMbMNDyQiP8InBNz7sFn00DMJXQQBakiNtx8qBw/s1080/Beasiswa-Creativa-Feed.png"
                         alt="Beasiswa" class="w-full h-48 object-cover">
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $dataPengajuan->nama_beasiswa }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $dataPengajuan->deskripsi }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($dataReviewer != null)
                <!-- Reviewer Actions -->
                @if($dataPengajuan->status != 11)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Reviewer Actions</h3>
                        <form action="{{ route('pengajuan.update-progress', $dataPengajuan->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="reviewerComment" class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                                <textarea name="reviewerComment" id="reviewerComment" rows="4"
                                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                         placeholder="Add your comments here..."></textarea>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <button type="submit" name="action" value="approve"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-check mr-2"></i>Approve
                                </button>
                                <button type="submit" name="action" value="revise"
                                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-2.5 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-edit mr-2"></i>Request Revision
                                </button>
                                <button type="submit" name="action" value="reject"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </button>
                            </div>
                            <input type="hidden" name="role_id" value="{{ $dataReviewer->role_id }}">
                            <input type="hidden" name="pengajuan_status" value="{{ $dataPengajuan->status }}">
                        </form>
                    </div>
                @endif
            @else
                <!-- Applicant Actions -->
                @if($dataPengajuan->status <= 1)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        <a href="{{ route('pengajuan.show', $dataPengajuan->id) }}"
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>Edit Application
                        </a>
                        <form action="{{ route('pengajuan.batalkan-pengajuan', $dataPengajuan->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to cancel this application?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Cancel Application
                            </button>
                        </form>
                    </div>
                @elseif(in_array($dataPengajuan->status, [3, 5, 7, 9]))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Revision Required</h3>
                        @if($dataPengajuan->komentar)
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-medium text-amber-800 mb-2">Revision Comments:</h4>
                                <p class="text-sm text-amber-700">{{ $dataPengajuan->komentar }}</p>
                            </div>
                        @endif
                        <a href="{{ url('/pengajuan-beasiswa/edit/' . $dataPengajuan->id) }}"
                           class="w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-2.5 px-4 rounded-lg transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>Fix Application
                        </a>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                        <i class="fas fa-info-circle text-gray-400 text-2xl mb-3"></i>
                        <p class="text-gray-600 text-sm">Application can only be cancelled during "Submitted" status.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<script>
// Countdown Timer
const waktuSisa = {
    days: {{ $waktuSisa['days'] }},
    hours: {{ $waktuSisa['hours'] }},
    minutes: {{ $waktuSisa['minutes'] }},
    seconds: {{ $waktuSisa['seconds'] }}
};

function startCountdown() {
    let { days, hours, minutes, seconds } = waktuSisa;

    const timerInterval = setInterval(() => {
        if (seconds > 0) {
            seconds--;
        } else if (minutes > 0) {
            minutes--;
            seconds = 59;
        } else if (hours > 0) {
            hours--;
            minutes = 59;
            seconds = 59;
        } else if (days > 0) {
            days--;
            hours = 23;
            minutes = 59;
            seconds = 59;
        }

        document.getElementById('days').innerText = days;
        document.getElementById('hours').innerText = hours;
        document.getElementById('minutes').innerText = minutes;
        document.getElementById('seconds').innerText = seconds;
    }, 1000);
}

// Document Accordion Toggle
function toggleDocument(index) {
    const content = document.querySelector(`.document-content-${index}`);
    const chevron = document.querySelector(`.document-chevron-${index}`);

    content.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

// Start countdown on page load
window.addEventListener('load', startCountdown);
</script>

@endsection
