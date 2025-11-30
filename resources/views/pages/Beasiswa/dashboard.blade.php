@extends('layouts.main')
@section('content')
    @include('component.navbar', [
        'path' => 'List Beasiswa',
        'id' => null
    ])
    <style>
        .bg-gradient-to-tl {
            background: linear-gradient(to right, orange, rgb(213, 213, 21));
        }
    </style>

    <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">


        <!-- cards -->
        <div class="w-full px-6 py-6 mx-auto">

            <!-- cards row 2 -->
            <div class="flex flex-wrap  -mx-3">
                <div class="w-full px-3 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 lg:w-1/2 lg:flex-none ">
                                    <div class="flex flex-col h-full">
                                        <div class="basis-1/2">
                                            <p class="pt-2 mb-1 font-bold text-black text-3xl">Daftar Pengaju Beasiswa</p>
                                            <p class="text-sm text-gray">Ringkasan Laporan Pengajuan Beasiswa Polban</p>
                                        </div>
                                        <div class="basis-1/2">
                                            <div
                                                class="rounded-full max-w-full lg:w-full h-16 border-2 flex items-center justify-between px-3">
                                                <div class="flex -space-x-6">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <div class="w-14 h-14">
                                                            <img class="rounded-full w-full h-full object-cover border-2 border-white"
                                                                src="https://cdn2.f-cdn.com/files/download/38545966/4bce6b.jpg"
                                                                alt="">
                                                        </div>
                                                    @endfor
                                                </div>
                                                <div>
                                                    <p class="text-3xl mr-3">+{{ $data->total_pengajuan }}</p>
                                                </div>

                                            </div>
                                            <a href="/pengajuan/list-pengajuan">
                                                <div
                                                    class="rounded-3xl max-w-full lg:w-full h-10 border-2 flex items-center justify-center px-3 mt-5">
                                                    <p class="text-l ">Lihat lebih banyak</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="max-w-full mt-12 ml-auto text-center lg:mt-0 lg:w-5/12 lg:flex-none">
                                    <div class="w-full h-full bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl">
                                        <img src="./assets/img/shapes/waves-white.svg"
                                            class="absolute top-0 hidden w-1/2 h-full lg:block" alt="waves" />
                                        <div class="relative flex items-center justify-center h-full">
                                            <img class="relative z-20 w-full pt-6"
                                                src="{{ asset('/assets/img/graduation-cap-diploma-with-flat-design 1@2x.png') }}"
                                                alt="rocket" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 lg:w-5/12 lg:flex-none">
                    <div
                        class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4">
                        <div class="relative h-full overflow-hidden bg-cover rounded-xl"
                            style="background-image: url('./assets/img/ivancik.jpg')">

                            <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-white opacity-80"></span>

                            <div class="relative z-10 flex flex-col flex-auto h-full p-2">
                                <h3 class="font-semibold">Pengajuan Berdasarkan Status</h3>
                                <div>

                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mt-3">
                <!-- card1 -->
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold leading-normal">Beasiswa Aktif</p>
                                        <h5 class="mb-0 font-bold">
                                            {{ $data->beasiswa_on_going }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                        <i class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card2 -->
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold leading-normal">Jumlah Pengaju</p>
                                        <h5 class="mb-0 font-bold">
                                            {{ $data->total_pengajuan }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                        <i class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card3 -->
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold leading-normal">Total Beasiswa</p>
                                        <h5 class="mb-0 font-bold">
                                            {{ $data->total_beasiswa }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                        <i class="ni leading-none ni-paper-diploma text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card4 -->
                <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <div>
                                        <p class="mb-0 font-sans text-sm font-semibold leading-normal">Total Penerima
                                            Beasiswa</p>
                                        <h5 class="mb-0 font-bold">
                                            {{ $jmlPenerima }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                        <i class="ni leading-none ni-cart text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- cards row 4 -->

            <div class="flex flex-wrap my-6 -mx-3">
                <!-- card 1 -->

                <div class="w-full max-w-full px-3 mt-0 mb-6 md:mb-0 md:w-1/2 md:flex-none lg:w-2/3 lg:flex-none">
                    <div
                        class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">

                        <div class="flex-auto p-6 px-0 pb-2">
                            <div class="overflow-x-auto">
                                <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th
                                                class="px-6 py-3 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                                Nama Beasiswa</th>
                                            <th
                                                class="px-6 py-3 pl-2 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                                Tanggal Dibuka</th>
                                            <th
                                                class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                                Tanggal Ditutup</th>
                                            <th
                                                class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($beasiswa as $bf)
                                            <tr>
                                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                    <div class="flex px-2 py-1">
                                                        <div class="w-10 h-10">
                                                            <img class="rounded-full w-full h-full object-cover border-2 border-white"
                                                                src="https://clipground.com/images/logo-tut-wuri-handayani-png-3.jpg"
                                                                alt="xd" />
                                                        </div>
                                                        <div class="flex flex-col justify-center ml-3">
                                                            <h6 class="mb-0 text-sm leading-normal">{{ $bf->nama_beasiswa }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                                    <p>{{ $bf->tanggal_mulai }}</p>
                                                </td>
                                                <td
                                                    class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                                                    <p>{{ $bf->tanggal_berakhir }}</p>

                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b whitespace-nowrap flex justify-center ">
                                                    <div
                                                        class="w-3/4 rounded-full h-10 flex justify-center border-2 bg-green-500">
                                                        <p class=" font-medium text-white flex items-center text-sm">Dibuka
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card 2 -->

                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none lg:w-1/3 lg:flex-none">
                    <div
                        class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid p-2 bg-white bg-clip-border">
                        <h3 class="font-semibold mb-2">Pengajuan Berdasarkan Tahun</h3>
                        <div style="height: 100%; display: flex; align-items: stretch;">
                            <canvas id="myChart1" style="height: 100%; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-full px-3 md:flex-none h-full lg:flex-none">
                <div
                    class="border-black/12.5 shadow-soft-xl relative flex h-96 min-w-0 flex-col break-words rounded-2xl p-2 border-0 border-solid bg-white bg-clip-border">
                    <h3 class="font-semibold mb-2">Pengajuan Berdasarkan Jurusan</h3>
                    <div class="flex gap-3">
                        <label for="jurusanSelect">Filter by Jurusan:</label>
                        <form method="GET" action="{{ route('dashboard.index') }}" class="max-w-sm">
                            <select id="countries" name="nama_jurusan"
                                class="bg-gray-50 border border-gray-300 text-white-900 text-sm rounded-lg"
                                onchange="this.form.submit()">
                                <option value="">Select Jurusan</option>
                                <!-- Optional: Placeholder for the dropdown -->
                                @foreach ($jurusan as $jr)
                                    <option value="{{ $jr->nama_jurusan }}"
                                        {{ request()->input('nama_jurusan') == $jr->nama_jurusan ? 'selected' : '' }}>
                                        {{ $jr->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div style="height: 100%; display: flex; align-items: stretch;">
                        <canvas id="myChart2" style="height: 100%; width: 100%;"></canvas>
                    </div>
                </div>
            </div>


        </div>

    </body>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        @json($data->pengajuan_diajukan),
                        @json($data->pengajuan_diterima),
                        @json($data->pengajuan_direvisi),
                        @json($data->pengajuan_ditolak)
                    ],
                    backgroundColor: [
                        'rgb(170, 0, 170)', // Diajukan
                        'rgb(0, 170, 0)', // Diterima
                        'rgb(255, 205, 86)', // Direvisi
                        'rgb(255, 99, 132)' // Ditolak
                    ],
                    label: 'jumlah' // Label for the dataset
                }],
                labels: [
                    'Diajukan', // Label for first segment
                    'Diterima', // Label for second segment
                    'Direvisi', // Label for third segment
                    'Ditolak'
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    datalabels: {
                        color: '#333',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        align: 'end',
                        anchor: 'end',
                        offset: 5,
                        formatter: (value, context) => {
                            const labels = context.chart.data.labels; // Get the labels array
                            const label = labels[context.dataIndex]; // Get the label for the hovered segment

                            // Only return the label and value if the value is greater than 0
                            if (value > 0) {
                                return `${label}: ${value}`;
                            } else {
                                return ''; // Hide the label if value is 0 or less
                            }
                        }

                    }
                },
            },
            plugins: [ChartDataLabels]
        });




        const ctx1 = document.getElementById('myChart1').getContext('2d');

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['2021', '2022', '2023', '2024'], // Example labels
                datasets: [{
                    // Hide the label by not including it
                    data: [@json($data->pengajuan_3_tahun_lalu), @json($data->pengajuan_2_tahun_lalu),
                        @json($data->pengajuan_tahun_lalu), @json($data->pengajuan_tahun_ini)
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide the legend
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        const ctx2 = document.getElementById('myChart2').getContext('2d');
        const date = new Date();
        const currentYear = date.getFullYear();

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [
                    currentYear - 5,
                    currentYear - 4,
                    currentYear - 3,
                    currentYear - 2,
                    currentYear - 1,
                    currentYear
                ], // Example labels
                datasets: [{
                    // Hide the label by not including it
                    data: [@json($pengajuanTahun->jumlah_5_tahun_lalu), @json($pengajuanTahun->jumlah_4_tahun_lalu),
                        @json($pengajuanTahun->jumlah_3_tahun_lalu), @json($pengajuanTahun->jumlah_2_tahun_lalu),
                        @json($pengajuanTahun->jumlah_tahun_lalu), @json($pengajuanTahun->jumlah_tahun_sekarang)
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide the legend
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endsection
