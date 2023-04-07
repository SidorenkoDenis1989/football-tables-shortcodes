<div class="table table-games">
    <div class="table-row table-title">Results &amp; Fixtures</div>
    <ul class="tabs-nav">
        <?php
        foreach($results_data as $timestamp => $rounds) {
        krsort($rounds);
        foreach ($rounds as $round => $results) {
        ?>
        <li><a href="javascript:;" data-tab-name="tab<?=$round;?>">Wed <span>Dec 16</span></a></li>
            <?php
        }
        }
        ?>
    </ul>
    <div class="tabs-content">
        <div class="tab" id="tab1">
            <div class="table-row table-head">Wed 16 Dec, Round 18</div>
            <div class="table-row"><span>Middlesbrough</span><span>15:00</span><span>Middlesbrough</span>
            </div>
            <div class="table-row"><span>Middlesbrough</span><span>15:00</span><span>Middlesbrough</span>
            </div>
            <div class="table-row"><span>Middlesbrough</span><span>15:00</span><span>Middlesbrough</span>
            </div>
            <div class="table-row"><span>Middlesbrough</span><span>15:00</span><span>Middlesbrough</span>
            </div>
            <div class="table-row"><span>Middlesbrough</span><span>15:00</span><span>Middlesbrough</span>
            </div>
        </div>
        <div class="tab" id="tab2">
            <div class="table-row table-head">Thu 17 Dec, Round 18</div>
            <div class="table-row">
                <span>Watford</span><span>16:00</span><span>Watford</span></div>
            <div class="table-row">
                <span>Watford</span><span>16:00</span><span>Watford</span></div>
            <div class="table-row">
                <span>Watford</span><span>16:00</span><span>Watford</span></div>
            <div class="table-row">
                <span>Watford</span><span>16:00</span><span>Watford</span></div>
            <div class="table-row">
                <span>Watford</span><span>16:00</span><span>Watford</span></div>
        </div>
        <div class="tab tab--active" id="tab3">
            <div class="table-row table-head">Fri 18 Dec, Round 18</div>
            <div class="table-row">
                <span>Luton Town</span><span>18:00</span><span>Luton Town</span></div>
            <div class="table-row">
                <span>Luton Town</span><span>18:00</span><span>Luton Town</span></div>
            <div class="table-row">
                <span>Luton Town</span><span>18:00</span><span>Luton Town</span></div>
            <div class="table-row">
                <span>Luton Town</span><span>18:00</span><span>Luton Town</span></div>
            <div class="table-row">
                <span>Luton Town</span><span>18:00</span><span>Luton Town</span></div>
        </div>
        <div class="tab" id="tab4">
            <div class="table-row table-head">Sat 19 Dec, Round 18</div>
            <div class="table-row"><span>Birmingham City</span><span>20:00</span><span>Birmingham City</span>
            </div>
            <div class="table-row"><span>Birmingham City</span><span>20:00</span><span>Birmingham City</span>
            </div>
            <div class="table-row"><span>Birmingham City</span><span>20:00</span><span>Birmingham City</span>
            </div>
            <div class="table-row"><span>Birmingham City</span><span>20:00</span><span>Birmingham City</span>
            </div>
            <div class="table-row"><span>Birmingham City</span><span>20:00</span><span>Birmingham City</span>
            </div>
        </div>
        <div class="tab" id="tab5">
            <div class="table-row table-head">Sun 20 Dec, Round 18</div>
            <div class="table-row">
                <span>Bournemouth</span><span>14:00</span><span>Bournemouth</span>
            </div>
            <div class="table-row">
                <span>Bournemouth</span><span>14:00</span><span>Bournemouth</span>
            </div>
            <div class="table-row">
                <span>Bournemouth</span><span>14:00</span><span>Bournemouth</span>
            </div>
            <div class="table-row">
                <span>Bournemouth</span><span>14:00</span><span>Bournemouth</span>
            </div>
            <div class="table-row">
                <span>Bournemouth</span><span>14:00</span><span>Bournemouth</span>
            </div>
        </div>
    </div>

    <a class="full" href="javascript:;">View full table</a>
</div>
<!--<div id="fixtures-table" class="football-table-wrap" data-season="--><?php //echo $season; ?><!--" data-league_id="--><?php //echo $league_id; ?><!--">-->
<!--    <div class="standing-header"><h3>Results</h3></div>-->
<!--    --><?php
//        foreach($results_data as $timestamp => $rounds) {
//            krsort($rounds);
//            foreach ($rounds as $round => $results) {
//    ?>
<!--                <div class="result-date-round">--><?php //echo date('D d M, Y', $timestamp) . ', Round ' . $round; ?><!--</div>-->
<!--                <div class="table-container">-->
<!--                    --><?php //foreach ($results as $result): ?>
<!--                        <div class="table-container-row">-->
<!--                            <div class="fixtures-team fixtures-team__home table-container-cell table-container-cell--long">--><?php //echo $result->teams->home->name; ?><!--</div>-->
<!--                            --><?php //if( $result->goals->home !== NULL && $result->goals->away !== NULL ): ?>
<!--                                <div class="fixtures-score table-container-cell table-container-cell--short"><div class="score-wrap">--><?php //echo $result->goals->home . ' - ' . $result->goals->away; ?><!--</div></div>-->
<!--                            --><?php //else: ?>
<!--                                <div class="fixtures-score table-container-cell table-container-cell--short">--><?php //echo date('H:i', strtotime($result->fixture->date)); ?><!--</div>-->
<!--                            --><?php //endif; ?>
<!--                            <div class="fixtures-team fixtures-team__away table-container-cell table-container-cell--long">--><?php //echo $result->teams->away->name; ?><!--</div>-->
<!--                        </div>-->
<!--                    --><?php //endforeach; ?>
<!--                </div>-->
<!--    --><?php
//            }
//        }
//    ?>
<!--    --><?php //if( $atts['page_id'] != '' ): ?>
<!--        <div class="football-table-link table-container-row">-->
<!--            <div class="table-container-cell table-container-cell--wide"><a href="--><?php //echo get_the_permalink($atts['page_id']); ?><!--" class="view-full-table">View full table</a></div>-->
<!--        </div>-->
<!--    --><?php //endif; ?>
<!--</div>-->