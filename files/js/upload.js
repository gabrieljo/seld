$(function(){
	myUploadData = new Array();
 	// Total allowed image upload.
 	var totalImage 			= 50;
 	var uploadUrl			= $('.image-upload-main').data('ref');

 	var settings = {
	    url: 			uploadUrl + 'u/upload',
	    method: 		"POST",
	    allowedTypes: 	"jpg,jpeg,png,gif",
	    fileName: 		"myfile",
	    multiple: 		false,
	    maxFileCount: 	totalImage,
	    beforeSend:function(){
	    	return false;
	    },
	    onSuccess:function(files,data,xhr){
	    	console.log('here');
	        $("#status").html("<font color='green'>Upload completed</font>");
	        myUploadData.push(data);
	    },
	    afterUploadAll:function(){
	    	console.log('here');
	        after_upload();
	        $('.upload-statusbar').remove();
	    },
	    onError: function(files,status,errMsg){  
	    console.log('here');      
	        $("#status").html("<font color='red'>Upload has Failed</font>");
	    }
	};

	$("#image_uploader").uploadFile(settings);
});

/**
 * This method will add the image uploads to the display section.
 * for step 3
 */
function after_upload(){
	if (myUploadData != null){
		var photo 	= '';
		var base_url= $('.image-upload-main').data('ref');
		for (i=0; i<myUploadData.length; i++){
			var arr 	= $.parseJSON(myUploadData[i]);
			$.each(arr, function(k, v){
				photo += '<li><div class="img-wrapper"><img src="' + base_url + v + '" /></div></li>';
				var ob = $('input[name="frm_photos"]');
				vl = ob.val(ob.val() + ',' + k);
			});
		}
		$('#my-images-list').append(photo);
		myUploadData = new Array();
		// image
		$('.image-options h3 span[data-ref="image-options-select"]').trigger('click');
	}
}