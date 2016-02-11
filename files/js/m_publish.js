/**
 * this will control the Design Publish Page
 * it contains PRINT order system and MARKET
 */

Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

var design = {
	showMarketForm: function(){
		$('.marketInfo').addClass('hidden');
		$('.marketForm').removeClass('hidden');

		var status = $('#frmAddMarket').attr('data-status');
		if (status == 'listed'){
			$('.marketAddInfoTop, .marketAddTnC').addClass('hidden');
			$('#addToMarketButton span').text('Update');
		}
	},
	validateAddMarket: function(){
		var o = $('#mk_orig_price');
		var v = parseFloat(o.val());
		if (isNaN(v) || v == 0){
			alert('Enter valid price for your design.');
			o.focus();
			return false;
		}

		return true;
	},
	validatePrintOrder: function(){
		var o = $('#printQuantity');
		if (parseInt(o.val()) < 100){
			alert('Minimum Quantity required : 100');
			o.focus();
			return false;
		}

		if (confirm('You need to make a payment to process your order!\r\nClick OK to continue.')){
			return true;
		}
		return false;
	},
	calculateTotal: function(){
		var qty = parseInt($(this).val().trim());
		qty = isNaN(qty) ? 0 : qty;
		var rate = parseFloat($(this).attr('data-rate'));

		var total = qty * rate;
		$('#totalPrintAmount').text(total.format(0, 3));
	},
	init: function(){
		$('.btnAddToMarket').click(design.showMarketForm);
		$('#frmAddMarket').submit(design.validateAddMarket);

		// calculate total.
		$('#printQuantity').keyup(design.calculateTotal);
		$('#frmPrintOrder').submit(design.validatePrintOrder);
	}
}