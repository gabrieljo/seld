var step = {
	initCheck: function(){
		// Make sure all the ajax-dependent are hidden
		$('.ajax_load').parent().parent().addClass('hidden');

		var total = $('.control-form').length;
		var ajax = $('.control-form.ajax_load').length;
		//console.log(total, ajax);
		$('.control-form.ajax_load').each(function(){
			var o 		= $(this);
			var ref_id 	= $(this).attr('data-dep-id');
			var ref_val = $(this).attr('data-dep-val').toLowerCase();
			var ref 	= $('#form-control-' + ref_id);

			if (!ref.parent().parent().hasClass('hidden') && ref.val().toLowerCase() == ref_val){
				o.parent().parent().removeClass('hidden');
				//console.log(o.attr('data-id'), ref.val(), ref_val);
			}
		});
	},
	changeCheck: function(){
		//console.log('checking');
		step.initCheck();
	},
	validate: function(){
		$('.control-form.ajax_load').each(function(){
			var o = $(this);			
			if (!o.parent().parent().hasClass('hidden')){
				var name = o.attr('data-name');
				o.attr('name', name);
			}
			else{
				o.parent().parent().remove();
			}
		});
	},
	init: function(){
		step.initCheck();
		$('.control-form').change(step.changeCheck);
		$('#frmSettings').submit(step.validate);
	}
};