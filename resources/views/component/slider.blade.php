<style>
    /* Modern Professional Slider Styles */
    .benefit-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .section-subtitle {
        font-size: 1.125rem;
        color: #64748b;
        font-weight: 400;
    }

    /* Swiper Container */
    .swiper-benefits {
        overflow: visible;
    }

    .swiper-benefits .swiper-slide {
        height: auto;
        display: flex;
        justify-content: center;
    }

    /* Benefit Card */
    .benefit-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 0;
        margin: 0 10px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        min-height: 300px;
        display: flex;
        flex-direction: row;
    }

    .benefit-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .benefit-card:hover::before {
        transform: scaleX(1);
    }

    .benefit-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }

    /* Image Section */
    .benefit-image {
        flex: 0 0 35%;
        position: relative;
        overflow: hidden;
        border-radius: 20px 0 0 20px;
    }

    .benefit-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.4s ease;
    }

    .benefit-card:hover .benefit-image img {
        transform: scale(1.05);
    }

    .benefit-image::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }

    /* Content Section */
    .benefit-content {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .benefit-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        color: white;
        font-size: 24px;
        font-weight: 600;
    }

    .benefit-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 16px;
        line-height: 1.3;
    }

    .benefit-description {
        font-size: 1rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .benefit-highlight {
        background: linear-gradient(135deg, #FFCB25 0%, #ff9f00 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(255, 203, 37, 0.3);
    }

    /* Custom Pagination */
    .swiper-benefits .swiper-pagination {
        bottom: 30px !important;
        text-align: center;
    }

    .swiper-benefits .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: #cbd5e0;
        opacity: 1;
        margin: 0 6px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .swiper-benefits .swiper-pagination-bullet-active {
        background: #FFCB25;
        width: 32px;
        box-shadow: 0 2px 8px rgba(255, 203, 37, 0.4);
    }

    /* Custom Navigation */
    .swiper-benefits .swiper-button-next,
    .swiper-benefits .swiper-button-prev {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 50%;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        color: #667eea !important;
        border: 2px solid rgba(102, 126, 234, 0.1);
    }

    .swiper-benefits .swiper-button-next:after,
    .swiper-benefits .swiper-button-prev:after {
        font-size: 18px;
        font-weight: 700;
    }

    .swiper-benefits .swiper-button-next:hover,
    .swiper-benefits .swiper-button-prev:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        transform: scale(1.1);
        border-color: transparent;
    }

    .swiper-benefits .swiper-button-next {
        right: 20px;
    }

    .swiper-benefits .swiper-button-prev {
        left: 20px;
    }

    /* Progress Bar */
    .progress-container {
        margin-top: 30px;
        text-align: center;
    }

    .progress-bar {
        width: 250px;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        margin: 0 auto 16px auto;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #FFCB25 0%, #ff9f00 100%);
        border-radius: 3px;
        transition: width 0.4s ease;
    }

    .progress-text {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Default Image Placeholder */
    .image-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        font-weight: 300;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .section-title {
            font-size: 2rem;
        }

        .benefit-card {
            flex-direction: column;
            margin: 0 5px;
            min-height: 400px;
        }

        .benefit-image {
            flex: 0 0 40%;
            border-radius: 20px 20px 0 0;
        }

        .benefit-content {
            padding: 30px 24px;
        }

        .benefit-title {
            font-size: 1.25rem;
        }

        .swiper-benefits .swiper-button-next,
        .swiper-benefits .swiper-button-prev {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .benefit-container {
            padding: 20px 10px;
        }

        .benefit-content {
            padding: 24px 20px;
        }
    }
</style>

@if ($isBenefit)
    <div class="benefit-container">
        <div class="section-header">
            <h2 class="section-title">Manfaat Beasiswa</h2>
            <p class="section-subtitle">Temukan peluang menakjubkan yang menanti Anda</p>
        </div>

        <div class="swiper swiper-benefits">
            <div class="swiper-wrapper">
                @foreach ($beasiswa->benefitBeasiswa as $bf)
                    <div class="swiper-slide">
                        <div class="benefit-card flex ">
                            <div class="benefit-image">
                                <img src="{{ $bf->image ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=500&h=300&fit=crop' }}"
                                     alt="{{ $bf->benefit }}"
                                     onerror="this.parentElement.innerHTML='<div class=\'image-placeholder\'>📚</div>'">
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-title">{{ $bf->benefit }}</h3>
                                <p class="benefit-description">{{ $bf->deskripsi_benefit }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>

        <!-- Progress Indicator -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" style="width: 20%;"></div>
            </div>
            <p class="progress-text">
                <span id="current-slide">1</span> dari <span id="total-slides">{{ count($beasiswa->benefitBeasiswa) }}</span> manfaat
            </p>
        </div>
    </div>
@else
    <div class="benefit-container">
        <div class="section-header">
            <h2 class="section-title">Persyaratan Beasiswa</h2>
            <p class="section-subtitle">Ketahui persyaratan yang diperlukan</p>
        </div>

        <div class="swiper swiper-benefits">
            <div class="swiper-wrapper">
                @foreach ($beasiswa->benefitBeasiswa as $syarat)
                    <div class="swiper-slide">
                        <div class="benefit-card">
                            <div class="benefit-image">
                                <img src="{{ $syarat->image ?? 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=500&h=300&fit=crop' }}"
                                     alt="{{ $syarat->benefit }}"
                                     onerror="this.parentElement.innerHTML='<div class=\'image-placeholder\'>📋</div>'">
                            </div>
                            <div class="benefit-content">
                                <div class="benefit-icon">
                                    {{ $syarat->icon ?? '📋' }}
                                </div>
                                <h3 class="benefit-title">{{ $syarat->benefit }}</h3>
                                <p class="benefit-description">{{ $syarat->deskripsi_benefit ?? 'Persyaratan yang harus dipenuhi untuk mendaftar beasiswa ini.' }}</p>
                                <span class="benefit-highlight">Wajib</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>

        <!-- Progress Indicator -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" style="width: 20%;"></div>
            </div>
            <p class="progress-text">
                <span id="current-slide">1</span> dari <span id="total-slides">{{ count($beasiswa->benefitBeasiswa) }}</span> persyaratan
            </p>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Initialize Swiper
    const swiperBenefits = new Swiper('.swiper-benefits', {
        slidesPerView: 1,
        centeredSlides: true,
        spaceBetween: 20,
        loop: true,
        speed: 800,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.swiper-benefits .swiper-pagination',
            clickable: true,
            type: 'bullets',
        },
        navigation: {
            nextEl: '.swiper-benefits .swiper-button-next',
            prevEl: '.swiper-benefits .swiper-button-prev',
        },
        effect: 'slide',
        grabCursor: true,
        breakpoints: {
            640: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 1,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 1,
                spaceBetween: 40,
            }
        }
    });

    // Update progress bar and counter
    function updateProgress() {
        const totalSlides = document.querySelectorAll('.swiper-benefits .swiper-slide').length;
        const currentIndex = swiperBenefits.realIndex;
        const progressPercentage = ((currentIndex + 1) / totalSlides) * 100;

        const progressFill = document.querySelector('.progress-fill');
        const currentSlideSpan = document.getElementById('current-slide');
        const totalSlidesSpan = document.getElementById('total-slides');

        if (progressFill) progressFill.style.width = progressPercentage + '%';
        if (currentSlideSpan) currentSlideSpan.textContent = currentIndex + 1;
        if (totalSlidesSpan) totalSlidesSpan.textContent = totalSlides;
    }

    // Update progress on slide change
    swiperBenefits.on('slideChange', updateProgress);

    // Initialize progress
    updateProgress();

    // Pause autoplay on hover
    const swiperContainer = document.querySelector('.swiper-benefits');

    if (swiperContainer) {
        swiperContainer.addEventListener('mouseenter', () => {
            swiperBenefits.autoplay.stop();
        });

        swiperContainer.addEventListener('mouseleave', () => {
            swiperBenefits.autoplay.start();
        });
    }
</script>
