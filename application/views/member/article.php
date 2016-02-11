<?php
include('./application/views/inc/n_header.php');
?>

<div class="container seldMemberWrapper">
	<div class="row">
		<div class="col-sm-8">
			<h3><?=$article->art_title?></h3>
			<hr>
			<div class="article-desc">
				<?=$article->art_contents?>
			</div>
		</div>
		<div class="col-sm-4">
			<h4>Related Articles</h4>
			<hr>
			<ul class="record-list">
				<?php
				foreach ($articles->result() as $article){
					echo '<li>' . anchor('m/article/' . $article->art_id . '/'. url_title($article->art_title), $article->art_title) . '</li>';
				}
				?>
			</ul>
		</div>
	</div>
</div>


<script>
$(function(){

});
</script>
<?php include('./application/views/inc/n_footer.php') ?>