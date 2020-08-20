<?php

$font_family_string = $this->get_font_family_string($selected_backup_font_info, $selected_font_info);

foreach($target_tag_list as $target_tag):
	
	if($options['font_use_'.$target_tag]=="yes"):
		
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

if(!empty($options['rocketfont_class_id_list'])):
	echo $options['rocketfont_class_id_list'];
	?>
	{font-family:<?php echo $font_family_string;?>;}
<?php 
endif; 
?>
