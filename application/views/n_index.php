<?php include('inc/n_header.php') ?>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="n_slider">
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
						<li data-target="#carousel-example-generic" data-slide-to="1"></li>
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<div class="item active">
							<?=inc('n/s1.jpg')?>
							<div class="carousel-caption">
							...
							</div>
						</div>
						<div class="item">
							<?=inc('n/s1.jpg')?>
							<div class="carousel-caption">
							...
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="n_container">
	<div class="container">
		<div class="col-sm-12">
			<h1>추천 테마</h1>
			<div class="row">
				<ul class="thumb_list">
					<?php
					// thumbs list
					$thumbs = array();
					$thumbs[] = array('1', 'n1.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
					$thumbs[] = array('2', 'n2.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
					$thumbs[] = array('3', 'n3.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
					$thumbs[] = array('4', 'n4.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
					$thumbs[] = array('5', 'n5.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
					$thumbs[] = array('6', 'n6.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
					$thumbs[] = array('7', 'n7.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
					$thumbs[] = array('8', 'n8.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');

					for ($i=0; $i<count($thumbs); $i++){
						echo '<li class="col-sm-3"><div class="thumb_wrapper"><div>' . inc('n/'.$thumbs[$i][1]) . '</div></div><div class="thumb_desc">'. $thumbs[$i][2].'</div><div class="thumb_author">'.$thumbs[$i][3].'</div></li>';
					}
					?>
				</ul>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="n_featured">
						<div class="col-sm-4">
							<?=inc('n/f1.png')?>
							<h2>클릭만으로 나만의 디자인을</h2>
							<div class="desc">디자인의 모든 요소를 내마음대로 <br>직접 디자인 할 수 있습니다.</div>
						</div>
						<div class="col-sm-4">
							<?=inc('n/f2.png')?>
							<h2>수백 가지의 템플릿</h2>
							<div class="desc">마음에 드는 템플릿으로  <br>나만의 스타일을 디자인하세요</div>
						</div>
						<div class="col-sm-4">
							<?=inc('n/f3.png')?>
							<h2>템플릿 마켓</h2>
							<div class="desc">마음에 드는 템플릿으로 <br>나만의 스타일로 디자인하세요</div>
						</div>
						<div class="cf"></div>
					</div>
				</div>
			</div>

			<h1>디자인 마켓</h1>
			<div class="row">
				<div class="col-sm-12">
					<ul class="thumb_list">
						<?php
						$thumbs = array();
						$thumbs[] = array('1', 'n2.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
						$thumbs[] = array('2', 'n2.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
						$thumbs[] = array('3', 'n9.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');
						$thumbs[] = array('4', 'n2.jpg', '[리플렛] 세련된 디자인 1', 'Design By 앙드레김');

						for ($i=0; $i<count($thumbs); $i++){
							echo '<li class="col-sm-3"><div class="thumb_wrapper"><div>' . inc('n/'.$thumbs[$i][1]) . '</div></div><div class="thumb_desc">'. $thumbs[$i][2].'</div><div class="thumb_author">'.$thumbs[$i][3].'</div></li>';
						}
						?>
					</ul>
				</div>
			</div>

			<div class="n_separator"></div>

			<div class="row n_bottom">
				<div class="col-xs-3">
					<h3>고객지원</h3>
					<div class="number">070.4158.2540</div>
					<div class="hours">
						멍일 | 10:00 ~ 18:00 <br>
						멍일 | 10:00 ~ 1:00
					</div>
				</div>
				<div class="col-xs-9">
					<?=inc('logox.png', array('style'=>'height:26px;'))?> this is some description about the menu.
				</div>
			</div>

		</div>
	</div>
</div>

<?php include('inc/n_footer.php') ?>