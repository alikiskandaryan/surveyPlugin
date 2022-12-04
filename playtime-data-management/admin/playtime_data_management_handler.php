<div class="wrap">
	<h1>Playtime Data Management</h1>
    <?php
    $playtime_week_1_data_file = get_option("playtime_week_1_data_file");
    $playtime_week_2_data_file = get_option("playtime_week_2_data_file");
    $playtime_week_3_data_file = get_option("playtime_week_3_data_file");
    $playtime_week_4_data_file = get_option("playtime_week_4_data_file");
    $playtime_before_registration_data_file = get_option("playtime_before_registration_data_file");
    $playtime_after_registration_data_file = get_option("playtime_after_registration_data_file");
    $playtime_template_page = get_option("playtime_template_page");
    ?>
	<form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?= __("Week 1 Data",PTDM_DOMAIN) ?></th>
                    <td>
                        <?php
                        if (wp_get_attachment_url($playtime_week_1_data_file)){
	                        $file= wp_get_attachment_link($playtime_week_1_data_file);
                            echo $file;
                        ?>
                            <a href="#" class="remove-file">Remove Data File</a>
                            <input type="hidden" value="<?= $playtime_week_1_data_file ?>" name="playtime_week_1_data_file">
                            <?php
                        }else{
                            ?>
                            <a href="#" class="button upload-data-file">Upload Week 1 File</a>
                            <a href="#" class="remove-file button button-secondary" style="display:none">Remove Data File</a>
                            <input type="hidden" name="playtime_week_1_data_file" value="">
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?= __("Week 2 Data",PTDM_DOMAIN) ?></th>
                    <td>
	                    <?php
	                    if (wp_get_attachment_url($playtime_week_2_data_file)){
		                    $file= wp_get_attachment_link($playtime_week_2_data_file);
                            echo $file;
                        ?>
                            <a href="#" class="remove-file">Remove Data File</a>
                            <input type="hidden" value="<?= $playtime_week_2_data_file ?>" name="playtime_week_2_data_file">
		                    <?php
	                    }else{
		                    ?>
                            <a href="#" class="button upload-data-file">Upload Week 2 File</a>
                            <a href="#" class="remove-file button button-secondary" style="display:none">Remove Data File</a>
                            <input type="hidden" name="playtime_week_2_data_file" value="">
		                    <?php
	                    }
	                    ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?= __("Week 3 Data",PTDM_DOMAIN) ?></th>
                    <td>
		                <?php
		                if (wp_get_attachment_url($playtime_week_3_data_file)){
			                $file= wp_get_attachment_link($playtime_week_3_data_file);
			                echo $file;
                        ?>
                            <a href="#" class="remove-file">Remove Data File</a>
                            <input type="hidden" value="<?= $playtime_week_3_data_file ?>" name="playtime_week_3_data_file">
			                <?php
		                }else{
			                ?>
                            <a href="#" class="button upload-data-file">Upload Week 3 File</a>
                            <a href="#" class="remove-file button button-secondary" style="display:none">Remove Data File</a>
                            <input type="hidden" name="playtime_week_3_data_file" value="">
			                <?php
		                }
		                ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?= __("Week 4 Data",PTDM_DOMAIN) ?></th>
                    <td>
		                <?php
		                if (wp_get_attachment_url($playtime_week_4_data_file)){
			                $file= wp_get_attachment_link($playtime_week_4_data_file);
			                echo $file;
                        ?>
                            <a href="#" class="remove-file">Remove Data File</a>
                            <input type="hidden" value="<?= $playtime_week_4_data_file ?>" name="playtime_week_4_data_file">
			                <?php
		                }else{
			                ?>
                            <a href="#" class="button upload-data-file">Upload Week 4 File</a>
                            <a href="#" class="remove-file button button-secondary" style="display:none">Remove Data File</a>
                            <input type="hidden" name="playtime_week_4_data_file" value="">
			                <?php
		                }
		                ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?= __("Before Registration Data",PTDM_DOMAIN) ?></th>
                    <td>
		                <?php
		                if (wp_get_attachment_url($playtime_before_registration_data_file)){
			                $file= wp_get_attachment_link($playtime_before_registration_data_file);
			                echo $file;
                        ?>
                            <a href="#" class="remove-file">Remove Data File</a>
                            <input type="hidden" value="<?= $playtime_before_registration_data_file ?>" name="playtime_before_registration_data_file">
			                <?php
		                }else{
			                ?>
                            <a href="#" class="button upload-data-file">Upload Before File</a>
                            <a href="#" class="remove-file button button-secondary" style="display:none">Remove Data File</a>
                            <input type="hidden" name="playtime_before_registration_data_file" value="">
			                <?php
		                }
		                ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?= __("After Registration Data",PTDM_DOMAIN) ?></th>
                    <td>
		                <?php
		                if (wp_get_attachment_url($playtime_after_registration_data_file)){
			                $file= wp_get_attachment_link($playtime_after_registration_data_file);
			                echo $file;
                        ?>
                            <a href="#" class="remove-file">Remove Data File</a>
                            <input type="hidden" value="<?= $playtime_after_registration_data_file ?>" name="playtime_after_registration_data_file">
			                <?php
		                }else{
			                ?>
                            <a href="#" class="button upload-data-file">Upload After File</a>
                            <a href="#" class="remove-file button button-secondary" style="display:none">Remove Data File</a>
                            <input type="hidden" name="playtime_after_registration_data_file" value="">
			                <?php
		                }
		                ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?= __("Template Playtime Page",PTDM_DOMAIN) ?></th>
                    <td>
		                <?php
                        $pages = get_posts(array(
                                "posts_per_page" => -1,
                                "post_type"  => "page",
                                "post_status" => "any"
                        ));
                        ?>
                        <select class="select2-playtime-pages" name="playtime_template_page">
                            <option value="" <?php selected("", $playtime_template_page) ?>>Select Playtime Page</option>
                            <?php
                                foreach ($pages as $page){
                                    ?>
                                    <option value="<?= $page->ID ?>" <?php selected($page->ID, $playtime_template_page) ?>><?= $page->ID. "  - " .$page->post_title ." (".$page->post_status.")" ?></option>
	                                <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button("Save Changes"); ?>
    </form>
</div>