<?php
    $table_data = get_option('football_teams_data');
    $table_all_data = get_option('football_teams_all_teams_data');
    $teams = $table_data->response;
    $all_teams = $table_all_data->response;
    if( is_array($teams) && !empty($teams) ):
?>
    <table border="1px">
        <thead>
            <tr>
                <th>Team</th>
                <th>ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teams as $team): ?>
                <tr>
                    <td><?php echo $team->team->name; ?></td>
                    <td><?php echo $team->team->id; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php
    endif;
    if( is_array($all_teams) && !empty($all_teams) ):
?>
    <table border="1px">
        <thead>
        <tr>
            <th>Team</th>
            <th>ID</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($all_teams as $team): ?>
            <tr>
                <td><?php echo $team->team->name; ?></td>
                <td><?php echo $team->team->id; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php
    endif;