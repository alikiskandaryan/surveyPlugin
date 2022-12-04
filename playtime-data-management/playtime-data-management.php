<?php
/*
Plugin Name: Playtime Data Management
Plugin URI: https://stackmesh.com
Description: A Plugin for Playtime CSV Data management.
Version: 1.0.0
Author: Qamar Ul Din Hamza
Author URI: https://stackmesh.com
License: GPL2
Text Domain: playtime_data_management
*/

define("PTDM_PATH", plugin_dir_path(__FILE__));
define("PTDM_URL", plugin_dir_url(__FILE__));
define("PTDM_VERSION", "1.0.0");
define("PTDM_DOMAIN", "playtime_data_management");

require_once PTDM_PATH."/admin/admin-panel-functions.php";
require_once PTDM_PATH."/includes/playtime_frontend_tag_management.php";

//create table when activating plugin

function on_activation_playtime_data_management(){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE `{$wpdb->base_prefix}survey_data` (
  data_id  int(11)   not null AUTO_INCREMENT,
  user_id bigint(20) UNSIGNED NOT NULL,
  survey_id bigint(20) UNSIGNED NOT NULL,
  survey_answers json NOT NULL,
  created_at datetime NOT NULL,
  expires_at datetime NOT NULL,
  PRIMARY KEY  (data_id)
) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__,"on_activation_playtime_data_management");

add_action( 'admin_enqueue_scripts', 'ptdm_include_scripts' );
function ptdm_include_scripts() {
	if (isset($_GET['page']) && $_GET['page'] == "playtime-data-management"){
		wp_register_style( 'select2css', PTDM_URL.'/assets/select2/select2.css', false, '1.0', 'all' );
		wp_register_script( 'select2', PTDM_URL.'/assets/select2/select2.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'select2css' );
		wp_enqueue_script( 'select2' );
	}
	// I recommend to add additional conditions here
	// because we probably do not need the scripts on every admin page, right?

	// WordPress media uploader scripts
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
}




//createing table for Survey response page

if(is_admin())
{
    new Paulund_Wp_List_Table();
}

/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */
class Paulund_Wp_List_Table
{
    /**
     * Constructor will create the menu item
     */
//    add new page in admin panel "Survey Response"

    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'add_menu_example_list_table_page' ));
    }

    public function add_menu_example_list_table_page()
    {
//        add_menu_page( 'Example List Table', 'Example List Table', 'manage_options', 'example-list-table.php', array($this, 'list_table_page') );
        add_submenu_page(
            'playtime-data-management',
            'Survey Response', //page title
            'Survey Response', //menu title
            'edit_themes', //capability,
            'add-survey1',//menu slug
            array($this, 'list_table_page') //callback function
        );
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
        $exampleListTable = new Example_List_Table();
        $exampleListTable->prepare_items();
        ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Survey Response</h2>
            <?php $exampleListTable->display(); ?>
        </div>
        <?php
    }
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Example_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'ID',
            'survey_title'       => 'Survey Title',
            'responses' => 'Responses',
            'view_more'        => 'View more',
//            'director'    => 'Director',
//            'rating'      => 'Rating'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('survey_title' => array('survey_title', false),'responses' => array('responses', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();
        $my_posts = get_posts( array(
            'numberposts' => -1,
            'category'    => 0,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'include'     => array(),
            'exclude'     => array(),
            'meta_key'    => '',
            'meta_value'  =>'',
            'post_type'   => 'survey',
            'suppress_filters' => true,
            'post_status' => 'any'
        ) );

        global $post;

        foreach( $my_posts as $post ){
            global  $wpdb;
            $totalanswers = intval( $wpdb->get_var(
                "SELECT COUNT(*) FROM `{$wpdb->base_prefix}survey_data` WHERE survey_id = $post->ID "
            ) );
            $data[] = array(
                'id'          => $post->ID,
                'survey_title'       => $post->post_title,
                'responses' => $totalanswers,
            'view_more'        => '<a href="'.admin_url().'admin.php?page=single-survey&survey_id='.$post->ID .' ">View details</a>    ',
//            'director'    => 'Frank Darabont',
//            'rating'      => '9.3'
            );
        }

        wp_reset_postdata(); // сброс


        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'survey_title':
            case 'responses':
            case 'view_more':
//            case 'director':
//            case 'rating':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'title';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
}
/**
 * Create a new table class that will extend the WP_List_Table for single survey answers
 */
//class Survey_Answers_Table extends WP_List_Table
//{
//    /**
//     * Prepare the items for the table to process
//     *
//     * @return Void
//     */
//    public function prepare_items()
//    {
//        $columns = $this->get_columns();
//        $hidden = $this->get_hidden_columns();
//        $sortable = $this->get_sortable_columns();
//
//        $data = $this->table_data();
//        usort( $data, array( &$this, 'sort_data' ) );
//
//        $perPage = 20;
//        $currentPage = $this->get_pagenum();
//        $totalItems = count($data);
//
//        $this->set_pagination_args( array(
//            'total_items' => $totalItems,
//            'per_page'    => $perPage
//        ) );
//
//        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
//
//        $this->_column_headers = array($columns, $hidden, $sortable);
//        $this->items = $data;
//    }
//
//    /**
//     * Override the parent columns method. Defines the columns to use in your listing table
//     *
//     * @return Array
//     */
//    public function get_columns()
//    {
//        $columns = array(
//            'id'          => 'ID',
//            'survey_title'       => 'Survey Title',
//            'responses' => 'Responses',
//            'view_more'        => 'View more',
////            'director'    => 'Director',
////            'rating'      => 'Rating'
//        );
//
//        return $columns;
//    }
//
//    /**
//     * Define which columns are hidden
//     *
//     * @return Array
//     */
//    public function get_hidden_columns()
//    {
//        return array();
//    }
//
//    /**
//     * Define the sortable columns
//     *
//     * @return Array
//     */
//    public function get_sortable_columns()
//    {
//        return array('survey_title' => array('survey_title', false),'responses' => array('responses', false));
//    }
//
//    /**
//     * Get the table data
//     *
//     * @return Array
//     */
//    private function table_data()
//    {
//        $data = array();
//        $my_posts = get_posts( array(
//            'numberposts' => -1,
//            'category'    => 0,
//            'orderby'     => 'date',
//            'order'       => 'DESC',
//            'include'     => array(),
//            'exclude'     => array(),
//            'meta_key'    => '',
//            'meta_value'  =>'',
//            'post_type'   => 'survey',
//            'suppress_filters' => true,
//            'post_status' => 'any'
//        ) );
//
//        global $post;
//
//        foreach( $my_posts as $post ){
//            global  $wpdb;
//            $totalanswers = intval( $wpdb->get_var(
//                "SELECT COUNT(*) FROM `{$wpdb->base_prefix}survey_data` WHERE survey_id = $post->ID "
//            ) );
//            $data[] = array(
//                'id'          => $post->ID,
//                'survey_title'       => $post->post_title,
//                'responses' => $totalanswers,
//            'view_more'        => '<a href="'.admin_url().'admin.php?page=single-survey&survey_id='.$post->ID .' ">View details</a>    ',
////            'director'    => 'Frank Darabont',
////            'rating'      => '9.3'
//            );
//        }
//
//        wp_reset_postdata(); // сброс
//
//
//        return $data;
//    }
//
//    /**
//     * Define what data to show on each column of the table
//     *
//     * @param  Array $item        Data
//     * @param  String $column_name - Current column name
//     *
//     * @return Mixed
//     */
//    public function column_default( $item, $column_name )
//    {
//        switch( $column_name ) {
//            case 'id':
//            case 'survey_title':
//            case 'responses':
//            case 'view_more':
////            case 'director':
////            case 'rating':
//                return $item[ $column_name ];
//
//            default:
//                return print_r( $item, true ) ;
//        }
//    }
//
//    /**
//     * Allows you to sort the data by the variables set in the $_GET
//     *
//     * @return Mixed
//     */
//    private function sort_data( $a, $b )
//    {
//        // Set defaults
//        $orderby = 'title';
//        $order = 'asc';
//
//        // If orderby is set, use this as the sort column
//        if(!empty($_GET['orderby']))
//        {
//            $orderby = $_GET['orderby'];
//        }
//
//        // If order is set use this as the order
//        if(!empty($_GET['order']))
//        {
//            $order = $_GET['order'];
//        }
//
//
//        $result = strcmp( $a[$orderby], $b[$orderby] );
//
//        if($order === 'asc')
//        {
//            return $result;
//        }
//
//        return -$result;
//    }
//}



?>