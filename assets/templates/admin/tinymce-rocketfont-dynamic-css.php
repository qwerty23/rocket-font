<?php
$double_qute = '';
if(strpos($selected_font_info['font_name'], ' ') > 0){
	$double_qute = '"';
}
?>

body {font-family: <?php if($selected_backup_font_info['font_name']) { echo $selected_backup_font_info['font_name'] . ",";}?> <?php echo $double_qute . $selected_font_info['font_name'] . $double_qute?>, <?php if($selected_backup_font_info['generic_font_family']) { echo $selected_backup_font_info['generic_font_family'];}else{ echo "sans-serif";}?>;}
.rocket-font{font-family: <?php echo $double_qute . $selected_font_info['font_name'] . $double_qute?>, sans-serif;}
<?php
foreach($target_tag_list as $target_tag):
	echo $target_tag;?>{
		font-family: <?php if($selected_backup_font_info['font_name']) { echo $selected_backup_font_info['font_name'] . ",";}?> <?php echo $double_qute . $selected_font_info['font_name'] . $double_qute?>, <?php if($selected_backup_font_info['generic_font_family']) { echo $selected_backup_font_info['generic_font_family'];}else{ echo "sans-serif";}?>;
		<?php
		if($options['font_color_'.$target_tag]){ ?> color:<?php echo $options['font_color_'.$target_tag]?>;<?php } 
		if($options['font_weight_'.$target_tag]){ ?> font-size:<?php echo $options['font_weight_'.$target_tag]?>px;<?php } 
		if($options['font_lineheight_'.$target_tag]){ ?> line-height:<?php echo $options['font_lineheight_'.$target_tag]?>px; <?php } 
		?> 
		}
	<?php
endforeach;
?>
