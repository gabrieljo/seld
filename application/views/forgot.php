<?php include('inc/n_header.php') ?>

<div class="login">
	<div class="container">
		<?=form_open('p/forgot_password', array('class'=>'form-horizontal', 'id'=>'frm-register'))?>
		<div class="row margin-top-100">
			<div class="col-sm-6 col-sm-offset-3 login-frame">
				<div class="login-form">
					<div class="title">Forgot Password</div>
					<hr>
					<?=$msg == '' ? '' : '<p class="bg-danger" style="padding: 8px;">' . $msg .'</p><br />'?>
					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">Email</label>
						<div class="col-sm-8">
							<input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="" maxlength="200" autoFocus required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-8">
							<button type="submit" name="submit" value="1" class="btn btn-primary pull-left">Reset Password</button> 
							<?=anchor('p/login', 'Login', array('class'=>'btn btn-link pull-right'))?>
						</div>
					</div>
					<br><br>
				</div>
			</div>
		</div>
		<?=form_close()?>
	</div>
</div>
<?php include('inc/n_footer.php') ?>