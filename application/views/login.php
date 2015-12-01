<?php include('inc/header.php') ?>
<?=inc('login.js')?>

<div class="login">
	<div class="container">
		<div class="row">
			<div class="col-sm-offset-3 col-sm-6">
				<?=form_open('p/login', array('class'=>'form-horizontal', 'id'=>'frm-login'))?>
					<h3 class="text-success"><small><span class="glyphicon glyphicon-lock"></span></small> Sign In</h3>
					<div class="text-muted">Sign in using social network.</div>
					<div class="social-login">
						<a href="#" class="btn btn-info">Login with Twitter</a>
						<a href="#" class="btn btn-primary">Login with Facebook</a>
					</div>

					<div class="text-muted">Sign in using your registerd account.</div>
					<?=$message?>
					<div class="form-group">
						<label for="text" class="col-sm-3 control-label">Email</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="email" name="email" placeholder="Email Address" value="<?=$form['cl_email']?>" autofocus>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" value="" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-8">
						<div class="checkbox">
							<label>
								<?=form_checkbox('remember', '1', $form['cl_remember'])?> Keep me signed in
							</label>
						</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<button type="submit" class="btn btn-primary pull-left">Login</button> 
							<?=anchor('p/register', 'Register', array('class'=>'btn btn-link pull-right'))?>
							<?=anchor('p/forgot_password', 'Forgot Password', array('class'=>'btn btn-link pull-right'))?>
						</div>
					</div>
				<?=form_close()?>
			</div>
		</div>
	</div>
</div>
<script>$(login.init)</script>
<?php include('inc/footer.php') ?>