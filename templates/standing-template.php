<div class="football-table-wrap">
    <div class="standing-header"><h3><?php echo $standing_header . ' table'; ?></h3></div>
    <table border="0px">
        <thead>
            <tr>
                <td>#</td>
                <td>Team</td>
                <td>PL</td>
                <td>GD</td>
                <td>PT</td>
            </tr>
        </thead>
        <tbody>
        <?php
            if( $atts['short'] == 'true' && count($standing_data) > 10 ) {
                for($i = 0; $i < 10; $i++) { ?>
                    <tr>
                        <td><?php echo $standing_data[$i]->rank; ?></td>
                        <td><?php echo $standing_data[$i]->team->name; ?></td>
                        <td><?php echo $standing_data[$i]->all->played; ?></td>
                        <td><?php echo $standing_data[$i]->goalsDiff; ?></td>
                        <td><?php echo $standing_data[$i]->points; ?></td>
                    </tr>
                <?php } ?>
                <?php if( $atts['page_id'] != ''): ?>
                    <tr class="footbal-table-link">
                        <td colspan="5"><a href="<?php echo get_the_permalink($atts['page_id']); ?>" class="view-full-table">View full table</a></td>
                    </tr>
                <?php endif; ?>
        <?php
            } else {
                foreach($standing_data as $team_data) { ?>
                <tr>
                    <td><?php echo $team_data->rank; ?></td>
                    <td><?php echo $team_data->team->name; ?></td>
                    <td><?php echo $team_data->all->played; ?></td>
                    <td><?php echo $team_data->goalsDiff; ?></td>
                    <td><?php echo $team_data->points; ?></td>
                </tr>
            <?php }
            } ?>
        </tbody>
    </table>
</div>