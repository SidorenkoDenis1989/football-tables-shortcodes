jQuery(document).ready(function($){
    $( "#fixtures-date" ).datepicker({
        dateFormat: 'yy-mm-dd',
        showOn: "button",
        buttonText: "Date",
        onSelect: function(selected,evnt) {
            getFixturesTable(selected);
        }
    });

    $('.matches-date').on('click', function(e){
        if(!$(this).hasClass('selected-date')) {
            $('.matches-date').removeClass('selected-date');
            $(this).addClass('selected-date');
            e.preventDefault();
            let date = $(this).attr('data-date');
            getFixturesTable(date);
        }

    });

    function getFixturesTable(date) {
        let scorers = $('#fixtures-table').attr('data-scorers');
        $.ajax({
            url: footballTables.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action      : 'get_fixtures_data',
                date        : date,
                league_id   : $('#fixtures-table').attr('data-league_id'),
                season      : $('#fixtures-table').attr('data-season'),
                scorers      : scorers,
            },
            beforeSend : function ( xhr ) {
                $('#fixtures-table').append('<div class="loading"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>');
            },
            success: (response) => {
                $('.tables-updated-part').replaceWith(response.table_html);

                $( "#fixtures-date" ).datepicker({
                    dateFormat: 'yy-mm-dd',
                    defaultDate: date,
                    showOn: "button",
                    buttonText: "Date",
                    onSelect: function(selected,evnt) {
                        getFixturesTable(selected);
                    }
                });
                $('.loading').detach();
            }
        });
    }

    $('body').on('click', '#fixtures-table .table-row.with-goals', function (){
        $(this).toggleClass('active');
    });
});