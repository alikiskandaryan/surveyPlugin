<?php
/*
 * For frontend tag management
 * */

function playtime_modify_tags_content($content){
	if (is_page() && is_main_query()){
		global $post;
		$template_page_id = get_option("playtime_template_page");
		if (isset($template_page_id) && !empty($template_page_id)){
			if ($template_page_id == $post->ID){
				$weeks_data = playtime_get_users_weeks_data();
                if (!empty($weeks_data)){
                    foreach ($weeks_data as $week => $week_data){
                        if (!empty($week_data)){
                            $saidYes = false;
                            $weeks_data_headings = $week_data['headings'];
                            $weeks_data_values = $week_data['values'];
                            foreach ($weeks_data_values as $data => $value){
                                $tag = "{".$week."_".$data."}";
								if ($week != "before" && $week != "after"){

                                    if ($data == "question_1"){
                                        if ($value == "Yes, we massaged!"){
                                            $value = '<span class="av_font_icon avia_animate_when_visible avia-icon-animate av-icon-style- avia-icon-pos-left avia_start_animation avia_start_delayed_animation" style="float:none;color:#33aa2a; border-color:#33aa2a;"><span class="av-icon-char" style="font-size:20px;line-height:20px;" aria-hidden="true" data-av_icon="" data-av_iconfont="entypo-fontello"></span></span>';
                                            $saidYes = true;
                                        }else{
                                            $saidYes = false;
                                            $value = '<span class="av_font_icon avia_animate_when_visible avia-icon-animate av-icon-style- avia-icon-pos-left avia_start_animation avia_start_delayed_animation" style="float:none;color:#dd0000; border-color:#dd0000;"><span class="av-icon-char" style="font-size:20px;line-height:20px;" aria-hidden="true" data-av_icon="" data-av_iconfont="entypo-fontello"></span></span>';
                                        }
                                    }

                                    if ($data == "question_5" && !empty($value)){
                                        $value = $value."/5";
                                    }elseif(empty($value) && $data == "question_5"){
                                        $value = "—";
                                    }

                                    if(empty($value) && ($data == "question_7" || $data == "question_6" || $data == "question_9" || $data == "question_10")){
                                        $value = "—";
                                    }

                                    if ($data == "question_8" && empty($value) && $saidYes){
                                        $value = "This option was skipped";
                                    }elseif ($data == "question_8" && !empty($value) && !$saidYes){
                                        $value = "";
                                    }

                                    if($data == "question_8" && !empty($value) && $saidYes){
                                        $heading = '<div class="playtime-question-heading-yes">'.$weeks_data_headings[$data].'</div>';
                                        $value = $heading.$value;
                                    }

                                    if ($data == "question_9" && empty($value) && $saidYes){
                                        $value = "This option was skipped";
                                    }

                                    if ($data == "question_3" && empty($value) && !$saidYes){
                                        $value = "This option was skipped";
                                    }elseif ($data == "question_3" && !empty($value) && $saidYes){
                                        $value = "";
                                    }

                                    if ($data == "question_4" && empty($value) && !$saidYes){
                                        $value = "This option was skipped";
                                    }elseif ($data == "question_4" && !empty($value) && $saidYes){
                                        $value = "";
                                    }

                                    if(($data == "question_4" || $data == "question_3") && !empty($value) && !$saidYes){
                                        $heading = '<div class="playtime-question-heading-no">'.$weeks_data_headings[$data].'</div>';
                                        $value = $heading.$value;
                                    }

								}
	                            $content = str_replace($tag,$value, $content);
                            }
                        }
                    }
                }
                $content = preg_replace("/({)([a-z]\w+)(})/", "",$content);
			}
		}
	}
	return $content;
}
add_filter("the_content","playtime_modify_tags_content");

function playtime_get_users_weeks_data(){
    $weeks_data = array();
	$week1_file_id = get_option("playtime_week_1_data_file");
	if ($week1_file_id){
		$week1_file = get_attached_file($week1_file_id);
		$weeks_data["week_1"] = getUserData($week1_file);
	}

	$week2_file_id = get_option("playtime_week_2_data_file");
	if ($week2_file_id){
		$week2_file = get_attached_file($week2_file_id);
		$weeks_data["week_2"] = getUserData($week2_file);
	}

	$week3_file_id = get_option("playtime_week_3_data_file");
	if ($week3_file_id){
		$week3_file = get_attached_file($week3_file_id);
		$weeks_data["week_3"] = getUserData($week3_file);
	}

	$week4_file_id = get_option("playtime_week_4_data_file");
	if ($week4_file_id){
		$week4_file = get_attached_file($week4_file_id);
		$weeks_data["week_4"] = getUserData($week4_file);
	}

	$before_file_id = get_option("playtime_before_registration_data_file");
	if ($before_file_id){
		$before_file = get_attached_file($before_file_id);
		$weeks_data["before"] = getUserData($before_file);
	}

	$after_file_id = get_option("playtime_after_registration_data_file");
	if ($after_file_id){
		$after_file = get_attached_file($after_file_id);
		$weeks_data["after"] = getUserData($after_file);
	}
    return $weeks_data;
}

function playtimeFormatCSVData($csvMap){
	$dataMapped = array();
    $heading_data = array();
    if (!empty($csvMap[0])){
        $heading_data['date'] = $csvMap[0][0];
        $heading_data['email'] = $csvMap[0][1];
        for ($k=2; $k<count($csvMap[0]); $k++){
            $heading_data['question_'.($k-1)] = $csvMap[0][$k];
        }
    }
	for ($i = 1; $i<count($csvMap); $i++){
		$dataMappedSingle = array(
			"date" => $csvMap[$i][0],
			"email" => $csvMap[$i][1]
		);
		for ($j=2; $j<count($csvMap[$i]); $j++){
			$dataMappedSingle["question_".($j-1)] = $csvMap[$i][$j];
		}
		$dataMapped[] = $dataMappedSingle;
	}
    return array(
        "headings" => $heading_data,
        "values"   => $dataMapped,
    );
}

function getUserData($dataMainFile){
	$selectedEmail = null;
	if (is_user_logged_in()){
		global $current_user;
		$selectedEmail = $current_user->user_email;
	}

	if (isset($_GET['summery_email']) && !empty($_GET['summery_email'])){
		$selectedEmail = $_GET['summery_email'];
	}
	if ($selectedEmail !== null){
		$csv = array_map('str_getcsv', file($dataMainFile));
		$dataMain = playtimeFormatCSVData($csv);
		$mappedData = $dataMain['values'];
		$cUserData = array();
		if (!empty($mappedData)){
			foreach ($mappedData as $data){
				if (strtolower($data['email']) == strtolower($selectedEmail)){
					$cUserData = $data;
					break;
				}
			}
		}
		$headings = $dataMain['headings'];
		return array(
			"headings" => $headings,
			"values"   => $cUserData,
		);
	}
	return array(
		"headings" => array(),
		"values"   => array(),
	);
}

function email_selection_form_shortcode(){
	ob_start();
	$email_entered = "";
	if (is_user_logged_in()){
		global $current_user;
		$email_entered = $current_user->user_email;
	} 

	if (isset($_GET['summery_email']) && !empty($_GET['summery_email'])){
		$email_entered = $_GET['summery_email'];
	}

	?>
	<form action="" class="playtime-custom-email-selection-form">

		<p class="form-row wfacp-form-control-wrapper  wfacp_field_required wfacp-anim-wrap" id="summery_email_field" data-priority="20">
			<label for="summery_email" class="wfacp-form-control-label">Email
				<span class="woocommerce-input-wrapper">
					<input type="text" class="input-text wfacp-form-control" name="summery_email" id="summery_email" placeholder="" value="<?= $email_entered ?>">
				</span>
		</p>
		<p class="form-row">
			<button type="submit" class="button wfacp-coupon-btn" value="Open Summery">Open Summary</button>
		</p>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode("playtime-summery-email-form","email_selection_form_shortcode");
