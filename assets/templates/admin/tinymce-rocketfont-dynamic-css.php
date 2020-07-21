<?php
$double_qute = '"';
foreach($target_tag_list as $target_tag):
	
	if($options['font_use_'.$target_tag]=="yes"):
		
		echo $target_tag;
		?>
			{
			font-family: <?php if($selected_backup_font_info['font_name']) { echo $selected_backup_font_info['font_name'] . ",";}?> <?php echo $double_qute . $selected_font_info['font_name'] . $double_qute?>, <?php if($selected_backup_font_info['generic_font_family']) { echo $selected_backup_font_info['generic_font_family'];}else{ echo "sans-serif";}?>;
			<?php
			if($options['font_color_'.$target_tag]){ ?> color:<?php echo $options['font_color_'.$target_tag]?>;<?php } 
			if($options['font_weight_'.$target_tag]){ ?> font-size:<?php echo $options['font_weight_'.$target_tag]?>px;<?php } 
			if($options['font_lineheight_'.$target_tag]){ ?> line-height:<?php echo $options['font_lineheight_'.$target_tag]?>px; <?php } 
			?> 
			}
		<?php
	endif;
	
endforeach;

if(!empty($options['rocketfont_class_id_list'])) {
	echo $options['rocketfont_class_id_list'];
	?>
	{font-family:<?php
	if($selected_backup_font_info['font_name']) {
		echo $selected_backup_font_info['font_name'] . ",";
	} 
	
	if($selected_font_info['font_name']) {
		echo $double_qute . $selected_font_info['font_name'] . $double_qute . ",";
	} 
	?>
	
	<?php 
		if($selected_backup_font_info['generic_font_family']) {
		 	echo $selected_backup_font_info['generic_font_family'];
		} else{ echo "sans-serif";}?>
		 	;
		}
	<?php
}

