<div class="table table-results">
    <div class="table-row table-title"><?php echo $standing_header . ' table'; ?></div>
    <div class="table-row table-head">
        <span>#</span>
        <span>Team</span>
        <span>PL</span>
        <span>GD</span>
        <span>PT</span>
    </div>

        <?php
            if( $atts['short'] == 'true' && count($standing_data) > 10 ) {
                for($i = 0; $i < 10; $i++) { ?>
                    <div class="table-row">
                        <span><?php echo $standing_data[$i]->rank; ?></span>
                        <span><strong><?php echo get_team_name($standing_data[$i]->team->name); ?></strong></span>
                        <span><?php echo $standing_data[$i]->all->played; ?></span>
                        <span><?php echo $standing_data[$i]->goalsDiff; ?></span>
                        <span><?php echo $standing_data[$i]->points; ?></span>
                    </div>
                <?php } ?>
                <?php if( $atts['page_id'] != ''): ?>
                    <a class="full" href="<?php echo get_the_permalink($atts['page_id']); ?>">View full table</a>
                <?php endif; ?>
        <?php
            } else {
                foreach($standing_data as $team_data) { ?>
            <div class="table-row">
                    <span><?php echo $team_data->rank; ?></span>
                    <span><strong><?php echo get_team_name($team_data->team->name); ?></strong></span>
                    <span><?php echo $team_data->all->played; ?></span>
                    <span><?php echo $team_data->goalsDiff; ?></span>
                    <span><?php echo $team_data->points; ?></span>
            </div>
            <?php }
            } ?>
</div>