<div class="tables-updated-part">
    <div class="table-row table-head" style="background-color: #e9e9e9;">
        <?php echo date('D d M, Y', strtotime($table_data->parameters->date)) . ', Round '. str_replace ('Regular Season - ', '', $table_data->response[0]->league->round); ?>
    </div>
    <div  class="table-container">
        <?php
        if( empty($table_data->response) ){ ?>
            <div class="table-row">
                <div class="table-container-cell table-container-cell--wide fixtires-no-matches">No matches found for the selected date</div>
            </div>
            <?php
        } else {
            foreach($table_data->response as $match_data) { ?>
                <?php
                    $status = $match_data->fixture->status->short;
                    $fixture_id = $match_data->fixture->id;
                    $timestamp = $match_data->fixture->timestamp;

                    $goals = [];

                    if($status != 'FT'){
                        $status = '';
                    }
                    
                    if( $scorers == 'true' && $timestamp < strtotime(gmdate('Y-m-d H:i:s')) ){
                        $transient_name = 'fixtures_' . $fixture_id;
                        $get_match_data = get_transient( $transient_name );
                        if ( false === $get_match_data ) {
                            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures/events?fixture=' . $fixture_id, array(
                                'timeout'   => 20,
                                'headers'   => $this->headers,
                            ));

                            if( $status == 'FT' ) {
	                            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), 30*DAY_IN_SECONDS );
                            }
                            $get_match_data = json_decode($get_remote_data['body']);
                        }
                        $fixture_events = $get_match_data->response;
                        foreach ($fixture_events as $event) {
                            $event_type = $event->type;
                            if( $event_type == 'Goal' && $event->detail != 'Missed Penalty' ){
                                $team_id = $event->team->id;
                                $goal_data = [];
                                $goal_data['minute'] = $event->time->elapsed;
                                $goal_data['player'] = $event->player->name;
                                if( $event->detail != 'Normal Goal' ){
                                    $goal_data['detail'] = ' (' . str_replace('own', 'OG',strtolower(substr($event->detail,0, 3)) . ')');
                                    if( $goal_data['detail'] == ' (pen)' ){
	                                    $goal_data['detail'] = ' (P)';
                                    }
                                } else {
                                    $goal_data['detail'] = '';
                                }
                                $goals[$team_id][] = $goal_data;
                            }
                        }
                    }
	            $home_team_id = $match_data->teams->home->id;
	            $away_team_id = $match_data->teams->away->id;
                $container_class = ((isset($goals[$home_team_id]) && is_array($goals[$home_team_id]) && !empty($goals[$home_team_id])) || (isset($goals[$away_team_id]) && is_array($goals[$away_team_id]) && !empty($goals[$away_team_id]))) ? 'with-goals' : '';
                ?>
                <div class="table-row <?php echo $container_class; ?>">
                    <div class="fixtures-team fixtures-team__home table-container-cell table-container-cell--long">
                        <?php
                            echo get_team_name($match_data->teams->home->name);
                            if ( $scorers == 'true' ) {

                                if( isset($goals[$home_team_id]) && is_array($goals[$home_team_id]) && !empty($goals[$home_team_id])) {
                                    foreach($goals[$home_team_id] as $goal_details) {
                                        echo '<p class="goal-details">' . str_replace(' ', '&nbsp;',  $goal_details['player'] ) . ',&nbsp;' . $goal_details['minute'] . $goal_details['detail'] . '</p>';
                                    }
                                }
                            }
                        ?>
                    </div>

                    <?php if( $match_data->goals->home !== NULL && $match_data->goals->away !== NULL ): ?>
                        <div class="fixtures-score table-container-cell table-container-cell--short"><div class="score-wrap"><?php echo $match_data->goals->home . ' - ' . $match_data->goals->away; ?></div></div>
                    <?php else: ?>
                    <div class="fixtures-score table-container-cell table-container-cell--short"><?php echo date('H:i', strtotime($match_data->fixture->date)); ?></div>
                    <?php endif; ?>

                    <div class="fixtures-team fixtures-team__away table-container-cell table-container-cell--long">
                        <?php echo get_team_name($match_data->teams->away->name); ?>
                        <?php
                            if( $scorers == 'true' ):
                                if( isset($goals[$away_team_id]) && is_array($goals[$away_team_id]) && !empty($goals[$away_team_id])) {
                                    foreach($goals[$away_team_id] as $goal_details) {
                                        echo '<p class="goal-details">' . $goal_details['player'] . ', ' . $goal_details['minute'] . $goal_details['detail'] . '</p>';
                                    }
                                }
                        ?>
                            <div class="match-status"><?php echo $status; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php }
        } ?>
    </div>
</div>