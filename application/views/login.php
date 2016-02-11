<?php include('inc/n_header.php') ?>
<?=inc('login.js')?>

<script>
(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=946996452002869";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<div class="login">
	<div class="container">
		<div class="row margin-top-100">
			<div id="login-main-bg" class="col-sm-offset-3 col-sm-6">
				<?=inc('login/login-main-bg.png')?>
				<div class="login-desc">
					<div class="title">MEMBERS ZONE</div>
					<div class="sub-title">CLASSIC STYLE EVERYWHERE, EVERY TIME</div>
				</div>
			</div>
		</div>
		<?=form_open('p/login/' . $tb, array('class'=>'form-horizontal', 'id'=>'frm-login'))?>
		<div class="row margin-top-100">
			<div class="col-sm-6 login-frame">
				<div class="login-form">
					<div class="title">회원가입</div>
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
				</div>
			</div>
			<div class="col-sm-6">
					<div class="text-muted text-center">Sign in using social network.</div>
					<div class="social-login">
						<button type="button" class="btn btn-danger btn-social btnSocialLogin" data-mode="google">
							Login with Google+
						</button>
					</div>
					<div class="social-login">
					<button type="button" class="btn btn-primary btn-social btnSocialLogin" data-mode="facebook">
						Login with Facebook
					</button>
					</div>
					<div class="social-login">
						<a href="#" class="btn btn-success btn-social">Login with Naver</a>
					</div>

					<div class="text-muted text-center">Sign in using your registerd account.</div>					
			</div>
		</div>
		<input type="hidden" name="login_mode" id="login_mode" value="seld">
		<input type="hidden" name="login_oath" id="login_oath" value="">
		<?=form_close()?>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="SocialLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-nm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body">
				<div class="social-login-container" id="social-login-facebook">
					<div class="text-center">
						<?=inc('icon-login-facebook.png', array('class'=>'btnLoginFacebook', 'style'=>'height:120px;cursor:pointer;'))?>
					</div>
					<p class="bg-danger text-center hidden" id="fbLoginError" style="padding:20px;">SELD needs your <strong> email address </strong>to login. <br> Please try again and allow SELD to receive your email address.</p>
					<hr>
					<p class="text-primary text-center">
						SELD requires your <strong>Email Address</strong> for you to be able to login to SELD. <br>
						SELD requires only your <strong>Fullname</strong> and <strong>email address.</strong>
						<br>
						SELD will not post anything on facebook.
					</p>
				</div>
				<div class="social-login-container" id="social-login-google">
					<div class="text-center">
						<div class="g-signin2" data-onsuccess="onSignIn" style="text-align:center;margin:30px 0;"></div>
					</div>
					<p class="bg-danger text-center hidden" id="googleLoginError" style="padding:20px;">SELD needs your <strong> email address </strong>to login. <br> Please try again and allow SELD to receive your email address.</p>
					<hr>
					<p class="text-danger text-center">
						SELD requires your <strong>Email Address</strong> for you to be able to login to SELD. <br>
						SELD requires only your <strong>Fullname</strong> and <strong>email address.</strong>
						<br>
						SELD will not post anything on your Google+.
					</p>
				</div>
				<div class="social-login-container" id="social-login-naver">N</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="https://apis.google.com/js/platform.js" async defer></script>
<script>$(login.init)</script>
<script>
	$(function(){
		$('.btnSocialLogin').click(function(){
			var mode = $(this).attr('data-mode');
			$('.social-login-container').addClass('hidden');
			$('#social-login-' + mode).removeClass('hidden');

			var title = mode == 'facebook' ? 'Facebook Login' : (mode == 'google' ? 'Google+ Login' : 'Naver Login');

			$('#SocialLoginModal .modal-title').text(title);

			$('#SocialLoginModal').modal('show');
			return false;
		});

		$('.btnLoginFacebook').click(facebook.login);
	});

	var facebook = {
		login: function(){
			FB.login(function(response){
				if (response.status == 'connected' || response.status === 'not_authorized'){
					// check for EMAIL ADDRESS
					facebook.validate();
				}
				else{
					//console.log('failed')
				}
			}, {scope: 'email'});
		},
		validate: function(){
			FB.api('/me?fields=email,name,first_name,last_name', function(response){

				if (response == null){return false;}

				var id 			= response.id;
				var first_name 	= response.first_name;
				var last_name 	= response.last_name;
				var email 		= response.email;

				if (email == null || id == null){
					$('#fbLoginError').removeClass('hidden');
				}
				else{
					var me = JSON.stringify(response);
					$('#login_mode').val('facebook');
					$('#login_oath').val(me);

					// submit form
					$('#email, #password').val(email);
					$('form#frm-login').submit();
				}
			});
		}
	};

	var googlePlus = {
		signOut: function(){

		    var auth2 = gapi.auth2.getAuthInstance();
		    auth2.signOut().then(function () {
		      //console.log('User signed out.');
		    });
		},
		validate: function(googleUser){

			var profile = googleUser.getBasicProfile();
			if (profile == null){return false;}

			var id 			= profile.getId();
			var first_name 	= profile.getGivenName();
			var last_name 	= profile.getFamilyName();
			var email 		= profile.getEmail();

			if (email == null || id == null){
				$('#googleLoginError').removeClass('hidden');
			}
			else{
				var me = {
					id: id,
					first_name: first_name,
					last_name: last_name,
					email: email
				};
				var me = JSON.stringify(me);

				$('#login_mode').val('google');
				$('#login_oath').val(me);

				googlePlus.signOut();

				// submit form
				$('#email, #password').val(email);
				$('form#frm-login').submit();
			}
		}
	};

	function onSignIn(googleUser){
		googlePlus.validate(googleUser);
	}
	
</script>
<style>
.abcRioButtonLightBlue{margin: auto;}
</style>

<?php include('inc/n_footer.php') ?>