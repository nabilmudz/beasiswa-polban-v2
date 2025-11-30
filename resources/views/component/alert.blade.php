<!-- Alert Overlay -->
<div id="alertOverlay" class="hidden fixed top-4 right-4 z-50">
    <div id="alertPopup"
        class="w-80 bg-white shadow-lg rounded-lg border border-gray-200 flex items-center p-4 transform transition-all duration-300 ease-in-out translate-x-full">
        <!-- Icon -->
        <div id="alertIcon" class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600">
            <i class="fas fa-check-circle text-2xl"></i>
        </div>
        <!-- Text Content -->
        <div class="ml-4 flex-grow">
            <p id="alertTitle" class="font-semibold text-lg text-gray-800">Success</p>
            <p id="alertMessage" class="text-sm text-gray-500">Action completed successfully.</p>
        </div>
        <!-- Close Button -->
        <button id="alertClose" class="text-gray-400 hover:text-gray-600 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
