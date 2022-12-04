<?php
$survey_id = $_GET['survey_id'];
class Survey_Answers_Table extends WP_List_Table
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
        $survey_id = $_GET['survey_id'];

        $columns = array(
            'user_id'          => 'ID',

//            'survey_title'       => 'Survey Title',
//            'responses' => 'Responses',
//            'view_more'        => 'View more',
//            'director'    => 'Director',
//            'rating'      => 'Rating'
        );
        $quests = get_post_meta(  $survey_id, 'custom_survey_questions' , false );
        $count = 1;
        foreach ($quests as $quest){

            $columns[$quest] = $quest;
            $count ++;
        }

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
   public function table_data()
    {
        $survey_id = $_GET['survey_id'];
        $data = array();
        global $wpdb;
        $answers =  $wpdb->get_results(
            "SELECT * FROM `{$wpdb->base_prefix}survey_data` WHERE survey_id = $survey_id  "
        ) ;

        $quests = get_post_meta(  $survey_id, 'custom_survey_questions' , false );
        $count = 0;
        foreach ($answers as $answer){
            $data[$count] = array(
                'user_id'          => $answer->user_id,

            );
            for ($i=0;$i<count(json_decode($answer->survey_answers)) ; $i++){
                $data[$count][$quests[$i]] = json_decode($answer->survey_answers)[$i];
            }

//            foreach ($quests as $quest){
//
//                $data[$count][$quest] = $quest;
//
//            }
//            return json_decode($answer->survey_answers)[0];
            $count++;
        }






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


      echo ($item[$column_name]);


        }


    /**
     *
     *
     *
     *
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
$exampleListTable = new Survey_Answers_Table();
$exampleListTable->prepare_items();
?>
<div class="wrap">
    <div id="icon-users" class="icon32"></div>
    <h2><?php echo get_the_title($survey_id)?></h2>
    <?php $exampleListTable->display(); ?>
</div>


