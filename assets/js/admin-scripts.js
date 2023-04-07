jQuery(document).ready(function ($){
    $('.teams-ids-submit').on('click', function (e){
        e.preventDefault();

        $('input[name="teams_ids_league"]').removeClass('invalid');
        $('input[name="teams_ids_season"]').removeClass('invalid');

        let league_id = $('input[name="teams_ids_league"]').val();
        let season = $('input[name="teams_ids_season"]').val();
        let errors = 0;
        if( league_id == '' || season == '' || season.length != 4 ) {
            errors++;
        }
        if( league_id == '' ) {
            $('input[name="teams_ids_league"]').addClass('invalid');
        }

        if( season == '' || season.length != 4 ) {
            $('input[name="teams_ids_season"]').addClass('invalid');
        }
        if( errors == 0 ){
            $.ajax({
                url: footballTables.ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action      : 'get_teams_ids',
                    league_id   : league_id,
                    season      : season,
                },
                beforeSend : function ( xhr ) {
                    $('.teams-ids-submit').after('<div class="loading"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>');
                },
                success: (response) => {
                    $('.teams-ids-table').html(response.table_html);
                    $('.loading').detach();
                }
            });
        }
        console.log(errors);
    });
});