<?php
$title = isset($title) ? $title : SITE_TITLE;

// Nav Links
/*$navs = array(
			/*base_url() 			=> 'Home',
			'p/about/SelD' 		=> 'About Us',
			'p/contact'			=> 'Contact',
			'p/login'			=> 'Login'* /
			'p/login' 	=> 'SELD 하기',
			'p/about' 	=> '둘러보기',
			'p/market'	=> 'SELD 마켓',
			'p/order' 	=> 'SELD 의뢰하기',
		);*/

$navs = array();
$navs['p/about'] 	= 'SELD 시작하기';
// $navs['p/about'] 	= '둘러보기';
$navs['market'] 	= 'SELD 마켓';
$navs['market/cart'] 	= 'SELD 의뢰하기';

$quick_links = array();
$id = !get_cookie(APP_MEMBER.'_member') ? null : get_cookie(APP_MEMBER.'_member');

if ($id === null){
	$quick_links['p/login'] 	= '로그인';
	$quick_links['m/register'] = '회원가입';
	$home_link = '';
}
else{
	$quick_links['m/logout'] 	= 'Logout';
	$quick_links['m/'] = '내정보';
	$home_link = 'm';
}


?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<title><?=$title?></title>
	<link rel="icon" type="image/png" href="<?=base_url()?>files/img/favicon.png">
	<?php
	if (isset($meta)){
		echo $meta;
	}
	?>
	<?php
	// Load all CSS/JS framework
	$load = array(
				'bootstrap.min.css',
				'font-awesome.min.css',
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
			<div class="col-xs-3">
				<?=anchor('', inc('main/logo.png'))?>
			</div>
			<div class="col-xs-6">
			<div id="deco-top-line"></div>
			<div>
				<ul class="menu">
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
					echo '<li role="presentation" ' . $cls . '>' . anchor($k, $v) . '<div class="hover-underline"></div></li>';
				endforeach;
				?>
				</ul>
			</div>
				
			</div>
			<div class="col-xs-3">
				<ul class="user-menu">
					<?php
					foreach ($quick_links as $k=>$v){
						echo '<li>' . anchor($k, $v) . '</li>';
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>