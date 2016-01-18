<?php
$title = isset($title) ? $title : SITE_TITLE;

// Nav Links
$navs = array(
			/*base_url() 			=> 'Home',
			'p/about/SelD' 		=> 'About Us',
			'p/contact'			=> 'Contact',*/
			'p/login'			=> 'Login'
		);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<title><?=$title?></title>
	<link rel="icon" type="image/png" href="<?=base_url()?>files/img/favicon.png">
	<?php
	// Load all CSS/JS framework
	$load = array(
				'bootstrap.min.css',
				'n_style.css',
				'jquery.js',
				'bootstrap.min.js',
				'seld.js'
			);
	echo inc($load);
	?>
	<script>
	function base_url(){
		return '<?=base_url()?>';
	}
	</script>
</head>
<body>

<div class="n_header">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?=inc('logox.png')?>

				<ul class="pull-right menu">
					<li><?=anchor('p/login', '디자인하기')?></li>
					<li><a href="#">템플릿</a></li>
					<li><a href="#">둘러보기</a></li>
					<li><a href="#">고객지원</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>