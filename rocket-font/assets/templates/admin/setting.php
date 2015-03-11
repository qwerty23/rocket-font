<style>
	div.rocket-font-body{padding-right:20px;padding:10px;}
	
	ul.pws_tabs_controll li{margin-bottom: 0px;}
	.minicolors-input{height: 27px !important;}
	.item{border-bottom: 1px dotted #D3D3D3; margin-bottom:20px;padding:10px;}
	.item th{text-align: center;}
	div.pws_tab_single{width:98%;}
	.noUi-handle{text-align: center;}
	.rocket-font-wrap{margin:10px 20px 0px 2px;}
	.input-group span.label{display:inline-block;width:200px;}
	.rocket-font-family{font-size: 40px;display: inline-block;}
	<?php foreach($font_list as $css_slug => $font_family): ?>
		#select2-results-2 li.<?php echo $css_slug;?>{font-family:<?php echo $font_family['font_name'];?>;}
	<?php endforeach; ?>
	
	<?php foreach($font_family_list as $family_css_slug => $backup_font_family): ?>
		#select2-results-4 li.<?php echo $family_css_slug;?>{font-family:<?php echo $backup_font_family['font_name']?>, <?php echo $backup_font_family['generic_font_family']?>;}
	<?php endforeach; ?>
</style>
<?php
// var_export($options);
?>
	
	<p></p>
	<h1><i class="fa fa-rocket fa-2x"></i>Rocket Font</h1>
	<p></p>
	<?php
	if($_POST['action']=="update"):
	?>
	<div class="updated" style="margin:5px 20px 2px 0px;">
		<p><strong class="rocket-font">저장 완료!</strong></p>
		<p class="rocket-font">설정이 변경되었습니다.</p>
	</div>
	<?php
	endif;
	?>
	
	<div class="rocket-font-wrap">
		<div class="rocket-font-family notice" style="margin-left:0px;margin-bottom:10px;border-left:4px solid #1E8CBE;">
			<p>현재 설정된 폰트</p>
			<p>font-family:<span class="en-font-text"><?php echo $selected_backup_font_info['font_name'];?></span>,<span class="korean-font-text"><?php echo $options['selected_font'];?></span>,<span class="generic-font-text"><?php echo $selected_backup_font_info['generic_font_family'];?></span></p>
		</div>
		
		<form action="#" method="post" id="rocketfont_form" role="form">
			<button type="submit" class="button-primary dnp-plugin-submit">저장</button>
			<p></p>
			<div class="rocketfont-plugin-setting">
				
				<div data-pws-tab="start" data-pws-tab-name="<i class='fa fa-font'></i> 폰트 설정">
					
					<div class="">
						<h2><i class='fa fa-font'></i> 기본 폰트 설정</h2>
						<label>기본 폰트 선택 : </label>
						<select class="rocket-font-select" name="selected_font">
							<option value="">선택하지 않음</option>
							<?php
							foreach($font_list as $css_slug => $font_family):
							?>
								<option value="<?php echo $font_family['font_name']?>" class="<?php echo $css_slug?>" <?php selected($font_family['font_name'], $options['selected_font'])?> style="font-family:'<?php echo $font_family['font_name'];?>'"><?php echo $font_family['font_text_title']?></option>
							<?php
							endforeach;
							?>
						</select>
						<p></p>
						<code>한글 폰트를 설정해 주세요.</code>
						<input type="hidden" name="selected_font_slug" value=""/>
						
						<hr>
						
						<h2><i class='fa fa-font'></i> 영문 폰트</h2>
						<label>영문 폰트 선택 : </label>
						<select class="rocket-font-fontfamily" name="selected_font_family">
							<option value="">선택하지 않음</option>
							<?php
							foreach($font_family_list as $family_css_slug => $backup_font_family):
							?>
							<option value="<?php echo $family_css_slug?>" class="<?php echo $family_css_slug?>" style="font-family:<?php echo $backup_font_family['font_name'];?>" <?php selected($family_css_slug, $options['selected_font_family'])?>><?php echo $backup_font_family['font_name']?>, <?php echo $backup_font_family['generic_font_family']?></option>
							<?php
							endforeach;
							?>
						</select>
						<p></p>
						<code>영문만 다른 폰트를 사용하기 위한 설정입니다. 아무것도 선택하지 않았을 경우 영문에도 위의 한글 폰트가 사용될 것입니다.</code>
					</div>
				</div>
				
				<div data-pws-tab="font-weight-setting" data-pws-tab-name="<i class='fa fa-text-height'></i> 각 태그별 크기 및 글 간격 설정">
					<h2><i class='fa fa-text-height'></i> 각 태그별 크기 및 글 간격 설정</h2>
					<div class="rocket-font-item-container">
						<table class="form-table">
							<tbody>
							
						<?php
						foreach($target_tag as $tag):
						?>
							<tr class="item item-<?php echo $tag?>">
								<th>
									<h3><?php echo $tag?>태그 폰트 설정</h3>
									<button type="submit" class="button-primary dnp-plugin-submit">저장</button>
								</th>
								<td>
									<?php 
									if($tag=="body"):
									?>
										<div class="rocket-font-<?php echo $tag?> rocket-font rocket-font-review"><?php echo $tag;?> 태그의 폰트 크기를 지정해 주세요.<br>사이트 전체의 폰트 사이즈이며 별도로 사이즈가 지정되지 않은 태그일경우 이 사이즈가 적용될 것입니다.<br>초기값은 이 사이트에 설정되어 있던 값입니다.</div>
									<?php
									else:
									?>
										<<?php echo $tag?> class="rocket-font-<?php echo $tag?> rocket-font rocket-font-review"><?php echo $tag;?> 태그의 폰트 크기를 지정해 주세요.<br>별도로 사이즈가 지정되지 않은 태그일경우 이 사이즈가 적용될 것입니다.<br>초기값은 이 사이트에 설정되어 있던 값입니다.</<?php echo $tag?>>
									<?php 
									endif; 
									?>
									<div class="input-group">
										<div><span class="label">1. 사용</span> <input type="checkbox" name="font_use_<?php echo $tag?>" value="yes" <?php checked( $options['font_use_'.$tag], "yes" ); ?>></div>
										<div><span class="label">2. <?php echo $tag?>태그 폰트 색상</span> <input type="text" name="font_color_<?php echo $tag?>" value="<?php echo $options['font_color_'.$tag]?>" class="font-color"></div>
										3. <?php echo $tag?>태그 폰트 크기:<label class="rocket-font-size-<?php echo $tag?>-label"></label>px <div class="rocket-font-size-slider-<?php echo $tag?>"></div>
										<input type="hidden" name="font_weight_<?php echo $tag?>" value="<?php echo $options['font_weight_'.$tag]?>">
										
										4. <?php echo $tag?>태그 폰트 글 간격:<label class="rocket-font-lineheight-<?php echo $tag?>-label"></label>px
										<div class="rocket-font-lineheight-slider-<?php echo $tag?>"></div>
										<input type="hidden" name="font_lineheight_<?php echo $tag?>" value="<?php echo $options['font_lineheight_'.$tag]?>">
									</div>
								</td>
								
								
							</tr>
						<?php
						endforeach;
						?>
							</tbody>
						</table>
					</div>
				</div>
				
				<div data-pws-tab="font-cdn" data-pws-tab-name="<i class='fa fa-cog'></i> 옵션 (폰트가 설정대로 표시되지 않을 경우)">
					<div>
						<h2><i class='fa fa-cog'></i> 옵션 : 폰트 표시 방식</h2>
						<label>CSS 방식<input type="radio" name="use_jquery" value="no" <?php checked( $options['use_jquery'], "no" ); ?>/></label>
						<label>jQuery 방식<input type="radio" name="use_jquery" value="yes" <?php checked( $options['use_jquery'], "yes" ); ?>/></label>
						<p class="rocket-font">일반적으로 CSS로 사용하며 기본값입니다. 설정한대로 표시될 경우 변경할 필요가 없습니다.</p>
						<p class="rocket-font">그러나 설정한대로 표시되지 않을경우 (예를들어 CSS 에 important 로 폰트를 강제 지정한 경우) CSS 방식은 통하지 않으며 jQuery 방식을 선택하십시요.</p>
					</div>
					<div>
						<h2><i class='fa fa-cog'></i> 옵션 : 워드프레스 기본 에디터에서 폰트 사용</h2>
						<label>사용하지 않음<input type="radio" name="use_tinymce_editor" value="no" <?php checked( $options['use_tinymce_editor'], "no" ); ?>/></label>
						<label>사용<input type="radio" name="use_tinymce_editor" value="yes" <?php checked( $options['use_tinymce_editor'], "yes" ); ?>/></label>
					</div>
				</div>
				
			</div>
			<input type="hidden" name="action" value="update">
			<p></p>
			<button type="submit" class="button-primary dnp-plugin-submit">저장</button>
			
		</form>
		
		<div class="plugin-info">
			<h2><i class="fa fa-rocket"></i> Rocket Font - 플러그인 정보</h2>
			<h3>플러그인 버전: <?php echo $version;?></h3>
			<h4><i class="fa fa-medkit"></i> 개발자: Qwerty</h4>
			<h4><i class="fa fa-paint-brush"></i> 디자인: <i class="fa fa-rocket"></i>Rocketpress</h4>
			<ol>
				<li>현재 총 <?php echo sizeof($font_list) ?>개의 폰트가 준비되어 있습니다.</li>
				<li>이 외에도 한글 폰트의 종류는 더 있지만 유료이고, 사용에 아무런 문제가 없는 오픈소스 폰트만 등록해 뒀습니다.</li>
				<li>추후 새로운 폰트가 추가될경우 이 플러그인도 업데이트 해서 반영할 예정입니다.</li>
			</ol>
			기타 플러그인에 관한 문의 및 건의사항은 <a href="http://rocketpress.kr/forums/forum/%EB%A1%9C%EC%BC%93%ED%8F%B0%ED%8A%B8-%ED%94%8C%EB%9F%AC%EA%B7%B8%EC%9D%B8-rocket-font-plugin/"><i class="fa fa-rocket"></i>Rocketpress 문의 게시판</a> 을 확인해 주세요.
		</div>
	</div>
