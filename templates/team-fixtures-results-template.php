<div id="fixtures-table" class="table table-games" data-season="<?php echo $season; ?>" data-league_id="<?php echo $league_id; ?>">
    <div class="table-row table-title">Results & Fixtures</div>
    <?php
        foreach($results_data as $timestamp => $rounds) {
            foreach ($rounds as $round => $results) {
    ?>
                <div class="table-row"><?php echo date('D d M, Y', $timestamp) . ', Round ' . $round; ?></div>
                    <?php foreach ($results as $result): ?>
                        <div class="table-row">
                            <div class="fixtures-team fixtures-team__home table-container-cell table-container-cell--long"><?php echo get_team_name($result->teams->home->name); ?></div>
                            <?php if( $result->goals->home !== NULL && $result->goals->away !== NULL ): ?>
                                <div class="fixtures-score table-container-cell table-container-cell--short"><div class="score-wrap"><?php echo $result->goals->home . ' - ' . $result->goals->away; ?></div></div>
                            <?php else: ?>
                                <div class="fixtures-score table-container-cell table-container-cell--short"><?php echo date('H:i', strtotime($result->fixture->date)); ?></div>
                            <?php endif; ?>
                            <div class="fixtures-team fixtures-team__away table-container-cell table-container-cell--long"><?php echo get_team_name($result->teams->away->name); ?></div>
                        </div>
                    <?php endforeach; ?>
    <?php
            }
        }
    ?>
    <?php if( $atts['short'] == 'true' ): ?>
        <a href="full-table" class="full">View full table</a>
    <?php endif; ?>
</div>