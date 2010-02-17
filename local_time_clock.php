<?php
/*
Plugin Name: Local Time Clock
Description: Display a flash clock on your sidebar set automatically to your location's timezone. Choice of clocks, colors and sizes.
Author: localtimes.info
Version: 1.0
Author URI: http://localtimes.info
Plugin URI: http://localtimes.info/wordpress-clock-plugin/
*/



function local_time_clock_init() 
{

     if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
    	   return; 

    function local_time_clock_control() 
    {

        $newoptions = get_option('local_time_clock');
    	$options = $newoptions;
	$options_flag=0;


    	if ( empty($newoptions) )
	{
	   $options_flag=1;
      	   $newoptions = array(
	   	'title'=>'Washington Time',
           	'titleflag'=>'1', 
           	'transparentflag'=>'0', 
           	'ampmflag'=>'0', 
           	'country' => 'US',
           	'country_name' => 'United States',
		'state' => 'US-DC',
		'state_name' => 'District of Columnbia',
           	'city' => 'Washington',
           	'size' => '150',
           	'type' => '1000',
           	'typeflag' => '1',
           	'text_color' => '#000000',
           	'border_color' => '#963939',
           	'background_color' => '#FFFFFF'
	   );
	}

	if ( $_POST['local-clock-submit'] ) {
	     $options_flag=1;
              $newoptions['title'] = strip_tags(stripslashes($_POST['local-clock-title']));
              $newoptions['titleflag'] = strip_tags(stripslashes($_POST['local-clock-titleflag']));
              $newoptions['transparentflag'] = strip_tags(stripslashes($_POST['local-clock-transparentflag']));
              $newoptions['ampmflag'] = strip_tags(stripslashes($_POST['local-clock-ampmflag']));
              $newoptions['country'] = strip_tags(stripslashes($_POST['local-clock-country']));
              $newoptions['state'] = strip_tags(stripslashes($_POST['local-clock-state']));
              $newoptions['city'] = strip_tags(stripslashes($_POST['local-clock-city']));
              $newoptions['country_name'] = strip_tags(stripslashes($_POST['local-clock-country_name']));
              $newoptions['state_name'] = strip_tags(stripslashes($_POST['local-clock-state_name']));
              $newoptions['size'] = strip_tags(stripslashes($_POST['local-clock-size']));
              $newoptions['type'] = strip_tags(stripslashes($_POST['local-clock-type']));
              $newoptions['typeflag'] = strip_tags(stripslashes($_POST['local-clock-typeflag']));
              $newoptions['text_color'] = strip_tags(stripslashes($_POST['local-clock-text-color']));
              $newoptions['border_color'] = strip_tags(stripslashes($_POST['local-clock-border-color']));
              $newoptions['background_color'] = strip_tags(stripslashes($_POST['local-clock-background-color']));
        }

      	if ( $options_flag ==1 ) {
              $options = $newoptions;
              update_option('local_time_clock', $options);
      	}


      	// Extract value from vars
      	$title = htmlspecialchars($options['title'], ENT_QUOTES);
      	$titleflag = htmlspecialchars($options['titleflag'], ENT_QUOTES);
      	$transparent_flag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);
      	$country = htmlspecialchars($options['country'], ENT_QUOTES);
      	$state = htmlspecialchars($options['state'], ENT_QUOTES);
      	$country_name = htmlspecialchars($options['country_name'], ENT_QUOTES);
      	$state_name = htmlspecialchars($options['state_name'], ENT_QUOTES);
      	$city = htmlspecialchars($options['city'], ENT_QUOTES);
      	$size = htmlspecialchars($options['size'], ENT_QUOTES);
      	$type = htmlspecialchars($options['type'], ENT_QUOTES);
      	$typeflag = htmlspecialchars($options['typeflag'], ENT_QUOTES);
      	$ampmflag = htmlspecialchars($options['ampmflag'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);

      	echo '<ul><li style="text-align:center;list-style: none;"><label for="clock-title">Local Time Clock<br> by <a href="http://localtimes.info">localtimes.info</a></label></li>';


       	// Get country, state, city

       	echo '<li style="list-style: none;"><label for="local-clock-country">Country:'.
               '<select id="local-clock-country" name="local-clock-country" style="width:100%">';

     	$country_name = print_thecountry_list($country);
      	echo '<input id="local-clock-country_name" name="local-clock-country_name" type="hidden" value="'.$country_name.'" />';
      	echo '</select></label></li>';


       	// Get province

       	echo '<li style="list-style: none;"><label for="local-clock-state">Province or State:'.
               '<select id="local-clock-state" name="local-clock-state" style="width:100%">';
     	$state_name = print_theprovince_list($country,$state);
      	echo '<input id="local-clock-state_name" name="local-clock-state_name" type="hidden" value="'.$state_name.'" />';
      	echo '</select></label></li>';

       	// Get city

        echo '<li style="list-style: none;"><label for="local-clock-city">City (optional): <input style="width: 90%;" id="local-clock-city" name="local-clock-city" type="text" value="'.$city.'" /> </label></li>';



      	// Set clock type
      	echo '<li style="list-style: none;"><label for="local-clock-typeflag">'.'Clock Type:&nbsp;';
       	echo '<select id="local-clock-typeflag" name="local-clock-typeflag"  style="width:75px" >';
      	print_type_list($typeflag);
      	echo '</select></label>';
      	echo '</li>';


      	// Set Clock size
	echo "\n";
      	echo '<li style="list-style: none;text-align:bottom"><label for="local-clock-size">'.'Clock Size: &nbsp;'.
         '<select id="local-clock-size" name="local-clock-size"  style="width:75px">';
      	print_thesize_list($size);
      	echo '</select></label></li>';


      	// Set Text Clock color
      	echo '<li style="list-style: none;"><label for="local-clock-text-color">'.'Text Color: &nbsp;';
       	echo '<select id="local-clock-text-color" name="local-clock-text-color"  style="width:75px" >';
      	print_textcolor_list($text_color);
      	echo '</select></label>';
      	echo '</li>';

      	// Set Border Clock color
      	echo '<li style="list-style: none;"><label for="local-clock-border-color">'.'Border Color:&nbsp;';
       	echo '<select id="local-clock-border-color" name="local-clock-border-color"  style="width:75px" >';
      	print_bordercolor_list($border_color);
      	echo '</select></label>';
      	echo '</li>';

      	// Set Background Clock color
      	echo '<li style="list-style: none;"><label for="local-clock-background-color">'.'Background Color:&nbsp;';
       	echo '<select id="local-clock-background-color" name="local-clock-background-color"  style="width:75px" >';
      	print_backgroundcolor_list($background_color);
      	echo '</select></label>';
      	echo '</li>';







	//   Transparent option

	$transparent_checked = "";
	if ($transparent_flag =="1")
	   $transparent_checked = "CHECKED";

	echo "\n";
        echo '<li style="list-style: none;"><label for="local-clock-transparentflag"> Transparent: 
	<input type="checkbox" id="local-clock-transparentflag" name="local-clock-transparentflag" value=1 '.$transparent_checked.' /> 
	</label></li>';

	//   ampm option

	$ampm_checked = "";
	if ($ampm_flag =="1")
	   $ampm_checked = "CHECKED";

	echo "\n";
        echo '<li style="list-style: none;"><label for="local-clock-ampmflag"> am/pm format: 
	<input type="checkbox" id="local-clock-ampmflag" name="local-clock-ampmflag" value=1 '.$ampm_checked.' /> 
	</label></li>';

      	// Hidden "OK" button
      	echo '<label for="local-clock-submit">';
      	echo '<input id="local-clock-submit" name="local-clock-submit" type="hidden" value="Ok" />';
      	echo '</label>';


	//	Title header option	
	if($city)
		$title = UCWords($city) . " Time";
	elseif($state_name)
		$title = $state_name . " Time";
	elseif($country_name)
		$title = $country_name . " Time";

        echo '<label for="local-clock-title"> <input type="hidden" id="local-clock-title" name="local-clock-title" value="'.$title.'" /> </label>';



	$title_checked = "";
	if ($titleflag =="1")
	   $title_checked = "CHECKED";

	echo "\n";
        echo '<li style="list-style: none;"><label for="local-clock-titleflag"> City Title: 
	<input type="checkbox" id="local-clock-titleflag" name="local-clock-titleflag" value=1 '.$title_checked.' /> 
	</label></li>';

	echo '</ul>';


    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	OUTPUT CLOCK WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

     function local_time_clock($args) 
     {

	// Get values 
      	extract($args);

      	$options = get_option('local_time_clock');


	// Get Title,Location,Size,

      	$title = htmlspecialchars($options['title'], ENT_QUOTES);
      	$titleflag = htmlspecialchars($options['titleflag'], ENT_QUOTES);
      	$transparentflag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);
      	$ampmflag = htmlspecialchars($options['ampmflag'], ENT_QUOTES);
      	$country = htmlspecialchars($options['country'], ENT_QUOTES);
      	$state = htmlspecialchars($options['state'], ENT_QUOTES);
      	$country_name = htmlspecialchars($options['country_name'], ENT_QUOTES);
      	$state_name = htmlspecialchars($options['state_name'], ENT_QUOTES);
      	$city = htmlspecialchars($options['city'], ENT_QUOTES);
      	$size = htmlspecialchars($options['size'], ENT_QUOTES);
      	$type = htmlspecialchars($options['type'], ENT_QUOTES);
      	$typeflag = htmlspecialchars($options['typeflag'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);


	echo $before_widget; 




	// Output title
	echo $before_title . $title . $after_title; 


	// Output Clock


	$target_url= "http://localtimes.info/$country_name/";
	if ($state_name)
   	   $target_url .= $state_name ."/";

	$target_url .= $city ."/";
	$country_name = str_replace(" ", "+", $country_name);
	$city= str_replace(" ", "+", $city);
	$country_code = strtolower($country);
	$province_string = "&province_string=".$state_name;

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


	if($titleflag != 1){
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
	if($typeflag == 1)
    	    $widget_call_string .= "&widget_number=1000";
	else
	    $widget_call_string .= "&widget_number=100";

	echo '<script type="text/javascript" src="'.$widget_call_string . '"></script></div><!-end of code-->';




	echo $after_widget;


    }
  
    register_sidebar_widget('Local Time Clock', 'local_time_clock');
    register_widget_control('Local Time Clock', 'local_time_clock_control', 245, 300);


}


add_action('plugins_loaded', 'local_time_clock_init');


// This function print for selector clock color list

include("functions.php");


?>