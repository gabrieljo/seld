var register = {
	validateForm: function(){
		var o = $('#company');
		if ($.trim(o.val()) == ''){
			o.parent().parent().addClass('has-error');
		}
		else{
			o.parent().parent().removeClass('has-error');
		}
		o = $('#email');
		if ($.trim(o.val()) == '' || !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(o.val()))){
			o.parent().parent().addClass('has-error');
		}
		else{
			o.parent().parent().removeClass('has-error');
		}
		o = $('#password');
		var o2 = $('#repassword');
		if ($.trim(o.val()) == '' || (o.val() != o2.val())){
			o.parent().parent().addClass('has-error');
			o2.parent().parent().addClass('has-error');
		}
		else{
			o.parent().parent().removeClass('has-error');
			o2.parent().parent().removeClass('has-error');
		}
		
		// focus on first error
		if ($('#frm-register .form-group.has-error').length > 0){
			$('#frm-register .form-group.has-error:eq(0)').find('.form-control').select();
		}
		else{
			if ($('#tnc:checked').length != 1){
				alert('You must agree to our terms and conditions to continue...');
			}
			else{
				return true;				
			}
		}
		return false;
	},
	checkAvailability:function(){
		o = $('#email');
		if ($.trim(o.val()) != '' && (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(o.val()))){
			$.post(base_url()+'p/check_availability', {email:$('#email').val(), id:''}).done(register.checkAvailabilityAction);
		}	
	},
	checkAvailabilityAction:function(data){
		o = $('#email');
		if (data == 'unavailable'){
			o.parent().parent().removeClass('has-success').addClass('has-error');
		}
		else{
			o.parent().parent().removeClass('has-error').addClass('has-success');
		}
	},
	showTnC: function(){
		$('#myModal').modal('show');
	},
	init: function(){
		$('#frm-register').submit(register.validateForm);
		$('#email').blur(register.checkAvailability);
		$('.readtnc').click(register.showTnC);
	}
};