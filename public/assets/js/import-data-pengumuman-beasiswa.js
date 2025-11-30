const dropArea = document.getElementById('dropArea');
const fileInput = document.getElementById('fileInput');
const fileList = document.getElementById('fileList');
const noFilesMessage = document.getElementById('noFilesMessage');

// Highlight drop area on drag over
dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropArea.classList.add('bg-gray-200');
});

// Remove highlight on drag leave
dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('bg-gray-200');
});

// Handle file drop
dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dropArea.classList.remove('bg-gray-200');
    const files = e.dataTransfer.files;
    handleFiles(files);
});

// Open file input on click
dropArea.addEventListener('click', () => {
    fileInput.click();
});

// Handle file input change
fileInput.addEventListener('change', (e) => {
    const files = e.target.files;
    handleFiles(files);
});

// Display attached files
function handleFiles(files) {
    noFilesMessage.style.display = 'none';
    fileList.innerHTML = '';
    Array.from(files).forEach((file) => {
        const listItem = document.createElement('p');
        listItem.textContent = file.name;
        listItem.classList.add('truncate', 'mt-2', 'text-center');
        fileList.appendChild(listItem);
    });
}
