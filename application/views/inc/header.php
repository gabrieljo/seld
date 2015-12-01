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
				'style.css',
				'jquery.js',
				'jquery.mousewheel.js',
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

<div class="header">
	<div class="container">
		<div class="row">
			<div class="col-xs-4">
				<?=anchor('', '<h1>'. inc('logo.png') . '</h1>')?>
			</div>
			<div class="col-xs-8">
				<ul class="nav pull-right">
				<?php
				$cur = $this->uri->segment(1).'/'.$this->uri->segment(2);
				foreach ($navs as $k=>$v):
					$cls = '';
					if ($cur == '/'){
						$cls = $v == 'Home' ? 'class="active"' : '';
					}
					else{
						$cls = strpos($k, $cur) !== false ? 'class="active"' : '';
					}
					echo '<li role="presentation" ' . $cls . '>' . anchor($k, $v) . '</li>';
				endforeach;
				?>
				</ul>
			</div>
		</div>
	</div>
</div>