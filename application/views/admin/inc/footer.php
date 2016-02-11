	</div>
</div> <!-- .row > .container-fluid -->

<!-- Modal -->
<div class="modal fade" id="modalAbout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">SELD</h4>
			</div>
			<div class="modal-body">
				<div class="text-primary">
					SELD was developed by Creative Edge. <br>
					&copy; <?=date('Y')?>. All rights reserved.
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
	$('a.link_about').click(function(){
		$('#modalAbout').modal('show');
		return false;
	});
});
</script>
</body>
</html>