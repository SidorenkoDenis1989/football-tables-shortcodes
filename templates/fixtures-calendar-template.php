<div id="fixtures-table" class="football-table-wrap table table-games" data-season="<?php echo $season; ?>" data-league_id="<?php echo $league_id; ?>" data-scorers="<?php echo $scorers; ?>">
    <div class="table-row table-title">
        Results & Fixtures
    </div>
    <?php if( !empty($fixtures_dates) ): ?>
        <?php
            if($slider == 'true'){
                $slider_class = 'football-calendar-slider';
            } else {
                $slider_class = '';
            }
        ?>
        <div class="swiper-container mobile-calendar-wrap">
            <div class="swiper-wrapper">
                <?php foreach ($fixtures_dates as $key => $timestamp): ?>
                <?php
                    if($timestamp == $actual_date_timestamp){
                        $match_date_class = 'selected-date';
                        $current_slide = $key;
                    } else {
                        $match_date_class = '';
                        $current_slide = '';
                    }
                ?>
                    <div class="swiper-slide matches-date <?php echo $match_date_class; ?>" data-current-slide="<?php echo $current_slide; ?>" data-date="<?php echo date('Y-m-d', $timestamp); ?>">
                        <p><?php echo date('D', $timestamp); ?></p>
                        <p><?php echo date('M d', $timestamp); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        <script src="<?php echo __TABLESURL__; ?>assets/swiper/swiper-bundle.min.js"></script>
        <script type="text/javascript">
            var swiper = new Swiper('.swiper-container', {
                slidesPerView: 11,
                slidesPerGroup: 1,
                spaceBetween: 0,
                centeredSlides: true,
                initialSlide: parseInt(jQuery('.mobile-calendar-wrap').find('.selected-date').attr('data-current-slide')),
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    // when window width is >= 320px
                    320: {
                        slidesPerGroup: 1,
                        slidesPerView: 3,
                    },
                    // when window width is >= 480px
                    480: {
                        slidesPerView: 5,
                    },
                    // when window width is >= 640px
                    640: {
                        slidesPerView: 7,
                    },
                    768: {
                        slidesPerView: 9,
                    },
                    1024: {
                        slidesPerView: 11,
                    }
                }
            });
        </script>
    <?php endif; ?>
    <?php include 'fixtures-calendar-matches.php'; ?>
</div>
