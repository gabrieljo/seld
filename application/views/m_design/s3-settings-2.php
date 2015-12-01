<div class="container-fluid inner-content">

	<div class="row">
		<div class="col-sm-3 col-sm-offset-1">
			<!-- <div class="form-group form-group-sm col-sm-10">
				<label for="exampleInputEmail1">1. Select Paper</label>
				<?php
				/*$options = array(
								'eco-friendly' 	=> 'Eco-Friendly',
								'epoxy-resin'	=> 'Epoxy Resin',
								'laster'		=> 'Laster',
								'pet'			=> 'PET'
							);
				echo form_dropdown('set-paper', $options, '', 'class="form-control input-xs" id="set-paper"');*/
				?>
			</div> -->
			<h4>1. Select Paper (용지선택)</h4>
			<div class="form-group form-group-sm col-sm-10">
				<label for="set-size">1. Size</label>
				<?php
				$options = array(
								'A1'=>'A1', 'A2'=>'A2', 'A3'=>'A3', 
								'A4'=>'A4', 'A5'=>'A5', 'A6'=>'A6', 
								'B2'=>'B2', 'B3'=>'B3', 'B4'=>'B4', 
								'B5'=>'B5', 'B6'=>'B6', 'B7'=>'B7'
							);
				echo form_dropdown('set-size', $options, 'A1', 'class="form-control input-xs" id="set-size"');
				?>
			</div>
			<div class="form-group form-group-sm col-sm-10">
				<label for="set-cutting">2. Cutting</label>
				<?php
				$options = array(
								'1'=>'1', '2'=>'2', '3'=>'3', 
								'4'=>'4', '5'=>'5', '6'=>'6', 
								'7'=>'7', '8'=>'8', '9'=>'9' 
							);
				echo form_dropdown('set-cutting', $options, '', 'class="form-control input-xs" id="set-cutting"');
				?>
			</div>
			<div class="form-group form-group-sm col-sm-10">
				<label for="set-quantity">3. Quantity</label>
				<?php
				$options = array(
								'spin-box' 	=> 'Spin Box'
							);
				echo form_dropdown('set-quantity', $options, '', 'class="form-control input-xs" id="set-quantity"');
				?>
			</div>
		</div>
		<div class="col-sm-3 nopadding">
			<h4>2. Print Option</h4>
			<div class="form-group form-group-sm col-sm-10">
				<label for="set-printing">1. Printing method: general</label>
				<?php
				$options = array(
								'default' 	=> 'Default'
							);
				echo form_dropdown('set-printing', $options, '', 'class="form-control input-xs" id="set-printing"');
				?>
			</div>
			<div class="form-group form-group-sm col-sm-10">
				<label for="set-frequency">2. Frequency</label>
				<?php
				$options = array(
								'both sides to 8 degrees' 	=> 'Both Sides to 8 degrees Celsius',
								'one side to 8 degrees'	=> 'One Side to 4 degrees Celsius'
							);
				echo form_dropdown('set-frequency', $options, '', 'class="form-control input-xs" id="set-frequency"');
				?>
			</div>
			<div class="form-group form-group-sm col-sm-10">
				<label for="set-quality">3. Quality of paper</label>
				<?php
				$options = array(
								'Art Paper'		=>'Art Paper', 
								'Snow White'	=>'Snow White',
								'백색모조'			=> '백색모조',
								'미색모조'			=> '미색모조',
								'반누보'			=> '반누보',
								'스코트랜드'		=> '스코트랜드',
								'앙상블'			=> '앙상블',
								'랑데뷰 네츄럴'		=> '랑데뷰 네츄럴',
								'랑데뷰'			=> '랑데뷰',
								'울트라화이트'		=> '울트라화이트',
								'뉴크라프트보드'	=> '뉴크라프트보드',
								'몽블랑 백색'		=> '몽블랑 백색',
								'이매진 백색'		=> '이매진 백색',
								'르느와르 백색'		=> '르느와르 백색',
								'에코하임 백색'		=> '에코하임 백색',
								'에코하임 아이보리'	=> '에코하임 아이보리'
							);
				echo form_dropdown('set-quality', $options, '', 'class="form-control input-xs" id="set-quality"');
				?>
			</div>
		</div>
		<div class="col-sm-4 col-sm-offset-0">
			<h4>3. Processing method after</h4>
			<div class="form-group form-group-sm">
				<label for="set-coating" class="col-sm-6 control-label">Coating(코팅)</label>
				<div class="col-sm-6">
					<?php
					$options = array(
									'None' 				=> 'None',
									'One side Matt'		=> 'One side Matt',
									'Onse side glossy'	=> 'One side glossy',
									'One side UV'		=> 'One side UV',
									'Both side matt'	=> 'Both side matt',
									'Both side glossy'	=> 'Both side glossy',
									'Both side UV'		=> 'Both side UV'
								);
					echo form_dropdown('set-coating', $options, '', 'class="form-control input-xs" id="set-coating"');
					?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="set-folding" class="col-sm-6 control-label">Folding line 1(접지)</label>
				<div class="col-sm-6">
					<?php
					$options = array(
									'접지없음' 			=> '접지없음',
									'2단접지(4p)'			=> '2단접지(4p)',
									'3단접지(6p)'			=> '3단접지(6p)',
									'N자접지(6p)'			=> 'N자접지(6p)',
									'양끝접지(6p)'			=> '양끝접지(6p)',
									'대문접지(8p)'			=> '대문접지(8p)',
									'반접고반접지(8p)'		=> '반접고반접지(8p)',
									'십자접지 (8p)'		=> '십자접지 (8p)',
									'6단 DM접지'			=> '6단 DM접지',
									'두루마리 6단(12p)'	=> '두루마리 6단(12p)',
									'두루마리 4단(8p)'		=> '두루마리 4단(8p)',
									'두루마리 5단(10p)'	=> '두루마리 5단(10p)',
									'병풍접지 4단(8p)'		=> '병풍접지 4단(8p)',
									'병풍접지 5단(10p)'	=> '병풍접지 5단(10p)',
									'병풍접지 6단(12p)'	=> '병풍접지 6단(12p)',
									'병풍접지 7단(14p)'	=> '병풍접지 7단(14p)',
									'병풍접지 8단(16p)'	=> '병풍접지 8단(16p)',
									'병풍접지 9단(18p)'	=> '병풍접지 9단(18p)',
									'병풍접지 10단(20p)'	=> '병풍접지 10단(20p)',
									'병풍접지 11단(22p)'	=> '병풍접지 11단(22p)',
									'4단병풍후 2단접지(8p)'	=> '4단병풍후 2단접지(8p)',
									'5단병풍후 2단접지(10p)'=> '5단병풍후 2단접지(10p)',
									'6단병풍후 2단접지(12p)'=> '6단병풍후 2단접지(12p)',
									'4단병풍후 3단접지(16p)'=> '4단병풍후 3단접지(16p)',
									'4단병풍후 3단접지'		=> '4단병풍후 3단접지'
								);
					echo form_dropdown('set-folding', $options, '', 'class="form-control input-xs" id="set-folding"');
					?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="set-folding2" class="col-sm-6 control-label">Folding line 2(누름선)</label>
				<div class="col-sm-6">
					<?php
					$options = array(
									'1 line' 	=> '1 line',
									'2 lines' 	=> '2 lines',
									'3 lines' 	=> '3 lines',
									'4 lines' 	=> '4 lines'
								);
					echo form_dropdown('set-folding2', $options, '', 'class="form-control input-xs" id="set-folding2"');
					?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="set-dotted" class="col-sm-6 control-label">Dotted line (절취선)</label>
				<div class="col-sm-6">
					<?php
					$options = array(
									'1 line' 	=> '1 line',
									'2 lines' 	=> '2 lines',
									'3 lines' 	=> '3 lines',
									'4 lines' 	=> '4 lines'
								);
					echo form_dropdown('set-dotted', $options, '', 'class="form-control input-xs" id="set-dotted"');
					?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="set-holes" class="col-sm-6 control-label">Making hole(구멍내기)</label>
				<div class="col-sm-6">
					<?php
					$options = array(
									'1 hole' 	=> '1 hole',
									'2 holes' 	=> '2 holes',
									'3 holes' 	=> '3 holes',
									'4 holes' 	=> '4 holes'
								);
					echo form_dropdown('set-holes', $options, '', 'class="form-control input-xs" id="set-holes"');
					$options = array(
									'3 mm' 	=> '3 mm',
									'4 mm' 	=> '4 mm',
									'5 mm' 	=> '5 mm',
									'6 mm' 	=> '6 mm',
									'7 mm' 	=> '7 mm',
									'8 mm' 	=> '8 mm'
								);
					echo form_dropdown('set-holes-size', $options, '', 'class="form-control input-xs" id="set-holes-size"');
					?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="set-dotted" class="col-sm-6 control-label">Tolling(형압)</label>
				<div class="col-sm-6">
					<?=form_checkbox('set-tolling', '1', FALSE)?> Yes
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="set-dotted" class="col-sm-6 control-label">Painting(박)</label>
				<div class="col-sm-6">
					<?=form_checkbox('set-tolling', '1', FALSE)?> Yes
				</div>
			</div>
		</div>
	</div>
	<hr>
	<button name="frmsubmit" class="btn btn-primary pull-right">Save and continue <span class="glyphicon glyphicon-chevron-right"></span></button>
</div>
<div class="container-fluid bdt">
	<div class="row">
		<div class="col-xs-9">
			<h5></h5>
		</div><!-- .col-xs-9 -->
		<div class="col-xs-3 bdl">
			<h5>Price Info</h5>
			<p class="text-muted">
				Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. 
			</p>
		</div><!-- .col-xs-3 -->
	</div>
</div>