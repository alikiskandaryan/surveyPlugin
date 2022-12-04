<?php
/*
 * Admin side panel for playtime data management
 * */

//plugin scripts
add_action('wp_enqueue_scripts','customize_myplugin_js_init');

function customize_myplugin_js_init() {
    wp_enqueue_script( 'surveyscript', plugins_url( '/playtime-data-management/assets/select2/surveyscript.js' ),array('jquery'),'',true)  ;
}
function ptdm_add_menu_page(){
	add_menu_page(
		__("Playtime Data", PTDM_DOMAIN),
		"Playtime Data",
		"manage_options",
		"playtime-data-management",
		"playtime_data_management_handler"
	);
//    add_submenu_page(
//        'playtime-data-management',
//        'Add Survey5', //page title
//        'Add Survey5', //menu title
//        'edit_themes', //capability,
//        'add-survey',//menu slug
//        'add_survey_handler' //callback function
//    );
//    single surwey answers
    add_dashboard_page(
        __( 'Welcome', 'textdomain' ),
        __( 'Welcome', 'textdomain' ),
        'manage_options',
        'single-survey',
        'prefix_render'
    );
    the_content();
}
//single answers page function

function prefix_render() {
    echo '<div class="wrap">';

    require "answer_survey.php";
    echo '</div>';
}

add_action("admin_menu", "ptdm_add_menu_page");

function playtime_data_management_handler(){
	playtime_on_save_data();
	require "playtime_data_management_handler.php";
	playtime_scripts();
}
function add_survey_handler(){
	playtime_on_save_data();
	require "add-survey.php";
	playtime_scripts();
}
//hide single survey answers in admin panel

add_action( 'admin_head', function() {
    remove_submenu_page( 'index.php',         'single-survey',);
} );

function playtime_on_save_data(){
	if (isset($_POST['playtime_week_1_data_file'])){
		update_option("playtime_week_1_data_file", $_POST['playtime_week_1_data_file']);
	}

	if (isset($_POST['playtime_week_2_data_file'])){
		update_option("playtime_week_2_data_file", $_POST['playtime_week_2_data_file']);
	}

	if (isset($_POST['playtime_week_3_data_file'])){
		update_option("playtime_week_3_data_file", $_POST['playtime_week_3_data_file']);
	}

	if (isset($_POST['playtime_week_4_data_file'])){
		update_option("playtime_week_4_data_file", $_POST['playtime_week_4_data_file']);
	}

	if (isset($_POST['playtime_before_registration_data_file'])){
		update_option("playtime_before_registration_data_file", $_POST['playtime_before_registration_data_file']);
	}

	if (isset($_POST['playtime_after_registration_data_file'])){
		update_option("playtime_after_registration_data_file", $_POST['playtime_after_registration_data_file']);
	}

	if (isset($_POST['playtime_template_page'])){
		update_option("playtime_template_page", $_POST['playtime_template_page']);
	}
}
function playtime_scripts(){
    ?>
    <script>
        jQuery( function($){

            $( 'body' ).on( 'click', '.upload-data-file', function( event ){
                event.preventDefault();

                const button = $(this)
                const dataFileId = button.next().next().val();

                const customUploader = wp.media({
                    title: 'Insert Data File',
                    button: {
                        text: 'Use this File'
                    },
                    multiple: false
                }).on( 'select', function() {
                    const attachment = customUploader.state().get( 'selection' ).first().toJSON();
                    button.removeClass( 'button' ).removeClass("upload-data-file").html( '<a href="'+attachment.url+'">'+attachment.name+'</a>');
                    button.next().show();
                    button.next().next().val( attachment.id );
                })

                customUploader.on( 'open', function() {

                    if( dataFileId ) {
                        const selection = customUploader.state().get( 'selection' )
                        attachment = wp.media.attachment( dataFileId );
                        attachment.fetch();
                        selection.add( attachment ? [attachment] : [] );
                    }

                })

                customUploader.open()

            });

            $( 'body' ).on( 'click', '.remove-file', function( event ){
                event.preventDefault();
                const button = $(this);
                button.next().val( '' );
                button.hide().prev().addClass( 'button' ).addClass("upload-data-file").attr("href","#").html( 'Upload Data File' );
            });

            // $(".select2-playtime-pages").select2();
        });
    </script>
    <style>
        .select2-container{
            min-width: 300px;
        }

        .remove-file{
            text-decoration: none;
            background-color: #ff0000;
            display: inline-block;
            padding: 7px 15px;
            color: #ffffff;
            border-radius: 5px;
        }

    </style>
    <?php
}

function pd101_register_survey_post_type() {

    $labels = array(
        'name'               => 'Surveys',
        'singular_name'      => 'Survey',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Survey',
        'edit_item'          => 'Edit Survey',
        'new_item'           => 'New Survey',
        'all_items'          => 'All Surveys',
        'view_item'          => 'View Survey',
        'search_items'       => 'Search Surveys',
        'not_found'          =>  'No surveys found',
        'not_found_in_trash' => 'No survey found in Trash',
        'parent_item_colon'  => '',
        'menu_name'          => 'Surveys'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'survey' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 100,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ,'custom-fields' )
    );

    register_post_type( 'survey', $args );

}
add_action( 'init', 'pd101_register_survey_post_type' );

//echo get_field('test',236457);
add_filter( 'postmeta_form_limit', 'meta_limit_increase' );
function meta_limit_increase( $limit ) {
    return 200;
}
//


add_action( 'init', 'survey_move_to_draft' );

/**
 * Function for `init` action-hook.
 *
 * @return void
 */
function survey_move_to_draft(){
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
    ) );
        foreach ($my_posts as $post){
            $info = getdate();
            $date = $info['mday'];
            $month = $info['mon'];
            $year = $info['year'];
            $hour = $info['hours'];
            $min = $info['minutes'];
            $sec = $info['seconds'];

            $current_date = "$year-$month-$date $hour:$min:$sec";
            $dating = $post->post_date_gmt;


            $dating = date('Y-m-d H:i:s', strtotime($dating. ' + 28 days ')); //4 weeks
//    echo $dating;

            if ($dating<$current_date){
                $current_post = get_post( $post->ID, 'ARRAY_A' );
                $current_post['post_status'] = 'draft';
                wp_update_post($current_post);

            }
        }

}


//creating shortcode

add_shortcode( 'survey', 'survey_shortcode_fn' );

function survey_shortcode_fn( $atts ){
    $post_id =  $atts['id'];
    $post = get_post($post_id);
    $quests = get_post_meta(  $post_id, 'custom_survey_questions' , false );
    $html = '<form id="surveyquiz" style="    display: flex;
    flex-direction: column;
    width: 50%;
    align-items: center;">';
    $count = 0;
    foreach ($quests as $quest){
        $count++;
        $html .= '<label for="quest'.$count.'">'.$quest .'</label>';
        $html .= '<input type="text" id="quest'.$count.'" name="answer[]">';
    }
    $html .= '<input type="submit" value="submit">';
    $html .= '<input type="hidden" value="survey_answer" name="action">';
    $html .= '<input type="hidden" value="'. $post_id.'" name="survey_id">';
    $html .= '</form>';


    return $html;
}


//add custom admin column for survey

add_filter('manage_survey_posts_columns','filter_cpt_columns');

function filter_cpt_columns( $columns ) {
    // this will add the column to the end of the array
    $columns['survey_shortcode'] = 'Survey Shortcode';
    //add more columns as needed

    // as with all filters, we need to return the passed content/variable
    return $columns;
}
add_action( 'manage_posts_custom_column','action_custom_columns_content', 10, 2 );

function action_custom_columns_content ( $column_id, $post_id ) {
    //run a switch statement for all of the custom columns created

    $text = '[survey id="'.$post_id.'"]';
//        get_field( "posted_date", $post_id );

    switch( $column_id ) {
        case 'survey_shortcode':
//            echo 'test';
            if ($text != null){
                echo   $text;
            }else{
                echo 'NULL';
            }


//            echo ($value = 'Published <br>' . $text ) ? $value : 'Null';
            break;

        //add more items here as needed, just make sure to use the column_id in the filter for each new item.

    }
}

//survey answers saving in db

add_action( 'wp_ajax_survey_answer', 'survey_answer_fn' );
add_action( 'wp_ajax_nopriv_survey_answer', 'survey_answer_fn' );
function survey_answer_fn(){
    $post = $_POST;
    $survey = $post['survey_id'];
    $answer = $post['answer'];
    $answer = json_encode($answer);
    $user_id = get_current_user_id();
    global $wpdb;

    $table = $wpdb->prefix . 'survey_data';

    $wpdb->insert( $table, array(
        'user_id' => $user_id,
        'survey_id' => $survey,
        'survey_answers' => $answer,
    ));
    wp_send_json($wpdb->insert_id);
    wp_die();
}





