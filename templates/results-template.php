<div id="fixtures-table" class="football-table-wrap" data-season="<?php echo $season; ?>" data-league_id="<?php echo $league_id; ?>">
    <div class="standing-header"><h3>Results</h3></div>
    <?php
        foreach($results_data as $timestamp => $rounds) {
            krsort($rounds);
            foreach ($rounds as $round => $results) {
    ?>
                <div class="result-date-round"><?php echo date('D d M, Y', $timestamp) . ', Round ' . $round; ?></div>
                <div class="table-container">
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
                </div>
    <?php
            }
        }
    ?>
    <?php if( $atts['page_id'] != '' ): ?>
        <div class="football-table-link table-container-row">
            <div class="table-container-cell table-container-cell--wide"><a href="<?php echo get_the_permalink($atts['page_id']); ?>" class="view-full-table">View full table</a></div>
        </div>
    <?php endif; ?>
</div>