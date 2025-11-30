<!-- sidenav -->
<style>
    .icon {
        background: white; /* Warna default latar belakang */
        transition: background 0.3s ease, transform 0.2s ease; /* Transisi untuk latar belakang dan transformasi */
    }

    /* Gaya untuk ikon yang aktif */
    .icon.active {
        background: linear-gradient(to top left, #fbbf24, #f97316); /* Gradasi dari kuning ke oranye */
        transform: scale(1.1); /* Sedikit membesar saat aktif */
    }

    /* Perbaikan CSS untuk sidebar link - menghilangkan gerakan yang tidak diinginkan */
    .sidebar-link {
        transition: all 0.3s ease; /* Transisi halus untuk semua properti */
        transform: translateX(0); /* Posisi awal */
        opacity: 1; /* Opacity awal */
        position: relative; /* Untuk kontrol posisi yang lebih baik */
    }

    .sidebar-link.active {
        border: 2px;
        background-color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transform: translateX(5px); /* Geser sedikit saat aktif */
        opacity: 1; /* Pastikan opacity penuh saat aktif */
    }

    /* Perbaikan efek hover - hanya mempengaruhi elemen yang di-hover */
    .sidebar-link:hover {
        transform: scale(1.02) translateX(2px); /* Kombinasi scale dan translate yang lebih halus */
        background-color: rgba(255, 255, 255, 0.9);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Menghapus efek pada elemen lain ketika hover - ini yang menyebabkan masalah */
    /* .sidebar-link:not(.active):not(:hover) - DIHAPUS KARENA MENYEBABKAN GERAKAN */

    /* Khusus untuk active state hover */
    .sidebar-link.active:hover {
        transform: scale(1.02) translateX(5px); /* Mempertahankan translateX untuk active */
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
    }

    /* Styling untuk tombol logout dan cancel tetap sama */
    .btn-logout {
        background-color: #d33 !important; /* merah */
        color: white !important;
        border-radius: 6px;
        padding: 8px 16px;
        border: none;
        margin-right: 1rem;
    }

    .btn-logout:hover {
        background-color: #b71c1c !important;
    }

    .btn-cancel {
        background-color: #3085d6 !important; /* biru */
        color: white !important;
        border-radius: 6px;
        padding: 8px 16px;
        border: none;
    }

    .btn-cancel:hover {
        background-color: #2563eb !important;
    }

    /* Tambahan: Memastikan container tidak bergerak */
    .sidebar-container {
        position: relative;
        overflow: visible;
    }

    /* Perbaikan untuk icon hover effect */
    .sidebar-link:hover .icon {
        transform: scale(1.05);
        background: rgba(251, 191, 36, 0.1); /* Sedikit warna kuning transparan */
    }

    .sidebar-link.active:hover .icon {
        transform: scale(1.15); /* Sedikit lebih besar untuk active state */
    }

    /* Smooth transition untuk semua list items */
    .sidebar-list {
        display: flex;
        flex-direction: column;
        gap: 2px; /* Jarak konsisten antar item */
    }

    /* Memastikan tidak ada perubahan margin atau padding saat hover */
    .sidebar-link * {
        pointer-events: none; /* Mencegah child elements memicu hover yang tidak diinginkan */
    }
</style>

<aside
    class="max-w-62.5 h-screen ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
    <div class="h-19.5">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden"
            sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="javascript:;" target="_blank">
            <img src="{{asset('/assets/img/logo-polban.png')}}"
                class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">POLBAN SCHOLARSHIP</span>
        </a>
    </div>

    <!-- Menambahkan class sidebar-container dan sidebar-list untuk kontrol yang lebih baik -->
    <div class="sidebar-container">
        <ul class="sidebar-list flex flex-col pl-0 mb-0">
        @if (session()->has('auth') && session('auth')['role'] === 'reviewer')
            <li class="mt-0.5 w-full">
                <a id="dashboard-link" class="sidebar-link py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition duration-300 hover:border hover:bg-white hover:shadow-xl"
                href="/dashboard">
                    <div class="icon shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                        <i class="fas fa-tachometer-alt text-slate-800"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
                </a>
            </li>
        @endif

        <li class="mt-0.5 w-full">
        @if (session()->has('auth') && session('auth')['role'] === 'reviewer')
            <a id="beasiswa-link" class="sidebar-link py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition duration-300 hover:border hover:bg-white hover:shadow-xl"
            href="/list-beasiswa-staff">
            @else
            <a id="beasiswa-link" class="sidebar-link py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition duration-300 hover:border hover:bg-white hover:shadow-xl"
            href="/beasiswa">
            @endif
                <div class="icon shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-graduation-cap text-slate-800"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Beasiswa</span>
            </a>
        </li>

        @if (session()->has('auth'))
        <li class="mt-0.5 w-full">
            <a id="beasiswa-link" class="sidebar-link py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition duration-300 hover:border hover:bg-white hover:shadow-xl"
            href="/pengajuan/list-pengajuan">
                <div class="icon shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-clipboard-list text-slate-800"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Tracking</span>
            </a>
        </li>

        <li class="mt-0.5 w-full">
            <a id="billing-link" class="sidebar-link py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition duration-300 hover:border hover:bg-white hover:shadow-xl"
                href="/pengumuman-beasiswa">
                <div
                    class="icon shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center fill-current stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-bullhorn text-slate-800"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Pengumuman</span>
            </a>
        </li>
        @endif

        <li class="mt-0.5 w-full">
            <a id="billing-link" class="sidebar-link py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition duration-300 hover:border hover:bg-white hover:shadow-xl"
                href="/madding">
                <div
                    class="icon shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center fill-current stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-newspaper text-slate-800"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Madding</span>
            </a>
        </li>

        @if (session()->has('auth'))
        <li class="w-full mt-4">
            <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Account pages</h6>
        </li>

        <li class="mt-0.5 w-full">
            <a id="profile-link" class="sidebar-link py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 rounded-lg transition duration-300 hover:border hover:bg-white hover:shadow-xl"
            href="/pengaturan">
                <div class="icon shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-user text-slate-800"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Profile</span>
            </a>
        </li>

        <!-- Logout Button -->
        <li class="mt-0.5 w-full">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-200 rounded-lg w-full text-left">
                    <div
                        class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-red-600 text-white bg-center stroke-0 text-center xl:p-2.5">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                        Logout
                    </span>
                </button>
            </form>
        </li>

        @else
        <!-- Login Button -->
        <li class="mt-0.5 w-full">
            <a href="{{ route('login') }}"
                class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-200 rounded-lg">
                <div
                    class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-orange-600 text-white bg-center stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                    Login
                </span>
            </a>
        </li>
        </ul>
        @endif
    </div>
</aside>

<!-- end sidenav -->

<main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
    <!-- Navbar -->
    <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start"
        navbar-main navbar-scroll="true">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
            <nav>
                <!-- breadcrumb -->
                <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                    <li class="text-sm leading-normal">
                        <a class="opacity-50 text-slate-700" href="javascript:;">Pages</a>
                    </li>
                    <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']"
                        aria-current="page">{{ $path }}</li>
                </ol>
                <h6 class="mb-0 font-bold capitalize">{{ $path . ($id ? '/' . $id : null) }} </h6>
            </nav>

            <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
                <div class="flex items-center md:ml-auto md:pr-4">
                </div>
                <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                    <li class="flex items-center pl-4 xl:hidden">
                        <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand text-slate-500"
                            sidenav-trigger>
                            <div class="w-4.5 overflow-hidden">
                                <i
                                    class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                                <i
                                    class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                                <i class="ease-soft relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                            </div>
                        </a>
                    </li>
                    @if (session()->has('auth'))
                    <li class="flex items-center px-4">
                        <a href="javascript:;" class="p-0 text-sm transition-all ease-nav-brand text-slate-500">
                            <i fixed-plugin-button-nav class="cursor-pointer fa fa-cog"></i>
                            <!-- fixed-plugin-button-nav  -->
                        </a>
                    </li>

                    <!-- notifications -->
                    <li class="relative flex items-center pr-2">
                        <p class="hidden transform-dropdown-show"></p>
                        <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand text-slate-500"
                            dropdown-trigger aria-expanded="false">
                            <i class="cursor-pointer fa fa-bell"></i>
                            <!-- Tampilkan titik merah jika ada notifikasi yang belum dibaca -->
                            @if(isset($notificationData) && $notificationData->where('read', false)->count() > 0)
                                <span class="absolute right-0 top-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                @if(isset($notificationData) && $notificationData->where('read', true)->count() > 0)
                                <span></span>
                                @endif
                            @endif
                        </a>

                    <ul dropdown-menu class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease-soft lg:shadow-soft-3xl duration-250 min-w-44 before:sm:right-7.5 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-['\f0d8'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer">
                        <!-- Check if notifications are available -->
                        @if(isset($notificationData) && count($notificationData) > 0)
                            <!-- Looping through notifications -->
                            @foreach ($notificationData as $notification)
                                <li class="relative mb-2" data-id="{{ $notification->id }}">
                                    <a class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors"
                                        href="javascript:;" onclick="markAsRead(this)">
                                        <div class="flex py-1">
                                            <div class="my-auto">
                                                <i class="cursor-pointer fa fa-bell inline-flex items-center justify-center mr-4 text-sm text-grey h-9 w-9 max-w-none rounded-xl" aria-hidden="true"></i>
                                            </div>
                                            <div class="flex flex-col justify-center notification-content">
                                                @if(!$notification->read)
                                                    <span class="absolute right-0 top-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                                @endif
                                                <h6 class="mb-1 text-sm font-normal leading-normal">
                                                    <p>{{ $notification->pengajuanBeasiswa->Beasiswa->nama_beasiswa }}<p>
                                                    {{ $notification->pengajuanBeasiswa->Status->isi_status }}
                                                </h6>
                                                <p class="text-xs text-gray-400">
                                                    <i class="mr-1 fa fa-clock"></i>
                                                    {{ $notification->created_at }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                            @else
                                <!-- If there are no notifications, show this message -->
                                <li class="relative mb-2">
                                    <p class="text-center text-gray-500 py-2">No new notifications</p>
                                </li>
                            @endif
                        </ul>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('logout-form').addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin logout?',
                text: "Anda akan keluar dari sesi ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, logout',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn-logout',
                    cancelButton: 'btn-cancel'
                },
                buttonsStyling: false // supaya pakai CSS kita sendiri
            })
            .then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Perbaikan JavaScript untuk navigasi yang lebih smooth
        const navLinks = document.querySelectorAll('.sidebar-link');

        // Fungsi untuk menangani transisi halus - diperbaiki
        function setActiveLink(activeLink) {
            // Hapus kelas 'active' dari semua tautan dan ikon
            navLinks.forEach(l => {
                l.classList.remove('active');
                const icon = l.querySelector('.icon');
                if (icon) {
                    icon.classList.remove('active');
                }
                // Tidak mengubah opacity dan transform di sini untuk menghindari gerakan
            });

            // Tambahkan kelas 'active' pada tautan yang diklik
            activeLink.classList.add('active');
            const icon = activeLink.querySelector('.icon');
            if (icon) {
                icon.classList.add('active');
            }

            // Simpan href tautan yang diklik ke localStorage
            localStorage.setItem('activeNavLink', activeLink.getAttribute('href'));
        }

        // Event listener yang diperbaiki
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Jangan prevent default untuk navigasi normal
                setActiveLink(this);
                
                // Biarkan navigasi berjalan normal tanpa delay
            });
        });

        // Mendapatkan URL saat ini
        const currentPath = window.location.pathname;

        // Ketika halaman dimuat - diperbaiki
        document.addEventListener('DOMContentLoaded', function() {
            // Periksa URL saat ini dan set active state
            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href');
                if (linkPath === currentPath || 
                    (currentPath.includes(linkPath) && linkPath !== '/')) {
                    setActiveLink(link);
                }
            });

            // Juga periksa localStorage untuk konsistensi
            const activeLinkHref = localStorage.getItem('activeNavLink');
            if (activeLinkHref) {
                navLinks.forEach(link => {
                    if (link.getAttribute('href') === activeLinkHref) {
                        setActiveLink(link);
                    }
                });
            }
        });

        // Improved dropdown handling for notifications - sama seperti sebelumnya
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownTrigger = document.querySelector('[dropdown-trigger]');
            const dropdownMenu = document.querySelector('[dropdown-menu]');

            if (dropdownTrigger && dropdownMenu) {
                function toggleDropdown(event) {
                    event.stopPropagation();
                    const isExpanded = dropdownTrigger.getAttribute('aria-expanded') === 'true';
                    dropdownTrigger.setAttribute('aria-expanded', !isExpanded);
                    dropdownMenu.style.opacity = isExpanded ? '0' : '1';
                    dropdownMenu.style.pointerEvents = isExpanded ? 'none' : 'auto';

                    if (!isExpanded) {
                        const triggerRect = dropdownTrigger.getBoundingClientRect();
                        dropdownMenu.style.right = '0';
                    }
                }

                dropdownTrigger.addEventListener('click', toggleDropdown);

                document.addEventListener('click', function(event) {
                    if (!dropdownTrigger.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownTrigger.setAttribute('aria-expanded', 'false');
                        dropdownMenu.style.opacity = '0';
                        dropdownMenu.style.pointerEvents = 'none';
                    }
                });
            }

            window.markAsRead = function(element) {
                const notificationId = element.closest('li').getAttribute('data-id');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                const baseUrl = window.location.origin;
                const normalizedPath = '/notifications/mark-as-read/' + notificationId;
                const fullUrl = baseUrl + normalizedPath;

                fetch(fullUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notificationElement = element.closest('li');
                        const redDot = notificationElement.querySelector('span.bg-red-500');
                        if (redDot) {
                            redDot.remove();
                        }

                        const unreadDots = document.querySelectorAll('li span.bg-red-500');
                        const bellIconDot = document.querySelector('a[dropdown-trigger] > span.bg-red-500');
                        if (unreadDots.length === 0 && bellIconDot) {
                            bellIconDot.remove();
                        }

                        updateNotificationContent();
                    }
                })
                .catch(error => {
                    console.error('Error updating notification:', error);
                });
            };

            function updateNotificationContent() {
                const notificationContents = document.querySelectorAll('.notification-content');
                notificationContents.forEach(content => {
                    content.addEventListener('click', () => {
                        location.reload();
                    });
                });
            }
        });

        document.querySelectorAll('.notification-content').forEach(item => {
            item.addEventListener('click', () => {
                location.reload();
            });
        });
    </script>