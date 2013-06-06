<?php
/*
  Plugin Name: Local Time Clock
  Description: Display a flash clock on your sidebar set automatically to your location's timezone. Choice of clocks, colors and sizes.
  Author: enclick
  Version: 1.2
  Author URI: http://localtimes.info
  Plugin URI: http://localtimes.info/wordpress-clock-plugin/
*/

require_once("functions.php");

$province_list;
$country_list;

/**
 * Add function to widgets_init that'll load our widget.
 */

add_action( 'widgets_init', 'load_local_time_clock' );

/**
 * Register our widget.
 * 'local_time_clock' is the widget class used below.
 *
 */
function load_local_time_clock() {
	register_widget( 'local_time_clock' );
}


/*******************************************************************************************
 *
 *       Local Time Clock  class.
 *       This class handles everything that needs to be handled with the widget:
 *       the settings, form, display, and update.
 *
 *********************************************************************************************/
class local_time_clock extends WP_Widget
{

	/*******************************************************************************************
	 *
	 *
	 * Widget setup.
	 *
	 *
	 ********************************************************************************************/
	function local_time_clock() {
		#Widget settings
		$widget_ops = array( 'description' => __('Displays a local time clock for any city', 'local_time_clock') );

		#Widget control settings
		$control_ops = array( 'width' => 200, 'height' => 550, 'id_base' => 'local_time_clock' );

		#Create the widget
		$this->WP_Widget( 'local_time_clock', __('Local Time Clock', 'local_time_clock'), $widget_ops, $control_ops );
	}


   	/*******************************************************************************************
	 *
	 *
	 * Update the widget settings.
	 *
	 *
	 *******************************************************************************************/
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		global $country_list;
		if(empty($country_list))
		{
			$file_location = dirname(__FILE__)."/countries.ser"; 
			if ($fd = fopen($file_location,'r')){
				$country_list_ser = fread($fd,filesize($file_location));
				fclose($fd);
			}
			$country_list = array();
			$country_list = unserialize($country_list_ser);
		}

		#
		#			COUNTRY
		#

		$country = strip_tags(stripslashes($new_instance['country']));
		$instance['country'] = $country;
		ob_start();
		$country_name = print_thecountry_list($country);
		ob_end_clean();
		$instance['country_name'] = $country_name;

		#
		#	STATE
		#

		if(has_provinces($country))
		{
			$old_state = strip_tags(stripslashes($old_instance['state']));
			$state = strip_tags(stripslashes($new_instance['state']));
			$instance['state'] = $state;
			ob_start();
  			$state_name = print_theprovince_list($country,$state);
			ob_end_clean();
			if($old_state != $state)
				$new_country_flag = 1;
		}
		else
		{
			$state = "";
			$state_name = "";
		}

		$instance['state'] = $state;
		$instance['state_name'] = $state_name;


		$instance['new_country_flag'] = $new_country_flag;
		#
		#	CITY
		#

		$new_country = strip_tags(stripslashes($new_instance['country']));
		$old_country = strip_tags(stripslashes($old_instance['country']));

		if($old_country != $new_country && empty($new_country_flag)){
			$city = $country_list[$country_name]['capital_name'];
			$new_country_flag = "";
		}
		else
			$city = strip_tags(stripslashes($new_instance['city']));

		$instance['city'] = UCWords($city);


		#
		#	TITLE
		#

		if($city)
			$title = UCWords($city);
			#$title = UCWords($city) . " Time";
		elseif($state_name)
			$title = $state_name;
			#$title = $state_name . " Time";
		elseif($country_name)
			$title = $country_name;				
			#$title = $country_name . " Time";				

		$instance['title'] = $title;

		#
		#
		#

		$instance['tflag'] = strip_tags(stripslashes($new_instance['tflag']));
		$instance['transparentflag'] = strip_tags(stripslashes($new_instance['transparentflag']));
		$instance['ampmflag'] = strip_tags(stripslashes($new_instance['ampmflag']));

		$instance['size'] = strip_tags(stripslashes($new_instance['size']));
		$instance['typeflag'] = strip_tags(stripslashes($new_instance['typeflag']));
		$instance['text_color'] = strip_tags(stripslashes($new_instance['text_color']));
		$instance['border_color'] = strip_tags(stripslashes($new_instance['border_color']));
		$instance['background_color'] = strip_tags(stripslashes($new_instance['background_color']));


		return $instance;

	}


	/*******************************************************************************************
	 *
	 *      Displays the widget settings controls on the widget panel.
	 *      Make use of the get_field_id() and get_field_name() function
	 *      when creating your form elements. This handles the confusing stuff.
	 *
	 *
	 ********************************************************************************************/
	function form( $instance )
	{




		#
		#       Set up some default widget settings
		#

		$default = array(
			'title' => 'London',
			'tflag'=>'0', 
			'transparentflag'=>'0', 
			'ampmflag'=>'0', 
			'country' => 'GB',
			'new_country_flag' => '',
			'country_name' => 'United Kingdom',
			'state' => '',
			'state_name' => '',
			'city' => 'London',
			'size' => '150',
			'typeflag' => '1000',
			'text_color' => '#000000',
			'border_color' => '#963939',
			'background_color' => '#FFFFFF'
			);

		if(!isset($instance['country']))
			$instance = $default;

		// Extract value from instance
		$title = format_to_edit($instance['title']);
		#$title = htmlspecialchars($instance['title'], ENT_QUOTES);
		$tflag = htmlspecialchars($instance['tflag'], ENT_QUOTES);
		$transparent_flag = htmlspecialchars($instance['transparentflag'], ENT_QUOTES);
		$country = htmlspecialchars($instance['country'], ENT_QUOTES);
		$new_country_flag = htmlspecialchars($instance['new_country_flag'], ENT_QUOTES);
		$state = htmlspecialchars($instance['state'], ENT_QUOTES);
		$country_name = htmlspecialchars($instance['country_name'], ENT_QUOTES);
		$state_name = htmlspecialchars($instance['state_name'], ENT_QUOTES);
		$city = htmlspecialchars($instance['city'], ENT_QUOTES);
		$size = htmlspecialchars($instance['size'], ENT_QUOTES);
		$typeflag = htmlspecialchars($instance['typeflag'], ENT_QUOTES);
		$ampmflag = htmlspecialchars($instance['ampmflag'], ENT_QUOTES);
		$text_color = htmlspecialchars($instance['text_color'], ENT_QUOTES);
		$border_color = htmlspecialchars($instance['border_color'], ENT_QUOTES);
		$background_color = htmlspecialchars($instance['background_color'], ENT_QUOTES);



		#
		#
		#               START FORM OUTPUT
		#
		#

		echo '<div style="margin-bottom:10px"></div>';


		// Get country, state, city

		echo '<p><label for="' .$this->get_field_id( 'country' ). '">Country <span style="display:inline;font-size:9px">(Save after selecting)</span>'.
			'<select id="' .$this->get_field_id( 'country' ). '" name="' .$this->get_field_name( 'country' ). '" style="width:100%">';
		$country_name = print_thecountry_list($country);
		echo '</select></label></p>';


		// Get province 
		if(has_provinces($country))
		{
			echo '<p><label for="' .$this->get_field_id( 'state' ). '">Province or State <span style="display:inline;font-size:9px">(Save after selecting)</span>'.
				'<select id="' .$this->get_field_id( 'state' ). '" name="' .$this->get_field_name( 'state' ). '" style="width:100%">';
			$state_name = print_theprovince_list($country,$state);
			echo '</select></label></p>';
		}
		else
			$state ="";
			
		if($new_country_flag == 1)
			$city ="";

		// Get city
		echo '<p><label for="' .$this->get_field_id( 'city' ). '">City: ';
		echo '<input style="width: 90%;"t id="' .$this->get_field_id( 'city' ). '" name="' .$this->get_field_name('city' ). '" type="text" value="'.$city.'" /> </label></p>';



		// Set clock type
		echo '<p><label for="' .$this->get_field_id( 'typeflag' ). '">'.'Clock Type:&nbsp;';
		echo '<select id="' .$this->get_field_id( 'typeflag' ). '" name="' .$this->get_field_name('typeflag' ). '"  style="width:125px" >';
		print_type_list($typeflag);
		echo '</select></label>';
		echo '</p>';


		// Set Clock size
		echo "\n";
		echo '<p><label for="' .$this->get_field_id( 'size' ). '">'.'Clock Size: &nbsp;'.
			'<select id="' .$this->get_field_id( 'size' ). '" name="' .$this->get_field_name('size' ). '"  style="width:75px">';
		print_thesize_list($size);
		echo '</select></label></p>';


		// Set Text Clock color
		echo '<p><label for="' .$this->get_field_id( 'text_color' ). '">'.'Text Color: &nbsp;';
		echo '<select id="' .$this->get_field_id( 'text_color' ). '" name="' .$this->get_field_name('text_color' ). '"  style="width:75px" >';
		print_textcolor_list($text_color);
		echo '</select></label>';
		echo '</p>';

		if($typeflag < 1000)
		{
			// Set Border Clock color
			echo '<p><label for="' .$this->get_field_id( 'border_color' ). '">'.'Border Color:&nbsp;';
			echo '<select id="' .$this->get_field_id( 'border_color' ). '" name="' .$this->get_field_name('border_color' ). '"  style="width:75px" >';
			print_bordercolor_list($border_color);
			echo '</select></label>';
			echo '</p>';
		}
		else{
			echo '<label for="' .$this->get_field_id( 'border_color' ). '">';
			echo '<input id="' .$this->get_field_id( 'border_color' ). '" name="' .$this->get_field_name( 'border_color' ). '" type="hidden" value="'.$border_color.'" /></label>';
		}

		// Set Background Clock color
		echo '<p><label for="' .$this->get_field_id( 'background_color' ). '">'.'Background Color:&nbsp;';
		echo '<select id="' .$this->get_field_id( 'background_color' ). '" name="' .$this->get_field_name('background_color' ). '"  style="width:75px" >';
		print_backgroundcolor_list($background_color);
		echo '</select></label>';
		echo '</p>';


		//   Transparent option

		$transparent_checked = "";
		if ($transparent_flag =="1")
			$transparent_checked = "CHECKED";

		echo "\n";
		echo '<p><label for="' .$this->get_field_id( 'transparentflag' ). '"> Transparent: 
		   <input type="checkbox"t id="' .$this->get_field_id( 'transparentflag' ). '" name="' .$this->get_field_name('transparentflag' ). '" value=1 '.$transparent_checked.' /> 
		   </label></p>';

		//   ampm option

		$ampm_checked = "";
		if ($ampmflag =="1")
			$ampm_checked = "CHECKED";

		echo "\n";
		echo '<p><label for="' .$this->get_field_id( 'ampmflag' ). '"> am/pm format: 
		      <input type="checkbox"t id="' .$this->get_field_id( 'ampmflag' ). '" name="' .$this->get_field_name('ampmflag' ). '" value=1 '.$ampm_checked.' /> 
		      </label></p>';


		$title_checked = "";
		if ($tflag =="1")
			$title_checked = "CHECKED";

		echo '<p><label for="' .$this->get_field_id( 'tflag' ). '"> City Title: 
		  <input type="checkbox"t id="' .$this->get_field_id( 'tflag' ). '" name="' .$this->get_field_name('tflag' ). '" value=1 '.$title_checked.' /> 
		  </label></p>';

		echo "\n";
		echo '<label for="' .$this->get_field_id( 'title' ). '">';
		echo '<input type="hidden" id="' .$this->get_field_id( 'title' ). '" name="' .$this->get_field_name( 'title' ). '" value="' . $title . '" />';
		echo '</label>';



    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	OUTPUT CLOCK WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

	function widget ($args, $instance) 
	{


		// Get values 
      	extract($args);


		// Get Title,Location,Size,
		$title = apply_filters('widget_title', $instance['title'] );
		#      	$title = htmlspecialchars($instance['title'], ENT_QUOTES);
      	$tflag = htmlspecialchars($instance['tflag'], ENT_QUOTES);
      	$transparentflag = htmlspecialchars($instance['transparentflag'], ENT_QUOTES);
      	$ampmflag = htmlspecialchars($instance['ampmflag'], ENT_QUOTES);
      	$country = htmlspecialchars($instance['country'], ENT_QUOTES);
      	$state = htmlspecialchars($instance['state'], ENT_QUOTES);
      	$country_name = htmlspecialchars($instance['country_name'], ENT_QUOTES);
      	$state_name = htmlspecialchars($instance['state_name'], ENT_QUOTES);
      	$city = htmlspecialchars($instance['city'], ENT_QUOTES);
      	$size = htmlspecialchars($instance['size'], ENT_QUOTES);
      	$typeflag = htmlspecialchars($instance['typeflag'], ENT_QUOTES);
      	$text_color = htmlspecialchars($instance['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($instance['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($instance['background_color'], ENT_QUOTES);


		echo $before_widget; 




		// Output title
		$title = UCWords($title);
		echo $before_title . $after_title; 


		// Output Clock


		$target_url= "http://localtimes.info/$country_name/";
		if ($state_name != "")
			$target_url .= $state_name ."/";

		$target_url .= $city ."/";
		$target_url= str_replace(" ", "_", $target_url);


		$country_name = str_replace(" ", "+", $country_name);
		$city= str_replace(" ", "+", $city);
		$country_code = strtolower($country);

		if ($state_name != "")
			$province_string = "&province=".$state_name;

		$widget_call_string = 'http://localtimes.info/wp_clock.php?country='.$country_name;
		$widget_call_string .= $province_string . '&city='.$city;

		$transparent_string = "&hbg=0";
		if($transparentflag == 1){
			$transparent_string = "&hbg=1";
			$background_color="";
		}

		$ampm_string = "&ham=1";
		if($ampmflag == 1)
			$ampm_string = "&ham=0";


		if($tflag != 1){
			$noscript_start = "<noscript>";
			$noscript_end = "</noscript>";
		}

		echo'<!-Local Time Clock widget - HTML code - localtimes.info --><div align="center" style="margin:15px 0px 0px 0px">';

		echo $noscript_start . '<div align="center" style="width:140px;border:1px solid #ccc;background:'.$background_color.' ;color:'.$text_color.' ;font-weight:bold">';
		echo '<a style="padding:2px 1px;margin:2px 1px;font-size:13px;line-height:16px;font-family:arial;text-decoration:none;color:'.$text_color. ' ;" href="'.$target_url.'">';
		echo '<img src="http://localtimes.info/images/countries/'.$country_code.'.png" border=0 style="border:0;margin:0;padding:0">&nbsp;&nbsp;'.$title.'</a></div>' . $noscript_end;

		$text_color = str_replace("#","",$text_color);
		$background_color = str_replace("#","",$background_color);
		$border_color = str_replace("#","",$border_color);

		$widget_call_string .= '&cp3_Hex='.$border_color.'&cp2_Hex='.$background_color.'&cp1_Hex='.$text_color. $transparent_string . $ampm_string. '&fwdt='.$size;

		if($typeflag == 1)$typeflag=1000;
		$widget_call_string .= "&widget_number=$typeflag";

		echo '<script type="text/javascript" src="'.$widget_call_string . '"></script></div><!-end of code-->';




		echo $after_widget;


    }
  


}




?>