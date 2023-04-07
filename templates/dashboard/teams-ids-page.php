<?php

    if( get_option('football_teams_ids_season') ){
        $season = get_option('football_teams_ids_season');
    } else {
        $season = '';
    }

    if( get_option('football_teams_ids_league') ){
        $league = get_option('football_teams_ids_league');
    } else {
        $league = '';
    }

?>
<div class="wrap">
    <h1 class="wp-heading-inline">Get teams IDs</h1>
    <div class="page-wrap">
        <div class="left">
            <div class="teams-ids-request-parameter" style="margin-top: 30px;">
                <div class="teams-ids-request-parameter-title">
                    <p>League</p>
                </div>
                <div class="teams-ids-request-parameter-value">
                    <input type="number"step="1" name="teams_ids_league" required value="<?php echo $league ?>">
                    <p>Integer. The id of the league. Championship ID = 40</p>
                </div>
            </div>
            <div class="teams-ids-request-parameter">
                <div class="teams-ids-request-parameter-title">
                    <p>Season</p>
                </div>
                <div class="teams-ids-request-parameter-value">
                    <input type="number" minlength="4" maxlength="4" step="1" name="teams_ids_season" required value="<?php echo $season; ?>">
                    <p>Integer. Format YYYY. The season of the league. All seasons are only 4-digit keys, so for a league whose season is 2018-2019 like the English Premier League (EPL), the 2018-2019 season in the API will be 2018.</p>
                </div>
            </div>
            <div class="teams-ids-submit-wrap">
                <a href="#" class="teams-ids-submit">Get IDs</a>
            </div>
            <div class="teams-ids-table">
                <?php include 'template-parts/teams-ids-table.php'; ?>
            </div>
        </div>
    </div>
</div>