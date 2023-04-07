<div class="table table-results table-scores">
    <div class="table-row table-title">Top scorers</div>
    <div class="table-row table-head">
        <span>#</span>
        <span>Player</span>
        <span>Team</span>
        <span>App.</span>
        <span>Goals</span>
    </div>
        <?php
        if( $atts['short'] == 'true' && count($table_data) > 10 ) {
            for($i = 0; $i < 5; $i++) { ?>
                <div class="table-row">
                    <span><?php echo $i + 1; ?></span>
                    <span><?php echo $table_data[$i]->player->name; ?></span>
                    <span><?php echo $table_data[$i]->statistics[0]->team->name; ?></span>
                    <span><?php echo $table_data[$i]->statistics[0]->games->appearences; ?></span>
                    <span><?php echo $table_data[$i]->statistics[0]->goals->total; ?></span>
                </div>
            <?php } ?>
            <?php if( $atts['page_id'] != ''): ?>
                <a class="full" href="<?php echo get_the_permalink($atts['page_id']); ?>" class="view-full-table">View full table</a>
            <?php endif;
        } else {
            $i = 1;
            foreach($table_data as $player_data) { ?>
                <div class="table-row">
                    <span><?php echo $i; ?></span>
                    <span><strong><?php echo $player_data->player->name; ?></strong></span>
                    <span><?php echo $player_data->statistics[0]->team->name; ?></span>
                    <span><?php echo $player_data->statistics[0]->games->appearences; ?></span>
                    <span><?php echo $player_data->statistics[0]->goals->total; ?></span>
                </div>
                <?php $i++;
            }
        } ?>
</div>
