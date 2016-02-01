<?php 
include('inc/n_header.php');

$heading = isset($heading) ? $heading : '404 Page Not Found';
$message = isset($message) ? $message : 'The page you requested was not found. It may have been removed or been moved to another location.';
$class 	 = isset($class) ? 'text-'.$class : 'text-info';
?>

<div class="response main">
	<div class="container">
		<div class="row">
			<div class="col-sm-offset-2 col-sm-8">
				<h1 class="<?=$class?> text-center"><?=$heading?></h1>
				<hr>
				<p class="<?=$class?> text-center"><?=$message?></p>
			</div>
		</div>
	</div>
</div>

<?php include('inc/n_footer.php') ?>