<?php

//$post = get_post(236457);
//$info = getdate();
//$date = $info['mday'];
//$month = $info['mon'];
//$year = $info['year'];
//$hour = $info['hours'];
//$min = $info['minutes'];
//$sec = $info['seconds'];
//
//$current_date = "$year-$month-$date $hour:$min:$sec";
//$dating = $post->post_date_gmt;
////if (isset($_GET['testing'])){
//    echo '<pre>';
//    print_r($post);
//    print_r($current_date);
//    echo '</pre>';
//
//$dating = date('Y-m-d H:i:s', strtotime($dating. ' + 4 days '));
////$dating = date('Y-m-d H:i:s', strtotime($dating. ' + 10 hours '));
//echo $dating;
////}
//if ($dating<$current_date){
//    echo 'ok';
//
//}else if ($dating==$current_date){
//    echo '==';
//}else{
//    $current_post = get_post( 236457, 'ARRAY_A' );
//    $current_post['post_status'] = 'draft';
//    wp_update_post($current_post);
//}
//$my_posts = get_posts( array(
//    'numberposts' => -1,
//    'category'    => 0,
//    'orderby'     => 'date',
//    'order'       => 'DESC',
//    'include'     => array(),
//    'exclude'     => array(),
//    'meta_key'    => '',
//    'meta_value'  =>'',
//    'post_type'   => 'survey',
//    'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
//) );
//foreach ($my_posts as $post){
//    $info = getdate();
//    $date = $info['mday'];
//    $month = $info['mon'];
//    $year = $info['year'];
//    $hour = $info['hours'];
//    $min = $info['minutes'];
//    $sec = $info['seconds'];
//
//    $current_date = "$year-$month-$date $hour:$min:$sec";
//    $dating = $post->post_date_gmt;
//
//
//    $dating = date('Y-m-d H:i:s', strtotime($dating. ' + 4 days '));
//    echo $dating;
//
//    if ($dating<$current_date){
//        $current_post = get_post( 236457, 'ARRAY_A' );
//        $current_post['post_status'] = 'draft';
//        wp_update_post($current_post);
//
//    }
//}
//echo '<pre>';
//print_r($my_posts);
//echo '</pre>';
echo do_shortcode('[survey id="7"]')
?>

<!--<form action="">-->
<!--    <label for="quest1">Question1</label><br>-->
<!--    <input type="text" id="quest1" name="quest1" placeholder="question" ><br>-->
<!--    <label for="quest2">Question2:</label><br>-->
<!--    <input type="text" id="quest2" name="quest2"  placeholder="question" ><br>-->
<!--    <label for="quest3">Question3</label><br>-->
<!--    <input type="text" id="quest3" name="quest3"  placeholder="question" ><br>-->
<!--    <label for="quest4">Question4</label><br>-->
<!--    <input type="text" id="quest4" name="quest4"  placeholder="question" ><br><br>-->
<!--    <input type="submit" value="Submit">-->
<!--</form>-->
<?php



