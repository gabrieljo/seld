<?php
$title = isset($title) ? $title : SITE_TITLE;
$keyword = isset($keyword) ? $keyword : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<title><?=$title?></title>
	<link rel="icon" type="image/png" href="<?=base_url()?>files/img/favicon.png">
	<?php
	// Load all CSS/JS framework
	$load = array(
				'bootstrap.min.css',
				'style_admin.css',
				'jquery.js',
				'bootstrap.min.js'
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

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<?=anchor('a', 'SELD Admin', array('class'=>'navbar-brand'))?>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
			<li><?=anchor('a', '<span class="glyphicon glyphicon-th-large"></span> Dashboard')?></li>
			<li><?=anchor('a/articles', '<span class="glyphicon glyphicon-user"></span> Articles')?></li>
			<li><?=anchor('a/users', '<span class="glyphicon glyphicon-user"></span> Users')?></li>
			<li><?=anchor('a/report', '<span class="glyphicon glyphicon-cd"></span> Report')?></li>
			<li><?=anchor('a', '<span class="glyphicon glyphicon-info-sign"></span> About', array('class'=>'link_about'))?></li>
			<li><?=anchor('a/logout', '<span class="glyphicon glyphicon-off"></span> Logout')?></li>
			</ul>
			<form class="navbar-form navbar-right hidden" id="frmSearch" method="post">
				<input type="text" name="keyword" class="form-control" placeholder="Search..." value="<?=$keyword?>">
				<input type="hidden" name="frm_search" value="1">
			</form>
		</div>
	</div>
</nav>

<div class="container-fluid">
	<div class="row">
	