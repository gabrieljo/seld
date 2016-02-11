// JavaScript Document
// dependant on jquery.js
function addslashes(str){
	if(empty(str)) return '';
	filterchars = new Array("'", '"');
	$.each(filterchars, function(i, v){
		if(str.indexOf(v)>=0){						 
			str = str.replace(v, '\\'+v);
			alert(str);
		}
	});	
	return str;
}

function prepareQuerystring(frmObj){
	 //prepare querystring
	 var parastr = "";
	 
	 $(':input', frmObj).each(function(i) {
		if ((this.type == 'radio') || (this.type == 'checkbox')) {
               if (this.checked && !this.disabled) {
                    parastr += this.name + "=" + encodeURIComponent($.trim(this.value)) + "&";
               }
        } 
		else if(this.type != 'button' && $(this).attr('isWysiwyg') != '1'  && !this.disabled) {
			
			if($(this).tagName() == 'select'){
				
				if($(this).attr('multiple')){
					val = sep = '';
					$(this).find(':selected').each(function(i, selected){
						val += sep+encodeURIComponent($(selected).val());
						sep = ',';
					});	
					parastr += this.name + "=" + encodeURIComponent($.trim(val)) + "&";			
				}
				else{
					parastr += this.name + "=" + $(this).val() + "&";	
					
				}
			}			
			else{
               parastr += this.name + "=" + encodeURIComponent($.trim(this.value)) + "&";
			}
        }
	 });
	 
	 $('textarea', frmObj).each(function(i) {
		if ($(this).attr('isWysiwyg') == '1') {
               
               parastr += this.name + "=" + encodeURIComponent($.trim(getContents(this.id))) + "&";
        } 
		
	 });
    
     return parastr = parastr.substr(0, parastr.length-1);
}

function resetForm(frmObj, callback_func) {
	// iterate over all of the inputs for the form element that was passed in
	$(':input', frmObj).each(function() {
		var type = this.type;
		var tag = this.tagName.toLowerCase(); // normalize case
		
		if (type == 'text' || type == 'password')	this.value = "";
		else if ( tag == 'textarea'){	this.innerHTML = ""; this.value ='';}
		else if (type == 'checkbox' || type == 'radio')		this.checked = false;
		// select elements need to have their 'selectedIndex' property set to -1 (this works for both single & multiple select)
		else if (tag == 'select')	this.selectedIndex = 0;
		
		if(callback_func) eval(callback_func);
	});
	
	
	$('textarea', frmObj).each(function(i) {
		if ($(this).attr('isWysiwyg') == '1') {
               
              setContents(this.id, '');
        } 
		
	 });
	
};

function populateForm(frmObj, processing_page, itemid){
	if(frmObj){
		$.ajax({
			type: 'get',
			dataType: 'string',
			url: BASEURL4JS+'process/'+processing_page+'.php?action=populateData&id='+itemid,
			success: function(resp){				
				$(frmObj).populate(eval(resp));	
			}
		});
	}
}



function errorify(obj, whichmsg){
	
	if(!whichmsg) var whichmsg = 0; //if multiple notification boxes for a single input is present, it lets u choose one
	if(obj && obj.attr('id').indexOf('recaptcha')>-1){
		$('#recaptchaError'+whichmsg).show();
		$('#recaptchaLabel').addClass('redlabel');
	}
	else{
		if(obj){
			obj.parent().find('label').addClass('redlabel');
			obj.parent().find("span[class*='input-error']:eq("+whichmsg+")").show();
		}
	}

}


function de_errorify(obj){

	if(obj && obj.attr('id') && obj.attr('id').indexOf('recaptcha')>-1){
		$('#recaptchaError1').hide();
		$('#recaptchaError2').hide();
		$('#recaptchaLabel').removeClass('redlabel');
	}
	else{
		if(obj && typeof obj.attr('id') != "undefined"){
			obj.parent().find('label').removeClass('redlabel');
			obj.parent().find("span[class*='input-error']").hide();
		}
	}
}


function changeNotificationBoxClass(obj, changeto){

	if(obj && changeto){
		obj.removeClass('error');
		obj.removeClass('success');
		obj.removeClass('attention');
		obj.removeClass('information');
		obj.addClass(changeto);
		obj.css('opacity', '1');
	}
	

}


function showRecaptcha(element, themeName) {
		
	Recaptcha.create(RECAPTCHA_PUB_KEY, element, {
		theme: themeName,
		tabindex: 0/*,
		callback: Recaptcha.focus_response_field*/
	});
	$('#recaptcha_table #recaptcha_response_field').attr('required', '1');
		  
}
		
		
				
function destroyRecaptchaWidget() {
   
   Recaptcha.destroy();
   
}


//remove item (string or number) from an array

function removeItem(originalArray, itemToRemove) {

	var j = 0;
	
	while (j < originalArray.length) {
		
		if (originalArray[j] == itemToRemove) {
		
			originalArray.splice(j, 1);
		
		} else { j++; }
	
	}

	return originalArray;

}

//remove one or more items from the given array
//var toRemove = [17,2];
// it can be done with one item only as well
// var toRemove = 5;
//theArray.removeItems(toRemove);


Array.prototype.removeItems = function(itemsToRemove) {

    if (!/Array/.test(itemsToRemove.constructor)) {
        itemsToRemove = [ itemsToRemove ];
    }

    var j;
    for (var i = 0; i < itemsToRemove.length; i++) {
        j = 0;
        while (j < this.length) {
            if (this[j] == itemsToRemove[i]) {
                this.splice(j, 1);
            } else {
                j++;
            }
        }
    }
}



//prototype for finding the associative array length. assoc_array.length just returns 0
function arraySize(arr) {
	var length = 0;
	for (var object in arr) {
		length++;
	}
	return length;
}


// usage example:
// flds = new Array('uname', 'passwd', 'email', 'recaptcha_response_field');
// validateForm(flds);


function alreadyErrorified(myErrorsObj, validationRules, skip){
	
	validationRules.removeItems(skip);
	for(i=0; i< validationRules.length; i++){
		
		if(myErrorsObj[validationRules[i]]){
			return true;
		}
		
	}
	
}


function validateForm(){	

	validationRules = new Array('required', 'editor-required', 'regex', 'email', 'numeric', 'min-length', 'max-length', 'checked', 'confirm', 'group', 'orWith', 'url', 'date_fromto');
	flds = arguments[0];
	frmObj = arguments[1];
	$.each(flds, function(){ de_errorify($(this, frmObj)); });
	
	var myErrors = new Object(); 
		
	if(flds && flds.length){
		for(i = 0; i<flds.length; i++){
			
			obj 	= $(flds[i], frmObj);
			objid 	= obj.attr('id');
			objTag 	= obj.tagName();
			
			val 	= obj.val();
			if(objTag == 'select'){
				val = '';
				$('#'+objid+' :selected').each(function(i, selected){
					val += $(selected).val();
				});
			}
			
			
			
			if(obj.length>1 && obj.attr('group'))
				obj = obj.eq(0);
			
			for(j=0; j<validationRules.length; j++){

				objAttr = obj.attr(validationRules[j]);
 
				attrib = new Array();
				if(objAttr){
					
					switch(validationRules[j]){
						
						case 'required': 
								if(empty(val)){
									de_errorify(obj); 
									errorify(obj, 0);
									attrib['required'] = 1; 
									myErrors[objid]=attrib;
									
								}
								else{
									
									de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['required'])
									delete myErrors[objid]['required'];
								}
									
								break;
						
						case 'editor-required': 
								if(validationRules[j] == 'editor-required'){									
									
									val=strip_tags(getContents(obj.attr('id'))); 
									
								}
								if(empty(val)){
									de_errorify(obj); 
									errorify(obj, 0);
									attrib['editor-required'] = 1; 
									myErrors[objid]=attrib;
									
								}
								else{
									
									de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['editor-required'])
									delete myErrors[objid]['editor-required'];
								}
									
								break;
											
						
															
						case 'regex': 		
								if(!empty(val) && !val.toString().match(new RegExp(objAttr))){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['regex'] = 1; 
									myErrors[objid]=attrib;
									
								}
								else if(!empty(val)){
								
									/*if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j]))*/ de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['regex'])
									delete myErrors[objid]['regex'];
									
								}
								break;
											
						case 'email': 		
								if(!empty(val) && !isemail(val)){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['email'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else if(!empty(val)){
								
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j])) de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['email'])
									delete myErrors[objid]['email'];
																					
								}												
								break;
											
						case 'numeric': 	
						
								if(!empty(val) && isNaN(val)){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['numeric'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else if(!empty(val)){
								
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j])) de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['numeric'])
									delete myErrors[objid]['numeric'];
																					
								}
								break;
											
						case 'min-length':  
								if(!empty(val) && val.length < parseInt(objAttr)){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['min-length'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else if(!empty(val)){
								
									//if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j])) 
									de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['min-length'])
									delete myErrors[objid]['min-length'];
																					
								}	
								break;
											
						case 'max-length':  
								if(!empty(val) && val.length > parseInt(objAttr)){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['max-length'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else if(!empty(val)){
								
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j])) de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['max-length'])
									delete myErrors[objid]['max-length'];
																					
								}
								break;
											
						case 'checked':  	
								if(!obj.attr('checked')){
									
									errorify(obj, 0);
									attrib['checked'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else{
								
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j])) de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['checked'])
									delete myErrors[objid]['checked'];
																					
								}
								break;
													
						case 'date':  		
								if(!empty(val) && !isDate(val)){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['date'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else if(!empty(val)){
								
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j])) de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['date'])
									delete myErrors[objid]['date'];
																					
								}
								break;
											
						case 'confirm': 

								if(!empty(val) && $('#'+objAttr, frmObj).val() != val){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['confirm'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else if(empty(val) && !empty($('#'+objAttr, frmObj).val())){
									de_errorify(obj); 
									errorify(obj, 0);
									attrib['confirm'] = 1; 
									myErrors[objid]=attrib;
								}
								else{
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j]))
										de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['confirm'])
									delete myErrors[objid]['confirm'];
																					
								}
								break;
											
						case 'group':  		
								if((!$("input[group='"+objAttr+"']:checked", frmObj).length)){
									
									errorify(obj, 0);
									attrib['group'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else{
								
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j])) de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['group'])
									delete myErrors[objid]['group'];
																					
								}
								break;
											
						case 'url':  		
								if(!empty(val) && !isWebUrl(val)){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['url'] = 1; 
									myErrors[objid]=attrib;
								
								}
								else if(!empty(val)){
								
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j])) de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['url'])
									delete myErrors[objid]['url'];
																					
								}
								break;
								
						//attribute must be in the from field	
						case 'date_fromto':  
								
								if(!empty(val) && days_between(val, $('#'+objAttr, frmObj).val())<0){
									de_errorify(obj); 
									errorify(obj, 1);
									attrib['date_fromto'] = 1; 
									myErrors[objid]=attrib;
								
								}								
								else{
									if(myErrors[objid] && !alreadyErrorified(myErrors[objid], validationRules, validationRules[j]))
										de_errorify(obj); 
									if(myErrors[objid] && myErrors[objid]['date_fromto'])
									delete myErrors[objid]['date_fromto'];
																					
								}
								break;
						
						case 'orWith':  	
								if(empty(val)){
									
									optionals = objAttr.split(' ');
									
									objs = new Array();
									
									for(m=0; m<optionals.length; m++){
										
										_obj = eval(optionals[m]);
										de_errorify(_obj);
										
										if(myErrors[objid] && myErrors[objid]['orWith'])
										delete myErrors[objid]['orWith'];
										
										if(empty(_obj.val())){
											
											objs.push(_obj);
											
										}
										
									}
									if(objs.length == optionals.length){
										
										for(n=0; n < objs.length; n++){
											
											obj = objs[n];
											errorify(obj, 2);
											attrib['orWith'] = 1; 
											myErrors[objid]=attrib;
											
										}
									}
								}
								break; 
								//checks if anyone is checked in a group of radiobuttons
					}			
				}
			}
		}
	}
	
	if(arraySize(myErrors))
	{
		//scroll the page upto the error message
		caller = $('.input-error:visible:eq(0)');
		//console.log(caller);
		callerTopPosition = $(caller).offset().top-30;
		callerleftPosition = $(caller).offset().left;
		$.scrollTo(callerTopPosition, callerleftPosition, {easing: 'jswing'});
		return false;	
	}
	return true;
	
}


function hide_sd(opt){
		
	if(opt && opt == 'instantly')
		$('.sd_wrapper').hide();
	else
		$('.sd_wrapper').slideUp(500);

}

//plugin for retrieving the tagname of the given jquery object
$.fn.tagName = function() {
	if(this.get(0))
    return this.get(0).tagName.toLowerCase();

}


function sort_table(objCol, orderby, xtraparam){
	
	var queryString = null;	
	queryString += '&orderby='+orderby;
	queryString += '&order='+objCol.attr('order');
	queryString += '&action=sort';
	if(typeof xtraparam != "undefined")
	queryString += xtraparam;
	
	refill_table(null, queryString);
}

function current_page(context){
	
	if(context){
		if($('.pagination a.current', context)){
			var pg = $('.pagination a.current', context).text();				
		}
	}
	else{
		if($('.pagination a.current')){			
			var pg = $('.pagination a.current').text();				
		}
	}	
	return typeof pg != "undefined" && pg!='' ? pg : 1;
}

function populateStates(objSrc, objTarget, objLoadingStates){


	cntryArr = ['Australia', 'Canada', 'Ireland', 'United Kingdom', 'United States'];
	var cid = objSrc.find('option').filter(':selected').val();
	var selectedCountry = objSrc.find('option').filter(':selected').text();

	if($.inArray(selectedCountry, cntryArr) >= 0){
		
		objLoadingStates.show();
		
		$.ajax({
			type: "post",
			url: BASEURL+'country_selection.php',
			data: 'cid='+cid,
			dataType:'string',		
			success: function(reval){
				objTarget.html(reval);
				objLoadingStates.hide();
			},
			error: function(reval){
				objLoadingStates.hide();
			}
		});		
		
	}
	else{
		
		objTarget.html("<option value='0'>None</option>");
		objTarget.find('option').removeAttr('selected');
		objTarget.find('option:eq(1)').attr('selected', true);
		objLoadingStates.hide();
	}
	
			
}