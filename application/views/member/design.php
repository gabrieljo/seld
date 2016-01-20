<?php include('inc/m_header.php') ?>

<?php
if ($page != 's4-design' && $page != 's4-canvas'):
?>
<div class="aside">
	<?php include('inc/m_editor_menu.php') ?>
</div><!-- .aside -->
<?php
endif;
?>
<div class="article">
	<div id="body-wrapper">
		<?php include("m_design/{$page}.php") ?>
	</div>
</div><!-- .article -->
<div class="cf"></div>

<?=inc("{$page}.js")?>
<script>$(step.init)</script>
<?php include('inc/m_footer.php') ?>