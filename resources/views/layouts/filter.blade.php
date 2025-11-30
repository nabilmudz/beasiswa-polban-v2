    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Additional styles for better visuals */
        .notification-popup {
            transition: all 0.3s ease;
        }
    </style>

        {{-- Filter Popup --}}
        <div id="popup" class="fixed inset-0 bg-opacity-50 backdrop-blur-md hidden flex items-center justify-center">
            <div class="bg-white w-full sm:w-3/4 p-6 sm:p-8 rounded-3xl shadow-xl max-w-lg mx-auto relative">
                <div class="absolute top-4 right-4">
                    <button onclick="hidePopup()" aria-label="Close" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <form action="{{ url('/beasiswa') }}" method="GET">
                        <div class="flex flex-col sm:flex-row justify-start gap-12 sm:gap-24">
                            <!-- Left Section: Checkboxes -->
                            <div class="flex flex-col items-start gap-6 sm:w-1/2">
                                <p class="text-xl font-semibold text-gray-700">Filter</p>

                                <!-- Jenis Beasiswa Section -->
                                <p class="text-sm sm:text-base font-medium text-gray-600">Jenis Beasiswa</p>
                                <div class="flex flex-row items-start gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jenis_beasiswa[]" value="half"
                                            {{ in_array('half', request('jenis_beasiswa', [])) ? 'checked' : '' }}
                                            class="rounded-full border-gray-300 focus:ring-0 focus:ring-offset-0 text-orange-500 h-8 w-8" />
                                        <label for="half" class="ml-2 text-sm text-gray-600">Half</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jenis_beasiswa[]" value="full"
                                            {{ in_array('full', request('jenis_beasiswa', [])) ? 'checked' : '' }}
                                            class="rounded-full border-gray-300 focus:ring-0 focus:ring-offset-0 text-orange-500 h-8 w-8" />
                                        <label for="full" class="ml-2 text-sm text-gray-600">Full</label>
                                    </div>
                                </div>

                                <!-- Jenjang Pendidikan Section -->
                                <p class="text-sm sm:text-base font-medium text-gray-600">Jenjang Pendidikan</p>
                                <div class="flex flex-row items-start gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jenjang_pendidikan[]" value="D3"
                                            {{ in_array('D3', request('jenjang_pendidikan', [])) ? 'checked' : '' }}
                                            class="rounded-full border-gray-300 focus:ring-0 focus:ring-offset-0 text-orange-500 h-8 w-8" />
                                        <label for="D3" class="ml-2 text-sm text-gray-600">D3</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jenjang_pendidikan[]" value="D4"
                                            {{ in_array('D4', request('jenjang_pendidikan', [])) ? 'checked' : '' }}
                                            class="rounded-full border-gray-300 focus:ring-0 focus:ring-offset-0 text-orange-500 h-8 w-8" />
                                        <label for="D4" class="ml-2 text-sm text-gray-600">D4</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Section: Dropdowns -->
                            <div class="flex flex-col items-start gap-6 sm:w-1/2 mt-6">
                                <!-- Tipe Beasiswa Section -->
                                <p class="text-sm sm:text-base font-medium text-gray-600">Tipe Beasiswa</p>
                                <div class="w-full">
                                    <select name="tipe_beasiswa" id="tipe_beasiswa"
                                        class="mt-2 block w-full rounded-full border border-gray-300 p-3 focus:border-orange-400 focus:ring-orange-300">
                                        <option value="">Select Tipe Beasiswa</option>
                                        <option value="kipk" {{ request('tipe_beasiswa') == 'kipk' ? 'selected' : '' }}>KIPK</option>
                                        <option value="internal" {{ request('tipe_beasiswa') == 'internal' ? 'selected' : '' }}>Internal</option>
                                        <option value="eksternal" {{ request('tipe_beasiswa') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                                    </select>
                                </div>

                                <!-- Jurusan Section -->
                                <p class="text-sm sm:text-base font-medium text-gray-600">Jurusan Khusus:</p>
                                <div class="w-full">
                                    <select name="jurusan" id="jurusan"
                                        class="block w-full rounded-full border border-gray-300 p-3 focus:border-orange-400 focus:ring-orange-300">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach ($jurusan as $j )
                                            <option value="{{ $j->nama_jurusan }}" {{ request('jurusan') ==  $j->nama_jurusan  ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons Section -->
                        <div class="flex flex-row justify-between gap-4 mt-6">
                            <button type="submit"
                                class="w-1/2 bg-orange-500 p-3 text-white rounded-full shadow-md hover:bg-blue-600">Apply</button>
                            <button type="button" onclick="hidePopup()"
                                class="w-1/2 bg-red-500 p-3 text-white rounded-full shadow-md hover:bg-red-600">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    {{-- Filter --}}
    <script>
        function showPopup() {
            document.getElementById('popup').classList.remove('hidden');
        }

        function hidePopup() {
            document.getElementById('popup').classList.add('hidden');
        }

        function toggleSelection(id) {
            const btn = document.getElementById(id);
            if (btn.style.backgroundColor === 'rgb(249, 115, 22)') { // Checking if the background is orange
                // Unselecting - Revert to default styles
                btn.style.backgroundColor = 'white';
                btn.style.color = '#6B7280'; // Grey text
                btn.style.border = '2px solid #F97316'; // Orange border
            } else {
                // Selecting - Apply selected styles
                btn.style.backgroundColor = '#F97316'; // Orange background
                btn.style.color = 'white'; // White text
                btn.style.border = 'none'; // No border for selected
            }
        }

        function setJenisBeasiswa(value) {
            document.getElementById('jenis_beasiswa').value = value;

            // Optionally, update the button styles to reflect the selected state
            document.getElementById('half').style.backgroundColor = (value === 'half') ? '#F97316' : 'white';
            document.getElementById('full').style.backgroundColor = (value === 'full') ? '#F97316' : 'white';

        }

        function searchBeasiswa() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const beasiswaCards = document.querySelectorAll('.beasiswa-card');

            beasiswaCards.forEach(card => {
                const namaBeasiswa = card.getAttribute('data-nama-beasiswa').toLowerCase();
                if (namaBeasiswa.includes(input)) {
                    card.style.display = ''; // Show the card
                } else {
                    card.style.display = 'none'; // Hide the card
                }
            });
        }

        function runFilter() {
            let halfSelected = document.getElementById('half').classList.contains('border-orange-500');
            let fullSelected = document.getElementById('full').classList.contains('border-orange-500');
            let programSelected = document.getElementById('tipe_beasiswa').value;
            let jenjangSelected = document.getElementById('jenjang_pendidikan').value;
            let jurusanSelected = document.getElementById('jurusan').value;

            // Create the query string based on selected filters
            let queryParams = new URLSearchParams();

            // Add jenis_beasiswa filters
            if (halfSelected) {
                queryParams.append('jenis_beasiswa[]', 'half');
            }
            if (fullSelected) {
                queryParams.append('jenis_beasiswa[]', 'full');
            }

            // Add tipe_beasiswa filter (only if it is selected)
            if (programSelected) {
                queryParams.append('tipe_beasiswa', programSelected);
            }

            // Add jenjang_pendidikan filter (only if it is selected)
            if (jenjangSelected) {
                queryParams.append('jenjang_pendidikan', jenjangSelected);
            }

            if (jurusanSelected) {
                queryParams.append('jurusan_khusus', jurusanSelected);
            }

            // Redirect to the filtered URL
            window.location.href = `?${queryParams.toString()}`;
        }
    </script>
    </head>

    <body>

    </body>

    </html>
