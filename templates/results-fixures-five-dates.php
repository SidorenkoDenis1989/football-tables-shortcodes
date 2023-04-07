<div id="fixtures-table" class="table table-games" data-season="<?php echo $season; ?>" data-league_id="<?php echo $league_id; ?>" data-scorers="<?php echo $scorers; ?>">
    <div class="table-row table-title">Results & Fixtures</div>
    <?php if( !empty($fixtures_dates) ): ?>
        <div class="tabs-nav">
            <?php foreach ($fixtures_dates as $timestamp): ?>
                <?php
                if($timestamp == $actual_date_timestamp){
                    $match_date_class = 'selected-date';
                } else {
                    $match_date_class = '';
                }
                ?>
                <div class="matches-date <?php echo $match_date_class; ?>" data-date="<?php echo date('Y-m-d', $timestamp); ?>">
                    <p><?php echo date('D', $timestamp); ?></p>
                    <p><?php echo date('M d', $timestamp); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php include 'fixtures-calendar-matches.php'; ?>
    <?php if( $atts['page_id'] != '' ): ?>
         <a style="margin-top: -20px" href="<?php echo get_the_permalink($atts['page_id']); ?>" class="full">View full table</a>
    <?php endif; ?>
</div>