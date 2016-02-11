<?php
include('./application/views/inc/n_header.php');
?>

<div class="container seldMemberWrappers">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
			<div class="article-desc">
				<?=form_open('m/profile', array('class'=>'form-horizontal', 'id'=>'frm-register'))?>
				<div class="row margin-top-100">
					<div class="col-sm-8 col-sm-offset-2 login-frame">
						<div class="login-form">
							<div class="title">User Profile</div>
							<hr>
							<?=$msg == '' ? '' : '<p class="bg-danger" style="padding: 8px;">' . $msg .'</p><br />'?>
							<div class="form-group">
								<label for="firstname" class="col-sm-3 control-label">Name</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname" value="<?=$client->cl_firstname?>" maxlength="100" autofocus>
								</div>
								<div class="col-sm-4 nopadding">
									<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" value="<?=$client->cl_lastname?>" maxlength="100">
								</div>
							</div>
							<div class="form-group">
								<label for="company" class="col-sm-3 control-label">Company</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="company" name="company" placeholder="Company" value="<?=$client->cl_company?>" maxlength="200">
								</div>
							</div>
							<div class="form-group">
								<label for="telephone" class="col-sm-3 control-label">Contact</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="telephone" name="telephone" placeholder="Telephone Number" value="<?=$client->cl_telephone?>" maxlength="20">
								</div>
								<div class="col-sm-4 nopadding">
									<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile Number" value="<?=$client->cl_mobile?>" maxlength="20">
								</div>
							</div>
							<div class="form-group">
								<label for="address1" class="col-sm-3 control-label">Address</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="address1" name="address1" placeholder="Street Address" value="<?=$client->cl_address1?>" maxlength="150">
								</div>
							</div>
							<div class="form-group">
								<label for="address2" class="col-sm-3 control-label">Suburb</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="address2" name="address2" placeholder="Suburb" value="<?=$client->cl_address2?>" maxlength="150">
								</div>
							</div>
							<div class="form-group">
								<label for="address3" class="col-sm-3 control-label">City</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="address3" name="address3" placeholder="State" value="<?=$client->cl_address3?>" maxlength="150">
								</div>
							</div>
							<div class="form-group">
								<label for="postcode" class="col-sm-3 control-label">Post Code</label>
								<div class="col-sm-2">
									<input type="text" class="form-control" id="postcode" name="postcode" placeholder="Post Code" value="<?=$client->cl_postcode?>" maxlength="5">
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label for="email" class="col-sm-3 control-label">Email</label>
								<div class="col-sm-4">
									<strong style="position:relative;top:6px;"><?=$client->cl_email?></strong>
								</div>
							</div>
							<div class="text-warning text-center">Leave the following fields blank if you don't want to change your current password.</div> <br>
							<div class="form-group">
								<label for="password" class="col-sm-3 control-label">Password</label>
								<div class="col-sm-4">
									<input type="password" class="form-control" id="password" name="password" placeholder="Password" value=""  maxlength="50">
								</div>
							</div>
							<div class="form-group">
								<label for="repassword" class="col-sm-3 control-label">Confirm Password</label>
								<div class="col-sm-4">
									<input type="password" class="form-control" id="repassword" name="repassword" placeholder="Confirm Password" value=""  maxlength="50" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<button type="submit" name="submit" value="1" class="btn btn-primary pull-left">Update</button> 
								</div>
							</div>
							<br><br>
						</div>
					</div>
				</div>
				<?=form_close()?>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){

	$('#frm-register').submit(function(){
		if ($('#password').val() != '' && ($('#password').val() != $('#repassword').val())){
			alert('Please confirm your new password!');
			return false;
		}
	});
});
</script>
<?php include('./application/views/inc/n_footer.php') ?>