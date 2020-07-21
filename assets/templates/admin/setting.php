<style>
	div.rocket-font-body{padding-right:20px;padding:10px;}
	code{line-height:2em;}
	.fa-question-circle{color:#1E8CBE;}
	.fa-c2x{font-size:1.4em;}
	ul.pws_tabs_controll li{margin-bottom: 0px;}
	.minicolors-input{height: 27px !important;}
	.item{border-bottom: 1px dotted #D3D3D3; margin-bottom:20px;padding:10px;}
	.item th{text-align: center;}
	div.pws_tab_single{width:98%;}
	.noUi-handle{text-align: center;}
	.rocket-font-wrap{margin:10px 20px 0px 2px;}
	.input-group span.label{display:inline-block;width:200px;}
	.rocket-font-family{font-size: 40px;display: inline-block;}
	.rocket-font-review{height:180px;margin:auto;display:block;}
	.check-change {
		background-color: #fdf6e3;
		box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
		color: #635eb0;
		display: inline-block;
		height: 22px;
		padding-top: 4px;
		text-align: center;
		vertical-align: middle;
		width: 100px;
	}
	<?php foreach($font_list as $css_slug => $font_family): ?>
		#select2-results-2 li.<?php echo $css_slug;?>{font-family:<?php echo $font_family['font_name'];?>;}
	<?php endforeach; ?>
	
	<?php foreach($font_family_list as $family_css_slug => $backup_font_family): ?>
		#select2-results-6 li.<?php echo $family_css_slug;?>{font-family:<?php echo $backup_font_family['font_name']?>, <?php echo $backup_font_family['generic_font_family']?>;}
	<?php endforeach; ?>
</style>
	
	<p></p>
	<h1><i class="fa fa-rocket fa-2x"></i>Rocket Font</h1>
	<p></p>
	<?php
	if(!empty($_POST['action']) && $_POST['action']=="update"):
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
			<p>현재 폰트 설정</p>
			<p>font-family:<span class="en-font-text"><?php echo $selected_backup_font_info['font_name'];?></span>,<span class="korean-font-text"><?php echo $options['selected_font'];?></span>,<span class="generic-font-text"><?php echo $selected_backup_font_info['generic_font_family'];?></span></p>
		</div>
		
		<form action="#" method="post" id="rocketfont_form" role="form">
			<button type="submit" class="button-primary dnp-plugin-submit">저장</button>
			<p></p>
			<div class="rocketfont-plugin-setting">
				
				<div data-pws-tab="start" data-pws-tab-name="<i class='fa fa-font'></i> 폰트 설정">
					<!-- <div style="display:none;">하나의 폰트만 사용<input type="checkbox" name="font_single_multi_select" value="single" class="js-switch" <?php //checked( $options['font_single_multi_select'], "single" ); ?>>선택한 복수의 폰트 사용</div> -->
					<div class="">
						<div class="single-font">
							<h2><i class='fa fa-font'></i> 기본 폰트 설정</h2>
							<label>기본 폰트 선택 : </label>
							<select class="rocket-font-select " name="selected_font">
								<option value="">선택하지 않음</option>
								<?php
								foreach($font_list as $css_slug => $font_family):
								?>
									<option value="<?php echo $font_family['font_name']?>" class="<?php echo $css_slug?>" <?php selected($font_family['font_name'], $options['selected_font'])?> style="font-family:'<?php echo $font_family['font_name'];?>'"><?php echo $font_family['font_text_title']?></option>
								<?php
								endforeach;
								?>
							</select>
							<i class="fa fa-question-circle fa-c2x tooltips" title="<b>기본 폰트란?</b><p>사이트에 사용할 기본 폰트입니다.<br>보통 다운받아서 사용중인 테마의 경우 영문 폰트를 사용하는데 이 폰트를 한글로 변경합니다.</p>"></i>
							<p></p>
							<code>한글 폰트를 설정해 주세요.</code>
							<input type="hidden" name="selected_font_slug" value=""/>
						</div>
						<hr>
						<div class="single-font">
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
							<i class="fa fa-question-circle fa-c2x tooltips" title="<b>한글 폰트와 영문 폰트를 따로 지정했을 경우<br>예) 한글: 나눔 붓글씨, 영문 : impact</b><p><img src='<?php echo $plugin_url?>assets/images/Screenshot_159.jpg'></p><b>영문 폰트를 지정하지 않았을 경우(한글 폰트만 지정했을 경우)<br>예) 한글, 영문 : 나눔 붓글씨</b><p><img src='<?php echo $plugin_url?>assets/images/Screenshot_160.jpg'></p>"></i>
							<p></p>
							<code>영문만 다른 폰트를 사용하기 위한 설정입니다. 아무것도 선택하지 않았을 경우 영문에도 위의 한글 폰트가 사용될 것입니다.</code>
						</div>
						
						<div class="multi-font" style="display:none;">
							<h2><i class='fa fa-font'></i> 복수의 폰트를 사용</h2>
							<label>폰트 선택 : </label>
							<select class="rocket-font-select" name="selected_multiple_font" multiple="multiple">
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
						</div>
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
										<div>
											<span class="label">1. 사용 <i class="fa fa-question-circle fa-c2x tooltips" title="<p><?php echo $tag;?>태그에 폰트를 적용할지 말지를 선택합니다.</p>"></i> </span>
											<input type="checkbox" name="font_use_<?php echo $tag?>" value="yes" class="js-switch" <?php checked( $options['font_use_'.$tag], "yes" ); ?>>
											<div class="check-change js-check-change-field">사용</div>
										</div>
										<hr>
										<div>
											<span class="label">2. <?php echo $tag?>태그 폰트 색상 <i class="fa fa-question-circle fa-c2x tooltips" title="<p><?php echo $tag;?>태그에 사용할 폰트의 색상을 지정해 주세요.<br>기본값은 사이트에서 사용하던 값입니다.</p>"></i></span>
											<input type="text" name="font_color_<?php echo $tag?>" value="<?php echo $options['font_color_'.$tag]?>" class="font-color">
										</div>
										<hr>
										3. <?php echo $tag?>태그 폰트 크기:<label class="rocket-font-size-<?php echo $tag?>-label"></label>px <i class="fa fa-question-circle fa-c2x tooltips" title="<p><?php echo $tag;?>태그에 사용할 폰트의 크기를 지정해 주세요.<br>기본값은 사이트에서 사용하던 값입니다.</p>"></i>
										<div class="rocket-font-size-slider-<?php echo $tag?>"></div>
										<input type="hidden" name="font_weight_<?php echo $tag?>" value="<?php echo $options['font_weight_'.$tag]?>">
										<hr>
										4. <?php echo $tag?>태그 폰트 글 간격:<label class="rocket-font-lineheight-<?php echo $tag?>-label"></label>px <i class="fa fa-question-circle fa-c2x tooltips" title="<p><?php echo $tag;?>태그에 사용할 폰트의 글 간격을 지정해 주세요.<br>기본값은 사이트에서 사용하던 값입니다.</p>"></i>
										<div class="rocket-font-lineheight-slider-<?php echo $tag?>"></div>
										<input type="hidden" name="font_lineheight_<?php echo $tag?>" value="<?php echo $options['font_lineheight_'.$tag]?>">
										<!--
										<hr>
										<label>5. <?php echo $tag?>태그에 한해서 테마 style.css의 설정값보다 이 설정값을 우선시 함<i class="fa fa-question-circle fa-c2x tooltips" title="<p>이 로켓폰트에서 설정한 <?php echo $tag;?>태그의 설정값들이 적용되지 않는다면 체크하시면 되며<br>적용이 잘 되고있는 상태라면 체크할 필요는 없습니다.<br>로켓폰트의 css 에 위의 설정값들에 !important 를 적용하는 옵션입니다.</p><p><img src='<?php echo $plugin_url?>assets/images/Screenshot_141.jpg'></p>예를들어 위의 경우 로켓폰트에서 설정한 p 태그의 font-size 가 무시된 경우입니다.<br>테마의 style.css 에 조금 더 구체적으로 지정되어있기 때문에<br>로켓폰트 css 의 p태그에 지정된 폰트 사이즈는 무시되며 테마의 style.css 에 적용된 값이 우선시 됩니다."></i>
											<input type="checkbox" name="font_use_css_important_<?php //echo $tag?>" value="yes" <?php //checked( $options['font_use_css_important_'.$tag], "yes" ); ?>>
										</label>
										-->
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
				<div data-pws-tab="font-class-id" data-pws-tab-name="<i class='fa fa-tags'></i> (고급) class 및 id로 설정">
					<h2><i class='fa fa-font'></i> 폰트를 적용할 클래스 및 ID 설정 <i class="fa fa-question-circle fa-c2x tooltips" title="<b>이 옵션은<br>1. 특정 영역에서만 폰트를 사용하고 싶거나<br>2. 태그별 폰트를 설정했는데도 적용이 안되는 영역이 있을때 사용하시기 바랍니다.</b><br><br><p>class 와 id 에 대한 설명, 조금 더 자세한 사용법은 <a href='http://rocketpress.kr/forums/topic/rocketfont-120-changelog/#use' target='_blank'>포럼</a>을 참고해 주세요.</p>"></i></h2>
					<div>
						<textarea name="rocketfont_class_id_list"><?php echo $options['rocketfont_class_id_list']?></textarea>
						
						<h3><i class="fa fa-exclamation-triangle"></i> 클래스라면 앞에 점(.)을, ID 라면 앞에 샾(#)을 반드시 붙여서 입력해 주세요.</h3>
						<ol>
							<li>클래스 입력 예) <code>.example-class(엔터)</code></li>
							<li>ID 입력 예) <code>#example-id(엔터)</code></li>
							<li>연결해서 입력하는것도 가능합니다. content 클래스 안의 p 태그에 적용 입력 예) <code>.content>p(엔터)</code></li>
						</ol>
						<h3><i class="fa fa-lightbulb-o"></i> 아래와 같은 형태로 사용할 수도 있습니다.</h3>
						<ol>
							<li>태그를 전혀 설정하지 않고 클래스와 ID 로만으로 사용할 수도 있습니다.</li>
							<li>예를들어 <code>글 전체가 아닌 글 제목만, 혹은 글 본문의 특정 영역만 폰트를 적용</code>할 수 있습니다.</li>
							<li>위에서 입력한 클래스 혹은 ID의 태그로 감싸면 그 부분만 적용되며 가급적 ID 보다는 클래스를 사용하시기 바랍니다.</li>
							<li>조금 더 자세한 사용법은 <a href='http://rocketpress.kr/forums/topic/rocketfont-120-changelog/#custom-use' target='_blank'>포럼</a>을 참고해 주세요.</li>
						</ol>
					</div>
				</div>
				<div data-pws-tab="font-cdn" data-pws-tab-name="<i class='fa fa-cog'></i> 옵션 (폰트가 설정대로 표시되지 않을 경우)">
					<div>
						<h2><i class='fa fa-cog'></i> 옵션 : 폰트 표시 방식</h2>
						<label>CSS 방식
							<i class="fa fa-question-circle fa-c2x tooltips" title="<p>이 플러그인의 설정값을 바탕으로 CSS 파일을 만들고 사용자 화면에서 폰트를 표시할 때 이 css 파일을 사용합니다.<br>이 css 파일은 관리자 화면에서 저장 버튼을 누를때마다 갱신 & 생성되며, 사용자 화면에서는 생성이 끝난 이 CSS 파일을 사용합니다. 속도를 위해 그 외의 별다른 처리는 없습니다.</p><p>설정한 폰트가 사용자 화면에서 이상없이 표시된다면 특별히 건드릴 필요가 없습니다.</p>"></i>
							<input type="radio" name="use_jquery" value="no" <?php checked( $options['use_jquery'], "no" ); ?>/>
						</label>
						<label>jQuery 방식
							<i class="fa fa-question-circle fa-c2x tooltips" title="<p>간혹 사용하는 테마에 따라 CSS 방식으로는 설정한 폰트가 제대로 표시되지 않을 경우가 있는데,<br>이 경우 선택해 주세요.</p>"></i>
							<input type="radio" name="use_jquery" value="yes" <?php checked( $options['use_jquery'], "yes" ); ?>/>
						</label>
						<br>
						<code>일반적으로 CSS로 사용하며 기본값입니다. 설정한대로 표시될 경우 변경할 필요가 없습니다.<br>그러나 설정한대로 표시되지 않을경우 (예를들어 사용중인 테마의 폰트 설정 CSS 에 !important 로 폰트를 강제 지정한 경우, 일반적으로 테마를 커스텀한게 아니라면 !important는 거의 걸려있지 않습니다.) CSS 방식은 통하지 않으며 jQuery 방식을 선택하십시요.</code>
					</div>
					<hr>
					<div>
						<h2><i class='fa fa-cog'></i> 옵션 : 워드프레스 기본 에디터에서 폰트 사용</h2>
						<label>사용하지 않음<input type="radio" name="use_tinymce_editor" value="no" <?php checked( $options['use_tinymce_editor'], "no" ); ?>/></label>
						<label>사용<input type="radio" name="use_tinymce_editor" value="yes" <?php checked( $options['use_tinymce_editor'], "yes" ); ?>/></label>
						<i class="fa fa-question-circle fa-c2x tooltips" title="<p>사용자 화면뿐만이 아닌 워드프레스 기본 에디터(Tinymce) 에서도 설정한 폰트를 사용할 수 있습니다. 기본값은 [사용하지 않음] 입니다.<br>예) 한글: 제주 한라산, 영문 : Times New Roman</p><p><img src='<?php echo $plugin_url?>assets/images/Screenshot_136.jpg'></p>"></i>
					</div>
				</div>
				
				<div data-pws-tab="font-speed" data-pws-tab-name="<i class='fa fa-cog'></i> 속도 (Beta)">
					<div>
						<h2><i class='fa fa-cog'></i> 옵션 : 폰트 비동기 호출</h2>
						<label>사용하지 않음
							<i class="fa fa-question-circle fa-c2x tooltips" title="<p>이 플러그인의 설정값을 바탕으로 CSS 파일을 만들고 사용자 화면에서 폰트를 표시할 때 이 css 파일을 사용합니다.<br>이 css 파일은 관리자 화면에서 저장 버튼을 누를때마다 갱신 & 생성되며, 사용자 화면에서는 생성이 끝난 이 CSS 파일을 사용합니다. 속도를 위해 그 외의 별다른 처리는 없습니다.</p><p>설정한 폰트가 사용자 화면에서 이상없이 표시된다면 특별히 건드릴 필요가 없습니다.</p>"></i>
							<input type="radio" name="use_async" value="no" <?php checked( $options['use_async'], "no" ); ?>/>
						</label>
						<label>사용
							<i class="fa fa-question-circle fa-c2x tooltips" title="<p>간혹 사용하는 테마에 따라 CSS 방식으로는 설정한 폰트가 제대로 표시되지 않을 경우가 있는데,<br>이 경우 선택해 주세요.</p>"></i>
							<input type="radio" name="use_async" value="yes" <?php checked( $options['use_async'], "yes" ); ?>/>
						</label>
						<br>
						<code>비동기 방식 호출이란 간단하게 설명드리면 홈페이지 로딩에 포함하지 않는 방식입니다. 이를 사용하면 홈페이지 속도가 빨라질 수 있으나 적용한 웹 폰트가 홈페이지 로딩이 끝난 후 적용됩니다. 시용시와 비사용시의 차이점을 직접 확인해보시고 원하는쪽을 선택하세요.</code>
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
			<ol>
				<li>현재 총 <?php echo sizeof($font_list) ?>개의 폰트가 준비되어 있습니다.</li>
				<li>이 외에도 한글 폰트의 종류는 더 있지만 유료이고, 사용에 아무런 문제가 없는 오픈소스 폰트만 등록해 뒀습니다.</li>
				<li>추후 새로운 폰트가 추가될경우 이 플러그인도 업데이트 해서 반영할 예정입니다.</li>
			</ol>
			기타 플러그인에 관한 문의 및 건의사항은 <a href="http://in-web.co.kr/wordpress/plug-in/wordpress-%EC%97%90%EC%84%9C-%ED%95%9C%EA%B8%80-%ED%8F%B0%ED%8A%B8%EB%A5%BC-rocket-font/" target="_blank"><i class="fa fa-rocket"></i>플러그인 소개 글</a> 에 댓글 혹은 문의 메일을 보내 주세요.
		</div>
	</div>
