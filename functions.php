<?php

function print_thecountry_list($country)
{

	//	$country_list[$country_name]['country_code']
	global $country_list;

	if(empty($country_list)){

		$file_location = dirname(__FILE__)."/countries.ser"; 
		if ($fd = fopen($file_location,'r')){
			$country_list_ser = fread($fd,filesize($file_location));
			fclose($fd);
		}
		$country_list = array();
		$country_list = unserialize($country_list_ser);
	}

	$country_name ="";
	foreach($country_list as $k => $v)
	{
		$check_value = "";
		if ($country == $v['country_code']){
	   		$check_value = " SELECTED ";
			$country_name = $k;
		}

		$country_code = $v['country_code'];
		$output_string = '<option value="' . $country_code . '" ';
		$output_string .= $check_value . '>';
		$output_string .= $k . '</option>';
		echo $output_string;
		echo "\n";

	}

	return $country_name;
}


function has_provinces($country)
{
	//
	//	Province list
	//	$province_list[$country_name][$province_name]['province_code'];

	global $province_list;

	if(empty($province_list)){
		$file_location = dirname(__FILE__)."/province_list.ser"; 
		if ($fd = fopen($file_location,'r')){
			$province_list_ser = fread($fd,filesize($file_location));
			fclose($fd);
		}
		$province_list = unserialize($province_list_ser);
	}


	if(empty($province_list[$country])) 
		return false;
	else
		return true;

}

function print_theprovince_list($country, $province)
{
	//
	//	Province list
	//	$province_list[$country_name][$province_name]['province_code'];

	global $province_list;

	if(empty($province_list)){
		$file_location = dirname(__FILE__)."/province_list.ser"; 
		if ($fd = fopen($file_location,'r')){
			$province_list_ser = fread($fd,filesize($file_location));
			fclose($fd);
		}
		$province_list = unserialize($province_list_ser);
	}

	$state_name ="";
	foreach($province_list[$country] as $k=>$v)
	{
		$check_value = "";
		if ($province == $v['province_code']){
	   		$check_value = ' SELECTED ';
			$state_name = $k;
		}
		echo '<option value="'.$v['province_code'].'" '.$check_value .'>'.$k.'</option>';
		echo "\n";
	}

	return $state_name;

}



// This function print for selector clock size list

function print_thesize_list($size){
	 $size_list = array("50","75","100","150","180","200","250","300");

	 echo "\n";
	foreach($size_list as $isize)
	{
		$check_value = "";
		if ($isize == $size)
	   		$check_value = ' SELECTED ';
		echo '<option value="'.$isize.'" '.$check_value .'>'.$isize.'</option>';
		echo "\n";
	}
}







// This function print for selector clock color list

function print_textcolor_list($text_color){

	 $color_list =array(
		   "#FF0000" => "Red",
		   "#CC033C" =>"Crimson",
		   "#FF6F00" =>"Orange",
		   "#FFCC00" =>"Gold",
		   "#009000" =>"Green",
		   "#00FF00" =>"Lime",
  		   "#0000FF" => "Blue",
		   "#000090" =>"Navy",
		   "#FE00FF" =>"Indigo",
		   "#F99CF9" =>"Pink",
		   "#900090" =>"Purple",
		   "#000000" =>"Black",
		   "#FFFFFF" =>"White",
		   "#DDDDDD" =>"Grey",
		   "#666666" =>"Gray"
         );

	 echo "\n";
	foreach($color_list as $key=>$tcolor)
	{
		$check_value = "";
		if ($text_color == $key)
	   		$check_value = ' SELECTED ';

		$white_text = "";
		if ($key == "#000000" OR $key == "#000090" OR $key == "#666666" OR $key == "#0000FF" )
	   		$white_text = ';color:#FFFFFF ';
		echo '<option value="'.$key.'" style="background-color:'.$key. $white_text .'" '.$check_value .'>'.$tcolor.'</option>';
		echo "\n";
	}


}


// This function print for selector clock color list

function print_bordercolor_list($text_color){

print "<br> TEXT COLOR:"  . $text_color;

	 $color_list =array(
	      "#FF0000" => "Red",
	      "#CC033C" => "Crimson",
	      "#FF6F00" => "Orange",
	      "#FFCC00" => "Gold",
	      "#009000" => "Green",
	      "#00FF00" => "Lime",
	      "#963939" => "Brown",
	      "#C69633" => "Brass",
	      "#0000FF" => "Blue",
	      "#000090" => "Navy",
	      "#FE00FF" => "Indigo",
	      "#F99CF9" => "Pink",
	      "#900090" => "Purple",
	      "#000000" => "Black",
	      "#FFFFFF" => "White",
	      "#DDDDDD" => "Grey",
	      "#666666" => "Gray",
	      "#F6F9F9;" => "Silver");


	 echo "\n";
	foreach($color_list as $key=>$tcolor)
	{
		$check_value = "";
		if ($text_color == $key)
	   		$check_value = ' SELECTED ';

		$white_text = "";
		if ($key == "#000000" OR $key == "#000090" OR $key == "#666666" OR $key == "#0000FF" )
	   		$white_text = ';color:#FFFFFF ';
		echo '<option value="'.$key.'" style="background-color:'.$key. $white_text .'" '.$check_value .'>'.$tcolor.'</option>';
		echo "\n";
	}



}


// This function print for selector clock color list

function print_backgroundcolor_list($text_color){

	 $color_list =array(
	       "#FF0000" => "Red",
	       "#CC033C" => "Crimson",
	       "#FF6F00" => "Orange",
	       "#F9F99F" => "Golden",
	       "#FFFCCC" => "Almond",
	       "#F6F6CC" => "Beige",
	       "#209020" => "Green",
	       "#963939" => "Brown",
	       "#00FF00" => "Lime",
      	       "#99CCFF" => "Light Blue",
	       "#000090" => "Navy",
	       "#FE00FF" => "Indigo",
	       "#F99CF9" => "Pink",
	       "#993CF3" => "Violet",
	       "#000000" => "Black",
	       "#FFFFFF" => "White",
	       "#DDDDDD" => "Grey",
	       "#666666" => "Gray",
	       "#F6F9F9;" => "Silver");


	 echo "\n";
	foreach($color_list as $key=>$tcolor)
	{
		$check_value = "";
		if ($text_color == $key)
	   		$check_value = ' SELECTED ';

		$white_text = "";
		if ($key == "#000000" OR $key == "#000090" OR $key == "#666666" OR $key == "#0000FF" )
	   		$white_text = ';color:#FFFFFF ';
		echo '<option value="'.$key.'" style="background-color:'.$key. $white_text .'" '.$check_value .'>'.$tcolor.'</option>';
		echo "\n";
	}

}



// This function print for selector clock color list

function print_type_list($type){

	 $type_list =array(
	       "100" => "Analog Basic",
	       "121" => "World Globe",
	       "119" => "Analog with Flag ",
	       "110" => "Analog Shiny 3D",
	       "1000" => "Digital Basic",
	       "1100" => "Digital Time and Date",
	       "1025" => "Digital Stick-it Note",
	       "1024" => "Digital Flip over");


	 echo "\n";
	foreach($type_list as $key=>$ttype)
	{
		$check_value = "";
		if ($type == $key)
	   		$check_value = ' SELECTED ';

		echo '<option value="'.$key.'" style="background-color:'.$key .'" '.$check_value .'>'.$ttype.'</option>';
		echo "\n";
	}

}

?>