<style>
    /* Custom styles for pagination */
    .swiper-pagination {
        bottom: 10px;
        /* Adjust the position */
    }

    .swiper-pagination-bullet {
        background: gray;
        /* Change bullet color */
        opacity: 1;
        /* Fully visible */
        width: 12px;
        /* Bullet width */
        height: 12px;
        /* Bullet height */
        margin: 0 4px;
        /* Spacing between bullets */
        border-radius: 50%;
        /* Make bullets circular */
    }

    .swiper-pagination-bullet-active {
        background: #FFCB25;
        /* Active bullet color */
    }

    /* Custom styles for navigation buttons */
    .swiper-button-next,
    .swiper-button-prev {
        color: #fff;
        /* Change button text color */
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent background */
        border-radius: 50%;
        /* Rounded buttons */
        width: 40px;
        /* Button width */
        height: 40px;
        /* Button height */
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px;
        /* Change icon size */
    }

    .swiper-button-next {
        right: 10px;
        /* Position the next button */
    }

    .swiper-button-prev {
        left: 10px;
        /* Position the previous button */
    }

    /* Optional: Change hover effect */
    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background-color: rgba(0, 0, 0, 0.8);
        /* Darker background on hover */
    }

    .swiper-1 {
        max-width: 600px;
    }
</style>

<div class="swiper swiper-1 p-0 m-0">
    <div class="swiper-wrapper p-0 m-0">
        @foreach ($beasiswa->benefitBeasiswa as $syarat)
            <div class="swiper-slide flex justify-center ">
                <div>
                    <div class="max-w-60">
                        <img src="https://th.bing.com/th/id/OIP.1BCK9JuP0oN4CjMJx2urnQAAAA?rs=1&pid=ImgDetMain" class="max-w-full"
                            alt="Wild Landscape" />
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="swiper-pagination swiper-pagination-2"></div>


</div>

<!-- Display Container END -->

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper-1', {
        slidesPerView: 1,
        centeredSlides: true,
        loop: true,
        spaceBetween: 10,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination-2',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next-2',
            prevEl: '.swiper-button-prev-2',
        },
    });

    window.swiperInstance = swiper;
</script>
