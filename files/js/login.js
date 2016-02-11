var login = {
	validateForm: function(){
		var o = $('#email');
		if ($.trim(o.val()) == '' || !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(o.val()))){
			o.parent().parent().addClass('has-error');
		}
		else{
			o.parent().parent().removeClass('has-error');
		}
		o = $('#password');
		if ($.trim(o.val()) == ''){
			o.parent().parent().addClass('has-error');
		}
		else{
			o.parent().parent().removeClass('has-error');
		}
		
		// focus on first error
		if ($('#frm-login .form-group.has-error').length > 0){
			$('#frm-login .form-group.has-error:eq(0)').find('.form-control').select();
		}
		else{			
			return true;				
		}
		return false;
	},
	init: function(){
		$('#frm-login').submit(login.validateForm);
	}
};