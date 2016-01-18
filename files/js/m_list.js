var design = {
	deleteConfirmation: function(){
		if (confirm('Are you sure you want to delete this design?\r\nThis can\'t be undone.')){
			return true;
		}
		return false;
	},	
	init: function(){
		$('a.btnDelete').click(design.deleteConfirmation);
	}
};