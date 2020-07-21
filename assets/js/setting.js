jQuery(document).ready(function($){
	
	// var font_single_multi_select = document.querySelector('input[name=font_single_multi_select]');
	// font_single_multi_select.onchange = function() {
		// if(font_single_multi_select.checked){
			// $(".single-font").hide();
			// $(".multi-font").show();
		// }else{
			// $(".single-font").show();
			// $(".multi-font").hide();
		// }
	// };
	//var init = new Switchery(font_single_multi_select);
	
	$.fn.powerTip.smartPlacementLists.n = ['ne', 'e', 'se'];
	$('.tooltips').powerTip({
		mouseOnToPopup: true,
		smartPlacement: true
	});

	$(".rocket-font-select").select2({
		placeholder:"사용할 폰트를 선택해 주세요",
		width:"300px"
	}).on("change",function(){
		$("div.rocket-font-wrap .rocket-font, .rocket-font-select .select2-chosen").css({"font-family":$(this).val()});
		$("div.rocket-font-family .font-text").text($(this).val());
		$(".korean-font-text").text($(this).val());
	}).select2("container").find("ul.select2-choices").sortable({
		containment: 'parent',
		start: function() { $("#e15").select2("onSortStart"); },
		update: function() { $("#e15").select2("onSortEnd"); }
	});
	
	/* 2015-10-23 추가 : 클래스 입력  */
	$('textarea[name=rocketfont_class_id_list]').tagEditor({
		placeholder: '폰트를 적용할 클래스명을 입력해 주세요.'
	});
	
	var selected_font_family = $(".rocket-font-select option:selected").val();
	$("div.rocket-font-wrap .rocket-font, .rocket-font-select .select2-chosen").css({"font-family":selected_font_family});
	
	$(".rocket-font-fontfamily").select2({
		placeholder:"사용할 폰트를 선택해 주세요",
		width:"400px"
	}).on("change",function(){
		var target_font_family = $(".rocket-font-fontfamily option:selected").text();
		var font_family_arr = target_font_family.split(",");
		
		var last_index = font_family_arr.length;
		var backup_font_family = [];
		$.each(font_family_arr, function(index,value){
			if(last_index==(index+1)){
				$(".generic-font-text").text(value);
			}else{
				backup_font_family.push(value);
			}
		});
		$(".en-font-text").text(backup_font_family.join(', '));
		$("div.rocket-font-fontfamily .select2-chosen").css({"font-family":target_font_family});
	});
	
	var selected_backup_font_family = $(".rocket-font-fontfamily option:selected").text();
	$(".rocket-font-fontfamily .select2-chosen").css({"font-family":selected_backup_font_family});
	
	$(".rocket-font-style").select2({
		placeholder:"사용할 스타일을 선택해 주세요",
		width:"500px"
	}).on("change",function(){
		var selected_font_style = $(".rocket-font-style option:selected").val();
		$("div.rocketfont-plugin-setting .rocket-font").css({"font-weight":selected_font_style});
	})
	
	var arr = ["body","h1","h2","h3","h4","h5","h6","p"];
	$.each(arr, function(index,target_tag){
		
		var current_tag_font_color = ($("input[name=font_color_"+target_tag+"]").val().length > 0) ? $("input[name=font_color_"+target_tag+"]").val() : rgb2hex($(target_tag).css("color"));
		$("input[name=font_color_"+target_tag+"]").minicolors({
			defaultValue: current_tag_font_color,
			change:function(){
				$("div.rocketfont-plugin-setting .rocket-font-"+target_tag).css({"color":$(this).val()});
				$(".rocket-font-size-slider-"+target_tag+" ,.rocket-font-lineheight-slider-"+target_tag).css({"background-color":$(this).val()});
			}
		});
		$("div.rocketfont-plugin-setting .rocket-font-"+target_tag).css({"color":current_tag_font_color});
		$("tr.item-"+target_tag+" .rocket-font-size-slider-"+target_tag+" ,.rocket-font-lineheight-slider-"+target_tag).css({"background-color":current_tag_font_color});
		
		var elem = document.querySelector('input[name=font_use_'+target_tag+']');
		var init = new Switchery(elem,{color:current_tag_font_color});
		
		if(!$("div.rocketfont-plugin-setting .rocket-font-"+target_tag).parent().children('.input-group').children().children('.js-switch').is(':checked')){
			checkbox_status_text(false, target_tag);
			checkbox_check(false, target_tag);
		}
		
		$("div.rocketfont-plugin-setting .rocket-font-"+target_tag).parent().children('.input-group').children().children('.js-switch').on('change',function(){
			checkbox_status_text($(this).is(':checked'), target_tag);
			checkbox_check($(this).is(':checked'), target_tag);
		});
		
		var current_tag_font_weight = ($(":hidden[name=font_weight_"+target_tag+"]").val().length > 0) ? $(":hidden[name=font_weight_"+target_tag+"]").val() : $(target_tag).css("font-size").replace(/px/,'');
		$(".rocket-font-size-slider-"+target_tag).noUiSlider({
			start:current_tag_font_weight,
			connect: "lower",
			range:{
				'min':1,
				'max':40
			},
			format: wNumb({
				decimals: 0
			})
		}).on({change:function(){
			$("div.rocketfont-plugin-setting .rocket-font-"+target_tag).css({"font-size":$(this).val() + "px"});
			$(":hidden[name=font_weight_"+target_tag+"]").val($(this).val());
		}});
		$("div.rocketfont-plugin-setting .rocket-font-"+target_tag).css({"font-size":current_tag_font_weight + "px"});
		$(".rocket-font-size-slider-"+target_tag).Link('lower').to($(".rocket-font-size-"+target_tag+"-label"));
		
		var current_tag_font_lineheight = ($(":hidden[name=font_lineheight_"+target_tag+"]").val().length > 0) ? $(":hidden[name=font_lineheight_"+target_tag+"]").val() : $(target_tag).css("line-height").replace(/px/,'');
		$(".rocket-font-lineheight-slider-"+target_tag).noUiSlider({
			start:current_tag_font_lineheight,
			connect: "lower",
			range:{
				'min':1,
				'max':60
			},
			format: wNumb({
				decimals: 1
			})
		}).on({change:function(){
			$("div.rocketfont-plugin-setting .rocket-font-"+target_tag).css({"line-height":$(this).val() + "px"});
			$(":hidden[name=font_lineheight_"+target_tag+"]").val($(this).val());
		}});
		$("div.rocketfont-plugin-setting .rocket-font-"+target_tag).css({"line-height":current_tag_font_lineheight + "px"});
		$(".rocket-font-lineheight-slider-"+target_tag).Link('lower').to($(".rocket-font-lineheight-"+target_tag+"-label"));
	});
	
	$("#e15").select2("container").find("ul.select2-choices").sortable({
		containment: 'parent',
		start: function() { $("#e15").select2("onSortStart"); },
		update: function() { $("#e15").select2("onSortEnd"); }
	});

	$('.rocketfont-plugin-setting').pwstabs({
		containerWidth: '100%',
		defaultTab: 1,
		effect: 'none',
		responsive: true,
		theme:'pws_theme_grey'
	});
	
	$("div.rocket-font-item-container tr.item:odd").css('background-color','#F1F1F1');
	
	$("form#rocketfont_form").submit(function(){
		var selected_font_slug = $(".rocket-font-select option:selected").attr("class");
		$(":hidden[name=selected_font_slug]").val(selected_font_slug);
	});
});

function checkbox_check(status, target_tag){
	if(status){
		jQuery("tr.item-"+target_tag+" th, div.rocketfont-plugin-setting .rocket-font-"+target_tag).css({'text-decoration':''});
	}else{
		jQuery("tr.item-"+target_tag+" th, div.rocketfont-plugin-setting .rocket-font-"+target_tag).css({'text-decoration':'line-through'});
	}
}

function checkbox_status_text(status, target_tag){
	if(status){
		jQuery("div.rocketfont-plugin-setting .rocket-font-"+target_tag).parent().children('.input-group').children().children('.check-change').text("사용");
	}else{
		jQuery("div.rocketfont-plugin-setting .rocket-font-"+target_tag).parent().children('.input-group').children().children('.check-change').text("사용하지 않음");
	}
}

function rgb2hex(rgb) {
    rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}