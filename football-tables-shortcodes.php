<?php

/**
 * Plugin Name:       Football tables shortcodes
 * Description:       Use shortcodes [сhampionship_table], [fixtures_calendar_table], [scorers_table], [fixtures_table], [results_table]. You can use shortcode`s parameter <strong>"season"</strong>. All seasons are only 4-digit keys, so for a league whose season is 2018-2019 like the English Premier League (EPL), the 2018-2019 season in the API will be 2018. For example <strong>season="2020"</strong>. For shortcodes [сhampionship_table],  [scorers_table], [fixtures_table], [results_table] you can use parameter <strong>short="true"</strong> to show short table. You can use parameter <strong>"page_id"</strong> to show page link in the bottom of the table. For example <strong>page_id="4410"</strong>
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Denys Sydorenko
 * Author URI:        https://github.com/SidorenkoDenis1989
 * Text Domain:       football-tables-shortcodes
 */
define('__TABLESURL__', plugin_dir_url( __FILE__ ));
class FootballTablesClass
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'plugin_assets']);
        add_action('admin_enqueue_scripts', [$this, 'plugin_admin_assets']);
        add_shortcode('сhampionship_table', [$this, 'сhampionship_table_handler']);
        add_shortcode('scorers_table', [$this, 'scorers_table_handler']);
        add_shortcode('fixtures_calendar_table', [$this, 'fixtures_calendar_table_handler']);
        add_shortcode('results_table', [$this, 'results_table_handler`']);
        add_shortcode('results_fixtures_table', [$this, 'results_fixtures_table_handler']);
        add_shortcode('fixtures_table', [$this, 'fixtures_table_handler']);

        add_action('wp_ajax_get_fixtures_data', [$this, 'get_fixtures_data_handler']);
        add_action('wp_ajax_nopriv_get_fixtures_data', [$this, 'get_fixtures_data_handler']);

        add_action('wp_ajax_get_teams_ids', [$this, 'get_teams_ids_handler']);
        add_action('wp_ajax_nopriv_get_teams_ids', [$this, 'get_teams_ids_handler']);

        add_shortcode('all_results_fixtures_table', [$this, 'all_results_fixtures_table_handler']);
        add_shortcode('results_fixtures_five_dates', [$this, 'results_fixtures_five_dates_handler']);
        add_action('admin_menu', [$this, 'addPluginAdminMenu'], 9);

        add_shortcode('team_results_fixtures', [$this, 'team_fixtures_table']);

        $this->headers = array(
            'x-rapidapi-host' => 'v3.football.api-sports.io',
            'x-rapidapi-key' => '540d76a6f19a77b162092aacc645a0d7'
        );
    }

    public function addPluginAdminMenu() {
        add_menu_page( 'teams_ids', 'Teams IDs', 'administrator', 'teams-ids', array( $this, 'displayPluginAdminDashboard' ), plugin_dir_url( __FILE__ ) . 'assets/img/logo.ico', 26 );
    }

    public function displayPluginAdminDashboard() {
        require_once 'templates/dashboard/teams-ids-page.php';
    }

    public function plugin_assets(){
        $ver = time();
        wp_enqueue_script('jquery-ui', plugins_url('assets/js/jquery-ui.js', __FILE__), ['jquery'], '1.12.1', true);
        //wp_enqueue_script('football-swiper-scripts', plugins_url('assets/swiper/swiper-bundle.min.js', __FILE__), ['jquery', 'jquery-ui'], '6.4.10', true);
        wp_enqueue_script('football-table-scripts', plugins_url('assets/js/scripts.js', __FILE__), ['jquery', 'jquery-ui'], $ver, true);

        wp_enqueue_style('jquery-ui-styles', plugins_url('assets/css/jquery-ui.css', __FILE__));
        wp_enqueue_style('football-swiper-styles', plugins_url('assets/swiper/swiper-bundle.min.css', __FILE__), array(), '6.4.10');
        wp_enqueue_style('football-table-styles', plugins_url('assets/css/styles.css', __FILE__));
        wp_localize_script('football-table-scripts', 'footballTables', [
            'ajaxUrl' => admin_url('admin-ajax.php')
        ]);
    }

    public function plugin_admin_assets(){
        $ver = time();
        wp_enqueue_script('football-admin-scripts', plugins_url('assets/js/admin-scripts.js', __FILE__), ['jquery'], $ver, true);
        wp_enqueue_style('football-admin-styles', plugins_url('assets/css/admin-styles.css', __FILE__));
        wp_localize_script('football-admin-scripts', 'footballTables', [
            'ajaxUrl' => admin_url('admin-ajax.php')
        ]);
    }

    public function сhampionship_table_handler($atts){

        $atts = shortcode_atts([
            'league_id' => '40',
            'season' => '2020',
            'short' => 'false',
            'page_id' => '',
            'version' => 'new',
        ], $atts);

        $transient_name = 'standing_data_' . $atts['league_id'] . '_' . $atts['season'];
        $standing_table_data = get_transient( $transient_name  );
        if ( false === $standing_table_data ) {
            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/standings?league=' . $atts['league_id'] . '&season=' . $atts['season'] , array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));

            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), HOUR_IN_SECONDS );
            $standing_table_data = json_decode($get_remote_data['body']);
        }

        $standing_data = $standing_table_data->response[0]->league->standings[0];
        $standing_header = $standing_table_data->response[0]->league->name;

        ob_start();
        if($atts['version'] == 'new')
            include 'templates/new-standing-template.php';
        else
            include 'templates/standing-template.php';
        return ob_get_clean();
    }

    public function scorers_table_handler($atts)
    {

        $atts = shortcode_atts([
            'league_id' => '40',
            'season' => '2020',
            'short' => 'false',
            'page_id' => '',
        ], $atts);

        $transient_name = 'top_scorers_' . $atts['league_id'] . '_' . $atts['season'];
        $top_scorers_table_data = get_transient($transient_name);

        if ( false === $top_scorers_table_data ) {
            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/players/topscorers?season=' . $atts['season'] . '&league=' . $atts['league_id'] , array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), DAY_IN_SECONDS  );
            $top_scorers_table_data = json_decode($get_remote_data['body']);
        }

        $table_data = $top_scorers_table_data->response;

        ob_start();
        include 'templates/top-scorers-template.php';
        return ob_get_clean();
    }

    public function fixtures_calendar_table_handler($atts)
    {
        $atts = shortcode_atts([
            'league_id'         => '40',
            'season'            => '2020',
            'slider'            => 'false',
            'scorers'           => 'false',
        ], $atts);

        $league_id  = $atts['league_id'];
        $season     = $atts['season'];
        $slider     = $atts['slider'];
	    $scorers    = $atts['scorers'];

        $date = date('Y-m-d');
        $today_timestamp = strtotime($date);

        $transient_name = 'fixtures_' . $league_id . '_' . $season;
        $get_all_fixtures_data = get_transient($transient_name);
        if ( false === $get_all_fixtures_data ) {
            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?season=' . $season . '&league=' . $league_id, array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), 1*MINUTE_IN_SECONDS  );
            $get_all_fixtures_data = json_decode($get_remote_data['body']);
        }

        $all_fixtures_data = $get_all_fixtures_data->response;

        $fixtures_dates = array();
        foreach( $all_fixtures_data as $fixture ) {
            $fixture_timestamp = strtotime(date('d-m-Y', strtotime($fixture->fixture->date)));
            $fixtures_dates[] = $fixture_timestamp;
        }

        $fixtures_dates = array_unique($fixtures_dates);
        sort($fixtures_dates);
        foreach ( $fixtures_dates as $timestamp) {
            if ($timestamp >= $today_timestamp) {
                $actual_date_timestamp = $timestamp;
                break;
            } else {
                $actual_date_timestamp = $fixtures_dates[count($fixtures_dates) - 1];
            }
        }

        $actual_date = date('Y-m-d', $actual_date_timestamp);

        $transient_name = 'today_fixture_' . $league_id . '_' . $season;
        $get_today_fixture_data = get_transient($transient_name);
        if ( false === $get_today_fixture_data ) {

            $get_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?date=' . $actual_date . '&season=' . $season . '&league=' . $league_id , array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_data['body']), MINUTE_IN_SECONDS  );
            $get_today_fixture_data = json_decode($get_data['body']);
        }

        $table_data = $get_today_fixture_data;

        ob_start();
        include 'templates/fixtures-calendar-template.php';
        return ob_get_clean();
    }

    public function get_fixtures_data_handler(){
        if( isset($_POST['date']) && isset($_POST['league_id']) && isset($_POST['season']) ){

            $date       = $_POST['date'];
            $league_id  = $_POST['league_id'];
            $season     = $_POST['season'];
            $scorers    = $_POST['scorers'];

            $get_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?date=' . $date . '&season=' . $season . '&league=' . $league_id , array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));

            $table_data = json_decode($get_data['body']);

            $table_html = '';

            ob_start();
            include 'templates/fixtures-calendar-matches.php';
            $table_html .= ob_get_contents();
            ob_get_clean();

            wp_send_json(array(
                'table_html' => $table_html,
                'table_data' => $table_data,
                'get_data' => $get_data

            ));
        }
    }

    public function results_table_handler($atts){
        $atts = shortcode_atts([
            'league_id' => '40',
            'season' => '2020',
            'short' => 'false',
            'page_id' => '',
        ], $atts);

        $league_id = $atts['league_id'];
        $season = $atts['season'];

        $transient_name = 'results_' . $atts['league_id'] . '_' . $atts['season'];
        $get_fixtures_data = get_transient( $transient_name );

        if ( false === $get_fixtures_data ) {
            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?season=' . $season . '&league=' . $league_id . '&status=FT', array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), MINUTE_IN_SECONDS  );
            $get_fixtures_data = json_decode($get_remote_data['body']);
        }

        $fixtures_data = $get_fixtures_data->response;
        $results_data = array();
        foreach( $fixtures_data as $fixture ) {
            $results_data[strtotime(date('d-m-Y', strtotime($fixture->fixture->date)))][str_replace ('Regular Season - ', '', $fixture->league->round)][] = $fixture;
        }
        krsort($results_data);

        if( $atts['short'] == "true" ){
            $first_value = reset( $results_data );
            $first_key = key( $results_data );
            $results_data = array();
            $results_data[$first_key] = $first_value;
        }

        ob_start();
        include 'templates/results-template.php';
        return ob_get_clean();
    }

    public function fixtures_table_handler($atts){
        $atts = shortcode_atts([
            'league_id' => '40',
            'season' => '2020',
            'short' => 'false',
            'page_id' => '',
        ], $atts);

        $league_id = $atts['league_id'];
        $season = $atts['season'];

        $today_timestamp = strtotime(date('d-m-Y'));

        $transient_name = 'fixtures_' . $atts['league_id'] . '_' . $atts['season'];
        $get_all_fixtures_data = get_transient($transient_name);

        if ( false === $get_all_fixtures_data ) {
            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?season=' . $season . '&league=' . $league_id, array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), MINUTE_IN_SECONDS  );
            $get_all_fixtures_data = json_decode($get_remote_data['body']);
        }

        $all_fixtures_data = $get_all_fixtures_data->response;
        $fixtures_data = array();
        foreach( $all_fixtures_data as $fixture ) {
            $fixture_timestamp = strtotime(date('d-m-Y', strtotime($fixture->fixture->date)));
            if( $fixture_timestamp >= $today_timestamp){
                $fixtures_data[$fixture_timestamp][str_replace ('Regular Season - ', '', $fixture->league->round)][] = $fixture;
            }
        }

        ksort($fixtures_data);

        if( $atts['short'] == "true" ){
            $first_value = reset( $fixtures_data );
            $first_key = key( $fixtures_data );
            $fixtures_data = array();
            $fixtures_data[$first_key] = $first_value;
        }

        ob_start();
        include 'templates/fixtures-template.php';
        return ob_get_clean();
    }

    public function results_fixtures_table_handler($atts){
        $atts = shortcode_atts([
            'league_id' => '40',
            'season' => '2020',
            'short' => 'false',
            'page_id' => '',
        ], $atts);

        $league_id = $atts['league_id'];
        $season = $atts['season'];

        $transient_name = 'results_' . $atts['league_id'] . '_' . $atts['season'];
        $get_fixtures_data = get_transient( $transient_name );

        if ( false === $get_fixtures_data ) {
            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?season=' . $season . '&league=' . $league_id . '&from=2020-12-27' . '&to=2020-12-31', array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), MINUTE_IN_SECONDS  );
            $get_fixtures_data = json_decode($get_remote_data['body']);
        }

        $fixtures_data = $get_fixtures_data->response;
        $results_data = array();
        foreach( $fixtures_data as $fixture ) {
            $results_data[strtotime(date('d-m-Y', strtotime($fixture->fixture->date)))][str_replace ('Regular Season - ', '', $fixture->league->round)][] = $fixture;
        }
        krsort($results_data);

        if( $atts['short'] == "true" ){
            $first_value = reset( $results_data );
            $first_key = key( $results_data );
            $results_data = array();
            $results_data[$first_key] = $first_value;
        }

        ob_start();
        include 'templates/results-fixtures-template.php';
        return ob_get_clean();
    }

    public function all_results_fixtures_table_handler($atts){
        $atts = shortcode_atts([
            'league_id' => '40',
            'season' => '2020',
        ], $atts);

        $league_id = $atts['league_id'];
        $season = $atts['season'];

        $transient_name = 'fixtures_' . $league_id . '_' . $season;
        $get_all_fixtures_data = get_transient( $transient_name );
        if ( false === $get_all_fixtures_data ) {
            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?season=' . $season . '&league=' . $league_id, array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), MINUTE_IN_SECONDS  );
            $get_all_fixtures_data = json_decode($get_remote_data['body']);
        }

        $all_fixtures_data = $get_all_fixtures_data->response;

        $fixtures_data = array();
        foreach( $all_fixtures_data as $fixture ) {
            $fixture_timestamp = strtotime(date('d-m-Y', strtotime($fixture->fixture->date)));
            $fixtures_data[$fixture_timestamp][str_replace ('Regular Season - ', '', $fixture->league->round)][] = $fixture;
        }
        ksort($fixtures_data);

        ob_start();
        include 'templates/fixtures-template.php';
        return ob_get_clean();
    }

    public function results_fixtures_five_dates_handler($atts){
        $atts = shortcode_atts([
            'league_id' => '40',
            'season' => '2020',
            'page_id' => '',
            'scorers' => 'false',
        ], $atts);

        $league_id = $atts['league_id'];
        $season = $atts['season'];
        $scorers = $atts['scorers'];

        $today_timestamp = strtotime(date('d-m-Y'));

        $transient_name = 'fixtures_' . $league_id . '_' . $season;
        $get_all_fixtures_data = get_transient( $transient_name );
        if ( false === $get_all_fixtures_data ) {
            $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?season=' . $season . '&league=' . $league_id, array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), MINUTE_IN_SECONDS  );
            $get_all_fixtures_data = json_decode($get_remote_data['body']);
        }

        $all_fixtures_data = $get_all_fixtures_data->response;

        $fixtures_dates = array();
        foreach( $all_fixtures_data as $fixture ) {
            $fixture_timestamp = strtotime(date('d-m-Y', strtotime($fixture->fixture->date)));
            $fixtures_dates[] = $fixture_timestamp;
        }

        $fixtures_dates = array_unique($fixtures_dates);
        sort($fixtures_dates);
        $i = 0;
        foreach ( $fixtures_dates as $timestamp) {
            $i = $i + 1;
            if ($timestamp >= $today_timestamp) {
                $actual_date_timestamp = $timestamp;
                break;
            } else {
                $actual_date_timestamp = $fixtures_dates[count($fixtures_dates) - 1];
            }
        }

        $fixtures_dates_count = count($fixtures_dates);
        switch ($i) {
            case 1:
                $fixtures_dates = array_slice( $fixtures_dates, 0, 5 );
                break;
            case 2:
                $fixtures_dates = array_slice( $fixtures_dates, 1, 5 );
                break;
            case ($fixtures_dates_count-1):
                $fixtures_dates = array_slice( $fixtures_dates, ($i-4), 5 );
                break;
            case ($fixtures_dates_count):
                $fixtures_dates = array_slice( $fixtures_dates, ($i-5), 5 );
                break;
            default:
                $fixtures_dates = array_slice( $fixtures_dates, ($i-3), 5 );
        }

        $actual_date = date('Y-m-d', $actual_date_timestamp);

        $transient_name = 'today_fixture_' . $league_id . '_' . $season;
        $get_today_fixture_data = get_transient( $transient_name );
        if ( false === $get_today_fixture_data ) {

            $get_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?date=' . $actual_date . '&season=' . $season . '&league=' . $league_id , array(
                'timeout'   => 20,
                'headers'   => $this->headers,
            ));
            $transient = set_transient( $transient_name, json_decode($get_data['body']), MINUTE_IN_SECONDS  );
            $get_today_fixture_data = json_decode($get_data['body']);
        }

        $table_data = $get_today_fixture_data;

        ob_start();
        include 'templates/results-fixures-five-dates.php';
        return ob_get_clean();
    }

    public function get_teams_ids_handler(){
        $season = $_POST['season'];
        $league_id = $_POST['league_id'];
        update_option('football_teams_ids_season', $season);
        update_option('football_teams_ids_league', $league_id);

        $get_data = wp_remote_get('https://v3.football.api-sports.io/teams?season=' . $season . '&league=' . $league_id , array(
            'timeout'   => 20,
            'headers'   => $this->headers,
        ));

	    $get_all_teams_data = wp_remote_get('https://v3.football.api-sports.io/teams?season=' . $season, array(
		    'timeout'   => 20,
		    'headers'   => $this->headers,
	    ));

        $teams_data = json_decode($get_data['body']);
	    $teams_all_teams_data = json_decode($get_all_teams_data['body']);
        update_option('football_teams_data', $teams_data);
	    update_option('football_teams_all_teams_data', $teams_all_teams_data);
        $table = '';
        ob_start();
        include 'templates/dashboard/template-parts/teams-ids-table.php';
        $table .= ob_get_contents();
        ob_end_clean();

        wp_send_json(array(
            'response'      => $teams_data,
            'response_all'  => $teams_all_teams_data,
            'table_html'    => $table
        ));
    }

    public function team_fixtures_table($atts){
        $atts = shortcode_atts([
            'league_id' => '40',
            'team_id' => '',
            'season' => '2020',
            'short' => 'false',
            'page_id' => '',
        ], $atts);

        $league_id = $atts['league_id'];
        $season = $atts['season'];
        $id_term = get_queried_object()->term_id;
        $team_id = get_field('api_team_id' , 'team_'.$id_term);
//        $team_id = get_field('api_team_id');
        if(!$team_id){
            $team_id = $atts['team_id'];
        }

        if( $team_id ) {
            $transient_name = 'team_results_fixtures_' . $team_id . '_' . $atts['league_id'] . '_' . $atts['season'];
            $get_fixtures_data = get_transient( $transient_name );

            if ( false === $get_fixtures_data ) {
//                $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?season=' . $season . '&league=' . $league_id . '&team=' . $team_id, array(
//                    'timeout'   => 20,
//                    'headers'   => $this->headers,
//                ));
                $get_remote_data = wp_remote_get('https://v3.football.api-sports.io/fixtures?season=' . $season . '&team=' . $team_id, array(
                  'timeout'   => 20,
                  'headers'   => $this->headers,
                ));
                $transient = set_transient( $transient_name, json_decode($get_remote_data['body']), MINUTE_IN_SECONDS  );
                $get_fixtures_data = json_decode($get_remote_data['body']);
            }

            $fixtures_data = $get_fixtures_data->response;
            $results_data = array();
            $fixtures_dates  = array();
            foreach( $fixtures_data as $fixture ) {
                $results_data[strtotime(date('d-m-Y', strtotime($fixture->fixture->date)))][str_replace ('Regular Season - ', '', $fixture->league->round)][] = $fixture;
                $fixture_timestamp = strtotime(date('d-m-Y', strtotime($fixture->fixture->date)));
                $fixtures_dates[] = $fixture_timestamp;
            }

            ksort($fixtures_dates);
            ksort($results_data);

            $today_timestamp = strtotime(date('d-m-Y'));
            $i = 0;
            foreach ( $fixtures_dates as $timestamp) {
                $i = $i + 1;
                if ($timestamp >= $today_timestamp) {
                    break;
                }
            }
            $fixtures_dates_count = count($fixtures_dates);
            switch ($i) {
                case 3:
                case 2:
                case 1:
                    $fixtures_dates = array_slice( $fixtures_dates, 0, 6 );
                    break;

                case ($fixtures_dates_count-2):
                    $fixtures_dates = array_slice( $fixtures_dates, ($i-4), 6 );
                    break;
                case ($fixtures_dates_count-1):
                    $fixtures_dates = array_slice( $fixtures_dates, ($i-5), 6 );
                    break;
                case ($fixtures_dates_count):
                    $fixtures_dates = array_slice( $fixtures_dates, ($i-6), 6 );
                    break;
                default:
                    $fixtures_dates = array_slice( $fixtures_dates, ($i-3), 6 );
            }

            if( $atts['short'] == 'true'){
                foreach($results_data as $timestamp => $match) {
                    if( !in_array($timestamp, $fixtures_dates) ){
                        unset($results_data[$timestamp]);
                    }
                }
            }

            ob_start();
            include 'templates/team-fixtures-results-template.php';
            return ob_get_clean();
        } else {
            return '<p>Parameter "team_id" is required!</p>';
        }
    }
}
new FootballTablesClass();

function get_team_name($name){
  $shorts = array('Sheffield Wednesday' => 'Sheff Wed' , 'Nottingham Forest' => 'N Forest');

  if(array_key_exists($name , $shorts)){
    return $shorts[$name];
  }
  else{
    return $name;
  }

}