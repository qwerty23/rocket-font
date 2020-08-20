<?php
function detect_space($font_name, $double_qute = '"'){
	if (preg_match('/^\S.*\s.*\S$/', $font_name)){
		$font_name = $double_qute . $font_name . $double_qute;
	}
	return $font_name;
}

function get_font_family_string($selected_backup_font_info, $selected_font_info){
	return $selected_backup_font_info['font_name'] . ", " . detect_space($selected_font_info['font_name']) . ", " . $selected_backup_font_info['generic_font_family'] . ";";
}

$font_family_string = get_font_family_string($selected_backup_font_info, $selected_font_info);
$double_qute = '"';

if(!empty($selected_font_info["inline_font_face"])){
	echo $selected_font_info["inline_font_face"];
}

foreach($target_tag_list as $target_tag):
	
	if($options['font_use_'.$target_tag]=="yes"):
		
		if($target_tag == "body") return;
		
		echo ".editor-styles-wrapper ";
		echo $target_tag;
		
		?>
			{
			font-family: <?php echo $font_family_string?>;
			<?php
			if($options['font_color_'.$target_tag]){ ?> color:<?php echo $options['font_color_'.$target_tag]?>;<?php } 
			if($options['font_weight_'.$target_tag]){ ?> font-size:<?php echo $options['font_weight_'.$target_tag]?>px;<?php } 
			if($options['font_lineheight_'.$target_tag]){ ?> line-height:<?php echo $options['font_lineheight_'.$target_tag]?>px; <?php } 
			?> 
			}
		<?php
	endif;
	
endforeach;
 
?>
