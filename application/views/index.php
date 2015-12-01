<?php include('inc/header.php') ?>

<div class="article-pages">
	<div class="page active" id="page1">	
		<?=inc('p1-paper.png', array('class'=>'props anim', 'style'=>'left:40%;bottom:-500px;', 'data-istyle'=>'bottom:-600px', 'data-fstyle'=>'bottom:-500px'))?>
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<?=inc('p1-keyboard.png', array('class'=>'props anim', 'style'=>'top:-300px;margin-left:-5%', 'data-istyle'=>'top:-300px', 'data-fstyle'=>'top:-252px', 'data-duration'=>1800))?>
					<?=inc('p1-earphones.png', array('class'=>'props anim', 'style'=>'margin-left:250px', 'data-istyle'=>'top:-150px', 'data-fstyle'=>'top:0'))?>					

					<?=inc('p1-coffee.png', array('class'=>'props', 'style'=>'margin:290px 0 0 50px;'))?>
					<?=inc('p1-leaf.png', array('class'=>'props anim', 'style'=>'margin:250px 0 0 40px', 'data-istyle'=>'margin-left:30px', 'data-fstyle'=>'margin-left:40px'))?>
					<?=inc('p1-logo.png', array('class'=>'props', 'style'=>'margin:350px 0 0 40%'))?>
					<?=inc('p1-clip.png', array('class'=>'props', 'style'=>'margin:30% 0 0 85%'))?>
					<?=inc('p1-pencil.png', 	array('class'=>'props', 'style'=>'margin:40% 0 0 82%'))?>

					<?=inc('p1-post1.png', array('class'=>'props', 'style'=>'margin:50% 0 0 93%'))?>
					<?=inc('p1-post2.png', array('class'=>'props', 'style'=>'margin:53% 0 0 90%'))?>
				</div>
			</div>
		</div>
		<div class="intro">
			<h2>Sub title</h2>
			<h1><strong>Main</strong> Title</h1>
			<p class="text-primary">
				some tiny information about us. this can be anything.
			</p>
		</div>
		<div class="footer">
			<div class="container">
				<div class="row copyright">
					<div class="col-xs-12">
						&copy; <?=date('Y')?> SeLD. All Rights Reserved
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page" id="page2">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 nopadding">
					<video id="myVideo" style="width:100%;height:100%;">
						<source src="<?=base_url()?>files/media/video.mov" type="video/mp4">
						<source src="<?=base_url()?>files/media/video.mov" type="video/ogg">
						Your browser does not support the video tag.
					</video>
				</div>
			</div>
		</div>
	</div>

	<div class="page" id="page3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<h2>세상에서 가장 아름다운 디자인 이야기 “오른손잡이”</h2>
					<p>디자인에 옳고 그름이 없다는 것이 셀디의 생각입니다만, <br>
						고객들의 목적과 생각에 따라 그에 적합한 옳고 그름의 디자인의 기준은 생길 수 있지요. <br>
						그 기준에 적합한 옳은 디자인을 하는 옳은손잡이 셀디.</p>
				</div>
			</div>
		</div>
		<?=inc('p3-ipad.png', array('class'=>'props anim', 'style'=>'left:-50%;margin:2% 0 0 -331px', 'data-istyle'=>'left:-50%', 'data-fstyle'=>'left:50%', 'data-duration'=>2000))?>
	</div>

	<div class="page" id="page4">
		<div class="row1">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 hasbg anim" data-istyle="background-position-y:190%" data-fstyle="background-position-y:90%"></div>
					<div class="col-sm-6 nopadding"><?=inc('p4-text.png', array('class'=>'text', 'style'=>'max-width:330px;height:auto;margin:10%;'))?></div>
				</div>
			</div>
		</div>
		<div class="row2">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 nopadding"><?=inc('p4-text.png', array('class'=>'text', 'style'=>'max-width:330px;height:auto;margin:10%;'))?></div>
					<div class="col-sm-6 hasbg anim" data-istyle="background-position-x:0" data-fstyle="background-position-x:30px"></div>
				</div>
			</div>
		</div>
		<div class="row3">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 hasbg anim" data-istyle="background-position-y:0%" data-fstyle="background-position-y:80%"></div>
					<div class="col-sm-6 nopadding"><?=inc('p4-text.png', array('class'=>'text', 'style'=>'max-width:330px;height:auto;margin:10%;'))?></div>
				</div>
			</div>
		</div>
	</div>

	<ul id="quick_page_links" data-prev-page="0"></ul>
</div>

<script>$(seld.init)</script>
<?php include('inc/footer.php') ?>