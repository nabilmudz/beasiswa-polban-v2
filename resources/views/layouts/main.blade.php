<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-polban.png') }}" />
    <title>POLBAN SCHOLARSHIP</title>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    
    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slider.css') }}">
    
    <!-- Main Styling -->
    <link href="{{ asset('assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                fontFamily: {
                    sans: ["Roboto", "sans-serif"],
                    body: ["Roboto", "sans-serif"],
                    mono: ["ui-monospace", "monospace"],
                },
            },
            corePlugins: {
                preflight: false,
            },
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="overflow-hidden">

    <main class="min-h-screen overflow-y-auto">
        @include('component.alert')
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showAlert('success', 'Success', "{{ session('success') }}");
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showAlert('error', 'Error', {!! json_encode(session('error')) !!});
                });
            </script>
        @endif
        @yield('content')
    </main>

    <script src="{{ asset('assets/js/alert.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}" async></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <script src="https://buttons.github.io/buttons.js" async defer></script>
    <script src="{{ asset('assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5') }}" async></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>