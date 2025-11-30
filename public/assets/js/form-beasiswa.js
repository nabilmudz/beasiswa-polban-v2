let dokumenCounter = 1; // jumlah input dokumen
let selectedDokumen = [];
let selectedFiles = []; 

// pagination popup
let currentPage = 1; 
let last_page = 1;


if (typeof dokumen_tags === 'undefined') {
    var dokumen_tags = []; // Initialize dokumen_tags array if it is not already defined
}
if (typeof syarat_tags === 'undefined') {
    var syarat_tags = []; // Initialize syarat_tags array if it is not already defined
}
if (typeof benefit_tags === 'undefined') {
    var benefit_tags = []; // Initialize benefit_tags array if it is not already defined
}
if (typeof jenjang_tags === 'undefined') {
    var jenjang_tags = []; // Initialize jenjang_tags array if it is not already defined
}

document.addEventListener('DOMContentLoaded', function () {
    const posterBeasiswa = document.getElementById('poster_beasiswa');
    if (posterBeasiswa) {
        posterBeasiswa.addEventListener('change', function () {
            if (this.files.length > 3) {
                alert("Anda hanya dapat mengupload maksimal 3 file.");
                this.value = ""; // Reset file input
            }
        });
    }

    document.getElementById('close-modal').onclick = function () {
        event.preventDefault();
        document.getElementById('modal').classList.add('hidden'); // Sembunyikan modal saat tombol close diklik
    };
    
    document.getElementById('modal').onclick = function (e) {
        if (e.target === this) {
            event.preventDefault();
            this.classList.add('hidden'); // Sembunyikan modal saat area luar gambar diklik
        }
    };

    const form = document.getElementById('beasiswa-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        createHiddenInput();
        form.submit();
    })

    // $(document).mouseup(function (e) {
    //     if ($(e.target).closest("#popup > div").length === 0) {
    //         hidePopup();
    //     }
    // });

    let selectedRadio = document.querySelector('input[name="tipe_beasiswa"]:checked');
    if (selectedRadio) {
        showForm(selectedRadio.value);
    }
});

function fetchJenjangTags() {
    let query = $('#jenjang_pendidikan').val().trim();
    if (query === '') {
        $('#jenjang-suggestions').addClass('hidden');
        return;
    }
    $.ajax({
        url: routes.searchJenjang,
        type: 'GET',
        data: { query: query },
        success: function(tags) {
            let suggestions = $('#jenjang-suggestions').empty().removeClass('hidden');
            if (!tags.length) {
                suggestions.addClass('hidden');
                return;
            }
            tags.forEach(tag => {
                suggestions.append(`
                    <div class="tag-suggestion-jenjang px-4 py-2 text-gray-700 hover:bg-indigo-100 cursor-pointer">
                        ${tag.nama_prodi}
                    </div>
                `);
            });
            $('.tag-suggestion-jenjang').on('click', function() {
                addJenjangTag($(this).text());
                suggestions.empty().addClass('hidden');
            });
        }
    });
}

function addJenjangTag(tagText) {
    tagText = tagText.trim();

    if (jenjang_tags.includes(tagText)) {
        $('#jenjang_pendidikan').val('');
        return;
    }

    if (tagText === ''){
        return;
    }

    // Tambahkan tag ke array dan ke UI
    jenjang_tags.push(tagText);
    renderTags('jenjang');
    updateJenjangCounter();
    $('#jenjang_pendidikan').val('');
}

function updateJenjangCounter() {
    $("#tag-counter-jenjang").text("Jumlah jenjang pendidikan yang dipilih: " + jenjang_tags.length);
}

function fetchBeasiswaTags() {
    let query = $('#syarat_beasiswa').val().trim();
    if (query === '') {
        $('#syarat-suggestions-beasiswa').addClass('hidden');
        return;
    }
    $.ajax({
        url: routes.searchSyarat,
        type: 'GET',
        data: { query: query },
        success: function(tags) {
            let suggestions = $('#syarat-suggestions-beasiswa').empty().removeClass('hidden');
            if (!tags.length) {
                suggestions.addClass('hidden');
                return;
            }
            tags.forEach(tag => {
                suggestions.append(`
                    <div class="tag-suggestion-syarat px-4 py-2 text-gray-700 hover:bg-indigo-100 cursor-pointer">
                        ${tag.syarat}
                    </div>
                `);
            });
            $('.tag-suggestion-syarat').on('click', function() {
                addBeasiswaTag($(this).text());
                suggestions.empty().addClass('hidden');
            });
        }
    });
}

function addBeasiswaTag(tagText) {
    tagText = tagText.trim();
    if (syarat_tags.includes(tagText)) {
        $('#syarat_beasiswa').val('');
        return;
    }

    if (tagText === ''){
        return;
    }
    // Tambahkan tag ke array dan ke UI
    syarat_tags.push(tagText);
    console.log("adding tag", tagText);
    renderTags('syarat');
    updateBeasiswaCounter();
    $('#syarat_beasiswa').val('');
}

function updateBeasiswaCounter() {
    $("#tag-counter-beasiswa").text("Jumlah syarat beasiswa yang dipilih: " + syarat_tags.length);
}

function fetchBenefitTags() {
    let query = $('#benefit_beasiswa').val().trim();
    if (query === '') {
        $('#benefit-suggestions-beasiswa').addClass('hidden');
        return;
    }
    $.ajax({
        url: routes.searchBenefit,
        type: 'GET',
        data: { query: query },
        success: function(tags) {
            let suggestions = $('#benefit-suggestions-beasiswa').empty().removeClass('hidden');
            if (!tags.length) {
                suggestions.addClass('hidden');
                return;
            }
            tags.forEach(tag => {
                suggestions.append(`
                    <div class="tag-suggestion-benefit px-4 py-2 text-gray-700 hover:bg-indigo-100 cursor-pointer">
                        ${tag.benefit}
                    </div>
                    `);
            });
            $('.tag-suggestion-benefit').on('click', function() {
                addBenefitTag($(this).text());
                suggestions.empty().addClass('hidden');
            });
        }
    });
}

function addBenefitTag(tagText) {
    tagText = tagText.trim();

    if (benefit_tags.includes(tagText)) {
        $('#benefit_beasiswa').val('');
        return;
    }

    benefit_tags.push(tagText);
    renderTags('benefit');
    updateBenefitCounter();
    $('#benefit_beasiswa').val('');
} 

function updateBenefitCounter() {
    $("#tag-counter-benefit").text("Jumlah benefit beasiswa yang dipilih: " + benefit_tags.length);
}

function fetchDokumenTags(dokumenID) {
    element = $(`#dokumen-${dokumenID}`)
    if (element.prop('readonly')) {return;}
    query = element.val().trim();
    if (query === '') {
        $(`#syarat-suggestions-dokumen-${dokumenID}`).addClass('hidden');
        return;
    }
    if (dokumen_tags.includes(query)){
        $(`#syarat-suggestions-dokumen-${dokumenID}`).addClass('hidden');
        return;
    }
    $.ajax({
        url: routes.searchDokumen,
        type: 'GET',
        data: { query: query },
        success: function(tags) {
            suggestions = $(`#syarat-suggestions-dokumen-${dokumenID}`).empty().removeClass('hidden');
            if (!tags.length) {
                suggestions.addClass('hidden');
                return;
            }
            tags.forEach(tag => {
                suggestions.append(`
                    <div class="tag-suggestion-dokumen px-4 py-2 text-gray-700 hover:bg-indigo-100 cursor-pointer" data-name="${tag.dokumen}" data-link-dokumen="${tag.link_dokumen}">
                        ${tag.dokumen}
                    </div>
                `);
            });

            $('.tag-suggestion-dokumen').on('click', function() {
                addDokumenTag($(this).text().trim(), dokumenID);
                addDokumenFile($(this).data('link-dokumen'), dokumenID); // kirimkan link_dokumen ke fungsi addDokumenFile
                suggestions.empty().addClass('hidden');
            });
        }
    });
}

function addDokumenTag(tagText, dokumenID) {
    tagText = tagText.trim();
    const dokumen_input_field = $(`#dokumen-${dokumenID}`);

    if (dokumen_input_field.prop('readonly')) {
        return;
    } else if (dokumen_tags.includes(tagText)) {
        // Tambahkan tanda pada input dan tampilkan alert
        dokumen_input_field.addClass("border-red-500"); // Beri warna merah pada border
        alert("Tag sudah ada!");
        // dokumen_input_field.val(''); // Kosongkan input
        return;
    }

    if (tagText === '') {
        alert("Field tag kosong!");
        return;
    }

    // Tambahkan tag ke array dan set nilai input menjadi readonly
    dokumen_tags.push(tagText);

    dokumen_input_field.val(tagText);
    dokumen_input_field.prop('readonly', true);

    console.log(`Tag "${tagText}" berhasil ditambahkan.`);
}

function updateDokumenCounter() {
    let count = dokumen_tags.length;
    document.getElementById("tag-counter-dokumen").textContent = "Jumlah syarat dokumen yang dipilih: " + count;
}

function addDokumenFile(link, dokumenID) {
    const dokumen_name_span = $(`#dokumen-name-${dokumenID}`); // Assuming you have unique ids for each span, like dokumen-name-1, dokumen-name-2

    if (!(typeof link === 'string') && !(link instanceof File)){
        console.error('Invalid input: Expected a string or a File object');
    } else if (typeof link === 'string') {
        // Extract the filename from the link
        const filename = link.split('/').pop();

        // Modify the span text to indicate that the file has been added
        dokumen_name_span.text(filename); // Update the span text to show the filename

        // Store the link as a data attribute on the span element
        dokumen_name_span.attr('data-link', link); // Store the link in a data attribute (data-link)  
        console.log("url added");
    } else if (link instanceof File) {
        // Case 2: link is a File (from input[type="file"])
        const filename = link.name;  // Get the filename from the File object
        dokumen_name_span.text(filename);  // Update the span text with the filename
        dokumen_name_span.attr('data-link', URL.createObjectURL(link));  // Store a temporary URL for the file as a data attribute
        console.log("file added");
    } 

    url = dokumen_name_span.attr('data-link');
    selectedDokumen.push(url);
    console.log(selectedDokumen);
}

function renderTags(tagCategory) {
    // Get the tag container based on category
    const tagContainer = $(`#selected-tags-${tagCategory}`);

    // Clear the container to avoid duplicates
    tagContainer.empty();
    console.log("rendering tag")
    // Check if the category exists in the tagData
    if (tagCategory === 'benefit') {
        // Loop through the tags and render each
        benefit_tags.forEach(tagText => {
            tagContainer.append(`
                <div class="flex items-center bg-indigo-100 text-indigo-700 rounded-md px-2 py-1 text-sm">
                    ${tagText}
                    <span class="ml-2 text-gray-500 hover:text-red-500 cursor-pointer" onclick="removeTag('${tagText.replace(/'/g, "\\'")}', this, '${tagCategory}_tags');">×</span>
                    <input type="hidden" name="${tagCategory}_beasiswa[]" value="${tagText}">
                </div>
            `);
        });
    } else if (tagCategory === 'syarat') {
        syarat_tags.forEach(tagText => {
            tagContainer.append(`
                <div class="flex items-center bg-indigo-100 text-indigo-700 rounded-md px-2 py-1 text-sm">
                    ${tagText}
                    <span class="ml-2 text-gray-500 hover:text-red-500 cursor-pointer" onclick="removeTag('${tagText.replace(/'/g, "\\'")}', this, '${tagCategory}_tags');">×</span>
                    <input type="hidden" name="${tagCategory}_beasiswa[]" value="${tagText}">
                </div>
            `);
        });
    } else if (tagCategory === 'jenjang') {
        jenjang_tags.forEach(tagText => {
            tagContainer.append(`
                <div class="flex items-center bg-indigo-100 text-indigo-700 rounded-md px-2 py-1 text-sm">
                    ${tagText}
                    <span class="ml-2 text-gray-500 hover:text-red-500 cursor-pointer" onclick="removeTag('${tagText.replace(/'/g, "\\'")}', this, '${tagCategory}_tags');">×</span>
                    <input type="hidden" name="${tagCategory}_pendidikan[]" value="${tagText}">
                </div>
            `);
        });
    } else {
        console.error(`Unknown tag category: ${tagCategory}`);
    }
}

function removeTag(tagText, element, arrayName) {
    tagText = tagText.trim();

    // Hapus tag dari UI
    $(element).parent().remove();

    if (arrayName == 'benefit_tags'){
        benefit_tags = benefit_tags.filter(tag => tag !== tagText);
        updateBenefitCounter();
    } else if (arrayName == 'jenjang_tags'){
        jenjang_tags = jenjang_tags.filter(tag => tag !== tagText);
        updateJenjangCounter();
    } else if (arrayName == 'syarat_tags'){
        syarat_tags = syarat_tags.filter(tag => tag !== tagText);
        updateBeasiswaCounter();
    } else {
        dokumen_tags = dokumen_tags.filter(tag => tag !== tagText);
        updateDokumenCounter();
    }
}


function displayFileNamesAndPreview() {
    const input = document.getElementById('poster_beasiswa');
    
    // Tambahkan file yang baru dipilih ke dalam array selectedFiles
    for (let i = 0; i < input.files.length; i++) {
        const file = input.files[i];
        
        if (selectedFiles.length > 2) {
            alert("Anda hanya dapat mengupload maksimal 3 file.");
            input.value = "";
            break;
        }
        // Pastikan file belum ada di array
        if (!selectedFiles.some(item => item.name === file.name && item.lastModified === file.lastModified)) {
            selectedFiles.push(URL.createObjectURL(file));
        }
    }

    // Simpan file yang dipilih ke sessionStorage
    // selectedFiles = Array.from(input.files);
    console.log(selectedFiles);
    // saveFilesToStorage(selectedFiles);
    
    renderPreviews(selectedFiles);  // Tampilkan pratinjau untuk file yang dipilih
}

function renderPreviews() {
    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = ''; // Kosongkan kontainer preview

    selectedFiles.forEach((file, index) => {
        if (file instanceof File){
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('relative', 'w-24', 'h-24', 'mb-2', 'mr-2');
    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    img.classList.add('w-full', 'h-full', 'object-cover', 'rounded-md', 'shadow-sm');
    
                    // Fungsi untuk memperbesar gambar saat diklik
                    img.onclick = () => openModal(e.target.result);
    
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'X';
                    deleteButton.classList.add(
                        'absolute', 'top-1', 'right-1', 'bg-red-500', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex',
                        'items-center', 'justify-center', 'text-xs', 'opacity-0', 'hover:opacity-100', 'transition-opacity'
                    );
                    deleteButton.onclick = () => removeFile(index); // Panggil fungsi `removeFile`
    
                    imgContainer.onmouseenter = () => (deleteButton.style.opacity = '1'); // Tampilkan tombol saat dihover
                    imgContainer.onmouseleave = () => (deleteButton.style.opacity = '0'); // Sembunyikan tombol saat tidak dihover
    
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(deleteButton);
                    previewContainer.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            }
        } else if (typeof file === 'string'){
            const imgContainer = document.createElement('div');
            imgContainer.classList.add('relative', 'w-24', 'h-24', 'mb-2', 'mr-2');

            const img = document.createElement('img');
            img.src = file; // Menggunakan file sebagai URL langsung
            img.alt = getFileName(file);
            img.classList.add('w-full', 'h-full', 'object-cover', 'rounded-md', 'shadow-sm');

            // Fungsi untuk memperbesar gambar saat diklik
            img.onclick = () => openModal(file); 

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'X';
            deleteButton.classList.add(
                'absolute', 'top-1', 'right-1', 'bg-red-500', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex',
                'items-center', 'justify-center', 'text-xs', 'opacity-0', 'hover:opacity-100', 'transition-opacity'
            );
            deleteButton.onclick = () => removeFile(index); // Panggil fungsi `removeFile`

            imgContainer.onmouseenter = () => (deleteButton.style.opacity = '1'); // Tampilkan tombol saat dihover
            imgContainer.onmouseleave = () => (deleteButton.style.opacity = '0'); // Sembunyikan tombol saat tidak dihover

            imgContainer.appendChild(img);
            imgContainer.appendChild(deleteButton);
            previewContainer.appendChild(imgContainer);
            console.log("image rendered")
        }
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1); // Hapus file dari array `selectedFiles`
    renderPreviews(); // Perbarui preview gambar
}

function removeAllFiles() {
    // Kosongkan array selectedFiles
    selectedFiles.splice(0, selectedFiles.length);

    // Perbarui daftar file dan preview
    renderPreviews();
}

function openModal(imageSrc) {
    const modal = document.getElementById('modal');
    const modalImage = document.getElementById('modal-image');
    modalImage.src = imageSrc; // Set gambar modal ke gambar yang diklik
    modal.classList.remove('hidden'); // Tampilkan modal
}

function getFileName(url) {
    // Extract the filename from the Firebase Storage URL
    const parts = url.split('/');
    const fileName = parts[parts.length - 2]; // Get the second-to-last part (filename)

    // Remove any percent-encoding from the filename
    const decodedFileName = decodeURIComponent(fileName);

    return decodedFileName;
}


// Membuat form row baru
function createFormRow() {
    if (typeof dokumenCounter === 'undefined') {let dokumenCounter = 1;}
    console.log("creating form");
    dokumenCounter++;
    console.log(dokumenCounter);

    const formContainer = document.getElementById("form-container");
    const newFormRow = document.createElement("div");
    newFormRow.className = "mt-3 grid grid-cols-12 gap-4 items-center";
    newFormRow.id = `form-row-${dokumenCounter}`;

    newFormRow.innerHTML = `
        <div class="col-span-6 relative">
            <input
                type="text"
                id="dokumen-${dokumenCounter}"
                name="nama_dokumen[]" 
                placeholder="Masukkan dokumen"
                class="syarat_dokumen col-span-2 w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                oninput="fetchDokumenTags(${dokumenCounter})"
                onkeydown="handleDokumenKeydown(event, ${dokumenCounter})"
            />
            <div id="syarat-suggestions-dokumen-${dokumenCounter}" 
                    class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
        </div>
        <div class="col-span-5">
            <label for="unggah-${dokumenCounter}" class="block text-sm font-medium text-gray-700 mb-1"></label>
            <input
                type="file"
                id="unggah-${dokumenCounter}"
                class="w-1/3 text-gray-500 file:mr-6 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                name="dokumen_file[]" 
                onchange="addDokumenFile(this.files[0], ${dokumenCounter})"
            />
            <span id="dokumen-name-${dokumenCounter}" class="w-2/3 text-gray-500 ml-[-15px] bg-white">Belum ada file yang dipilih</span>
        </div>

        <div class="col-span-1 justify-center flex items-center">
            <div class="bg-red-400 hover:bg-red-600 rounded">
                <button
                    type="button"
                    class="px-3 text-sm font-medium"
                    onclick="removeFormRow(${dokumenCounter})"
                >
                    X
                </button>
            </div>
        </div>
    `;
    formContainer.appendChild(newFormRow);
}

// Fungsi untuk menghapus baris form
function removeFormRow(rowId) {
    const tagText = document.getElementById(`dokumen-${rowId}`).value.trim();
    const link = document.getElementById(`dokumen-name-${rowId}`).getAttribute('data-link');

    if (rowId === 1) {
        document.getElementById(`dokumen-${rowId}`).value = '';
        document.getElementById(`dokumen-${rowId}`).readOnly = false;
        document.getElementById(`dokumen-name-${rowId}`).textContent = 'Belum ada file yang dipilih';
    } else {
        const rowToRemove = document.getElementById(`form-row-${rowId}`);
        if (rowToRemove) {
            rowToRemove.remove();
        }
    }

    // Menghapus data yang terkait
    dokumen_tags = dokumen_tags.filter(tag => tag !== tagText);
    selectedDokumen = selectedDokumen.filter(ulink => ulink !== link);

    console.log(selectedDokumen);
    dokumenCounter--;

    // Update ID dan penomoran baris lainnya
    let rows = document.querySelectorAll('[id^="form-row-"]');
    rows.forEach((row, index) => {
        const newRowId = index + 1; // Penomoran dimulai dari 1
        row.id = `form-row-${newRowId}`;
        
        const dokumenInput = row.querySelector('[id^="dokumen-"]');
        if (dokumenInput) dokumenInput.id = `dokumen-${newRowId}`;
        dokumenInput.addEventListener('input', function(){
            fetchDokumenTags(newRowId);
        })
        dokumenInput.addEventListener('keydown', function(){
            handleDokumenKeydown(event, newRowId);
        })

        const syaratSuggestion = row.querySelector('[id^="syarat-suggestions-dokumen-"]');
        if (syaratSuggestion) syaratSuggestion.id = `syarat-suggestions-dokumen-${newRowId}`;

        const dokumenName = row.querySelector('[id^="dokumen-name-"]');
        if (dokumenName) dokumenName.id = `dokumen-name-${newRowId}`;

        const unggah = row.querySelector('[id^="unggah-"]');
        if (unggah) unggah.id = `unggah-${newRowId}`;
        unggah.addEventListener('change', function(){
            addDokumenFile(this.files[0], newRowId);
        })

        const button = row.querySelector('button[type="button"]');
        button.addEventListener('click', function(){
            removeFormRow(newRowId);
        })
    });
}


// Fungsi untuk menangani keydown pada input dokumen
function handleDokumenKeydown(event, rowId) {
    const inputField = document.getElementById(`dokumen-${rowId}`);
    if (inputField.readOnly) {
        event.preventDefault(); // Block Enter if readonly
        return;
    }

    if (event.keyCode === 13) { // Enter key
        event.preventDefault(); // Block Enter if readonly
        const tagText = inputField.value.trim();
        if (tagText) {
            addDokumenTag(tagText, rowId);
            // Mendapatkan semua elemen dengan atribut data-name
            const elements = document.querySelectorAll('[data-name]');
            elements.forEach(element => {
                if(element.getAttribute('data-name') === tagText) {
                    elementLink = element.getAttribute('data-link-dokumen');
                    addDokumenFile(elementLink, rowId);
                    element.classList.add('hidden');
                }
            })
        }
        // inputField.readOnly = true;
        $(`#syarat-suggestions-dokumen-${rowId}`).empty().addClass('hidden');
    }
}



function showPopup() {
    document.getElementById('popup').classList.remove('hidden');
    loadTemplates(currentPage);
}

function loadTemplates(page) {
    const templateList = document.getElementById('template-list');
    const paginationControls = document.getElementById('pagination-controls');
    
    // Menampilkan indikator loading
    templateList.innerHTML = '<li id="loading-indicator" class="text-center text-gray-600">Memuat template...</li>';

    // Ambil data template dengan paginasi menggunakan fetch
    fetch(`/beasiswa/get-templates?page=${page}`, {
        headers: {
            'Accept': 'application/json', // Memastikan respons berupa JSON
        }})
        .then(response => response.json())
        .then(data => {
            last_page = data.last_page;
            console.log('Response Data:', data); // Debugging keseluruhan respons
            if (!data.data || !Array.isArray(data.data)) {
                console.log('Data content:', data.data); // Debugging hanya bagian data
                throw new Error('Invalid data format: data.data is not an array');
            }
            
            // Bersihkan indikator loading
            templateList.innerHTML = '';
            
            // Menampilkan data template
            data.data.forEach(template => {
                const listItem = document.createElement('li');
                listItem.className = 'p-4 bg-gray-100 rounded-lg flex justify-between items-center';
                listItem.innerHTML = `
                    <div>
                        <p class="text-lg font-medium text-gray-800">${template.nama_beasiswa}</p>
                        <p class="text-sm text-gray-600">${template.deskripsi}</p>
                    </div>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                            onclick="selectTemplate('${template.id}')">
                        Pilih
                    </button>
                `;
                templateList.appendChild(listItem);
            });

            // Menampilkan tombol navigasi halaman
            paginationControls.classList.remove('hidden');
            paginationControls.innerHTML = `
                <button onclick="changePage(${data.current_page - 1})" ${data.current_page === 1 ? 'disabled' : ''} class="px-4 py-2 bg-gray-300 rounded-md"><</button>
                <span class="px-4 py-2">${data.current_page} of ${data.last_page}</span>
                <button onclick="changePage(${data.current_page + 1})" ${data.current_page === data.last_page ? 'disabled' : ''} class="px-4 py-2 bg-gray-300 rounded-md">></button>
            `;
        })
        .catch(error => {
            templateList.innerHTML = '<li class="text-center text-red-600">Gagal memuat template.</li>';
            console.error('Error fetching templates:', error);
        });
}

function changePage(page) {
    if (page < 1 || page > last_page) return; // Cek apakah halaman valid, jangan lupa sesuaikan batas dengan last_page
    currentPage = page;
    const paginationControls = document.getElementById('pagination-controls');
    paginationControls.classList.add('hidden');
    loadTemplates(currentPage);
}

function hidePopup() {
    document.getElementById('popup').classList.add('hidden');
    // document.getElementById('popup-tipe').classList.add('hidden');
}


function cleanInputFields() {
    // Membersihkan input
    document.getElementById('nama_beasiswa').value = "";
    document.getElementById('sumber_beasiswa').value = "";
    document.getElementById('deskripsi').value = "";

    // Set dates
    document.getElementById('tanggal_mulai').value = "";
    document.getElementById('tanggal_berakhir').value = "";
    document.getElementById('kuota_beasiswa').value = "";

    // Process arrays (poster, syarat, dokumen, etc.)
    selectedFiles = [];
    renderPreviews();  

    jenjang_tags = [];
    updateJenjangCounter();
    renderTags('jenjang');
    syarat_tags = [];
    updateBeasiswaCounter();
    renderTags('syarat');
    benefit_tags = [];
    updateBenefitCounter();
    renderTags('benefit');

    // Assuming addBeasiswaTag, addDokumenTag, etc., are functions to process each array

    console.log("Deleting dokumen..");
    console.log(dokumen_tags);
    dokumen_tags.forEach((item, index) => {
        console.log(item, index+1);
        removeFormRow(index+1);  // Assuming this function handles creating new rows for documents
    });
    dokumenCounter = 1;
}

function selectTemplate(templateID) {
    cleanInputFields();
    fetch(`get-beasiswa/${templateID}`)
        .then(response => response.json())
        .then(data => {
            const template = data.beasiswa;  // The main beasiswa data
            
            // Populate form fields
            document.getElementById('nama_beasiswa').value = template.nama_beasiswa;
            document.getElementById('sumber_beasiswa').value = template.sumber;
            document.getElementById('deskripsi').value = template.deskripsi;

            // Set radio buttons for jenis_beasiswa
            document.getElementsByName('jenis_beasiswa').forEach(radio => {
                if (radio.value === template.jenis_beasiswa) {
                    radio.checked = true;
                }
            });
            document.getElementsByName('publish_beasiswa').forEach(radio => {
                if (radio.value == template.publish) {
                    radio.checked = true;
                }
            });
            console.log(template.publish);
            
            // Set radio buttons for tipe_beasiswa
            document.getElementsByName('tipe_beasiswa').forEach(radio => {
                if (radio.value === template.tipe_beasiswa) {
                    radio.checked = true;
                }
                showForm(template.tipe_beasiswa);
            });

            // Set dates
            document.getElementById('tanggal_mulai').value = template.tanggal_mulai;
            document.getElementById('tanggal_berakhir').value = template.tanggal_berakhir;
            document.getElementById('kuota_beasiswa').value = template.kuota;
            document.getElementById('link_beasiswa').value = template.link_beasiswa.link_beasiswa;
            console.log(template.link_beasiswa);
            // Process arrays (poster, syarat, dokumen, etc.)
            data.poster.forEach(poster => {
                selectedFiles.push(poster);
                console.log("existing poster ", selectedFiles);
                renderPreviews(selectedFiles);  // Assuming renderPreviews handles displaying the file
            });

            // Assuming addBeasiswaTag, addDokumenTag, etc., are functions to process each array
            data.syarat.forEach(item => addBeasiswaTag(item));
            console.log("Inserting dokumen");
            console.log(data.dokumen);
            data.dokumen.forEach((item, index) => {
                console.log(item, index + 1);
                addDokumenTag(item, index + 1);
                if (index !== data.dokumen.length - 1) {
                    createFormRow();
                }
            });
            data.link_dokumen.forEach((item, index) => {
                addDokumenFile(item, index + 1);  // Assuming this handles document file rendering
            });
            data.jenjang.forEach(item => addJenjangTag(item));
            data.benefit.forEach(item => addBenefitTag(item));

            // Hide the popup (assuming hidePopup is defined elsewhere)
            hidePopup();
        })
        .catch(error => {
            console.error('Error fetching template:', error);
        });
}


function createHiddenInput() {
    const hiddenContainer = document.getElementById('hidden-input-container');

    // Kosongkan input tersembunyi sebelumnya
    hiddenContainer.innerHTML = '';

    // Tambahkan selectedFiles ke input tersembunyi
    selectedFiles.forEach((file, index) => {
        if (typeof file === "string") {
            // Jika file adalah URL, tambahkan URL
            const hiddenInput = document.createElement('input');
            hiddenInput.name = 'poster[]';
            hiddenInput.type = 'hidden';
            hiddenInput.value = file;
            hiddenContainer.appendChild(hiddenInput);
        }
    });

    selectedDokumen.forEach((file, index) => {
        // Regex untuk validasi URL
        const urlPattern = new RegExp(
            '^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', // fragment locator
            'i'
        );

        if (urlPattern.test(file)) {
            const hiddenInput = document.createElement('input');
            hiddenInput.name = 'link_dokumen[]';
            hiddenInput.type = 'hidden';
            hiddenInput.value = file;
            hiddenContainer.appendChild(hiddenInput);
        } else {
            console.warn(`Invalid URL skipped: ${file}`);
        }

    });

    // dd(selectedFiles);
}
// untuk edit
function loadBeasiswaData(){
    poster.forEach(poster => {
        selectedFiles.push(poster);
        console.log(selectedFiles);
        renderPreviews(selectedFiles);  // Tampilkan pratinjau untuk file yang dipilih
    });
    syarat.forEach(item => addBeasiswaTag(item));
    console.log(dokumen)
    dokumen.forEach((item, index) => {
        addDokumenTag(item, index + 1);
        if (index !== dokumen.length - 1) {
            createFormRow();
        }
    });
    link_dokumen.forEach((item, index) => {
        addDokumenFile(item, index + 1);
    });
    jenjang.forEach(item => addJenjangTag(item));
    benefit.forEach(item => addBenefitTag(item));
}

function showForm(tipe_beasiswa) {
    // Deselect all radio buttons
    document.querySelectorAll('input[name="tipe_beasiswa"]').forEach((elem) => {
        elem.checked = false;
    });

    // Check the corresponding radio button
    document.getElementById(tipe_beasiswa).checked = true;

    // Show and hide sections based on tipe_beasiswa
    if (tipe_beasiswa === 'internal') {
        document.getElementById("beasiswa-internal").classList.remove("hidden");
        document.getElementById("beasiswa-eksternal").classList.add("hidden");
    } else if (tipe_beasiswa === 'eksternal' || tipe_beasiswa === 'kipk') {
        document.getElementById("beasiswa-eksternal").classList.remove("hidden");
        document.getElementById("beasiswa-internal").classList.add("hidden");
    }
}
