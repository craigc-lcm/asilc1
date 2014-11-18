<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Simple Auto Installment Loan Amortization</title>

<!--<link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css">-->
<!--<script type="text/javascript" src="./assets/js/jquery.min.js"></script>-->

<link rel="stylesheet" href="./assets/css/asilc.css" /> 
<link rel="stylesheet" href="./assets/css/jquery.mobile.icons.min.css" /> 
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile.structure-1.4.2.min.css" /> 

<script type="text/javascript" src="./assets/js/jquery.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script> 

</head>
<body>

<div data-role="page" id="stickerPrice">
	<div data-role="header" data-position="fixed">
		<h1>ASILC</h1>
		<div data-role="navbar">
		<ul>
			<li><a href="#stickerPrice">Sticker Price</a></li>
			<li><a href="#tradeIn">Trade In Offer</a></li>
			<li><a href="#bannerMessage" data-rel="dialog" data-transition="pop">Info</a></li>
		</ul>
		</div>		
	</div><!-- /header -->
	
	<div data-role="content">
		<div class="content-primary">

		<div data-demo-html="true">
			<label class="text-success">Monthly Payment $</label>
			<input type="text" id="periodic_payment" class="form-control" />
		</div>

		<div data-demo-html="true">
			<label class="text-success">Annual Interest Rate %</label>
			<input type="text" id="apr" class="form-control" />
		</div>

		<div data-demo-html="true">
			<label class="text-success">Number of Months</label>
			<input type="text" id="number_of_months" class="form-control" />
		</div>

		<div data-demo-html="true">
			<label class="text-success">Trade In Value/Down Payment (optional) $</label>
			<input type="text" id="down_payment" class="form-control" />
		</div>

		<div data-demo-html="true">
			<label class="text-success">Sticker Price $</label>
			<input type="text" id="initial_cost" class="form-control" disabled="disabled" />
		</div>

		<div data-demo-html="true">
			<label class="text-success">Amount Financed $</label>
			<input type="text" id="principle" class="form-control" disabled="disabled" />
		</div>

		<button data-role="button" class="ui-shadow ui-btn ui-corner-all" id="process_button">Calculate</button>
	
		</div><!-- /primary-content -->
	</div><!-- /content -->

	<div data-role="footer" data-position="fixed">
		<h4>ASILC</h4>
	</div><!-- /footer -->
</div><!-- /page -->


<div data-role="page" id="tradeIn">
	<div data-role="header" data-position="fixed">
		<h1>ASILC</h1>
		<div data-role="navbar">
		<ul>
			<li><a href="#stickerPrice">Sticker Price</a></li>
			<li><a href="#tradeIn">Trade In Offer</a></li>
			<li><a href="#bannerMessage" data-rel="dialog" data-transition="pop">Info</a></li>
		</ul>
		</div>		
	</div><!-- /header -->
	
	<div data-role="content">
		<div class="content-primary">

		<div data-demo-html="true">
			<label class="text-success">Monthly Payment $</label>
			<input type="text" id="periodic_payment1" class="form-control" />
		</div>

		<div data-demo-html="true">
			<label class="text-success">Annual Interest Rate %</label>
			<input type="text" id="apr1" class="form-control" />
		</div>

		<div data-demo-html="true">
			<label class="text-success">Number of Months</label>
			<input type="text" id="number_of_months1" class="form-control" />
		</div>

		<div data-demo-html="true">
			<label class="text-success">Sticker Price $</label>
			<input type="text" id="sticker_price1" class="form-control"/>
		</div>

		<div data-demo-html="true">
			<label class="text-success">Trade In Value/Down Payment $</label>
			<input type="text" id="down_payment1" class="form-control" disabled="disabled" />
		</div>

		<button data-role="button" class="ui-shadow ui-btn ui-corner-all" id="process1_button">Calculate</button>
	
		</div><!-- /primary-content -->
	</div><!-- /content -->
	<div data-role="footer" data-position="fixed">
		<h4>ASILC</h4>
	</div><!-- /footer -->
</div><!-- /page -->



<!-- Start of third page: #bannerMessage -->
<div data-role="page" id="bannerMessage">

	<div data-role="header" data-theme="a">
		<h1>Info</h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<p>All Calculations use the California use/sales tax rate in Orange County of 8.00%.  License is 0.655% and all combined California fees is $101.</p>


		<p><a href="#main" data-rel="back" class="ui-btn ui-shadow ui-corner-all ui-btn-inline ui-icon-back ui-btn-icon-left">Go Back</a></p>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>ASILC</h4>
	</div><!-- /footer -->
</div><!-- /page popup -->

<script type="text/javascript">
jQuery(document).ready(function() {
	$( "#process_button" ).click(function() {
		var formdata = {
			apr: parseFloat(jQuery("#apr").val())/100,
			number_of_months: jQuery("#number_of_months").val(),
			periodic_payment: jQuery("#periodic_payment").val(),
			down_payment: jQuery("#down_payment").val()			
		};
	
		//determine
		jQuery.ajax({   
			type: "POST",
			data : formdata,
			cache: false,  
			url: "./results.php", 
			success: function(data) {
					var values = JSON.parse(data);
					if(values['status'] === "ok") {
						jQuery("#principle").val(values['principle']);
						jQuery("#apr").val(values['apr']*100);
						jQuery("#number_of_months").val(Math.ceil(values['number_of_months']));
						jQuery("#periodic_payment").val(values['periodic_payment']);
						jQuery("#total_interest").val(values['total_interest']);	
						jQuery("#initial_cost").val(values['initial_cost']);
					} else {
						alert(values['error']);
					}
				},
			async: true
		});
		return false;
	});

	$( "#process1_button" ).click(function() {
		var formdata = {
			apr: parseFloat(jQuery("#apr1").val())/100,
			number_of_months: jQuery("#number_of_months1").val(),
			periodic_payment: jQuery("#periodic_payment1").val(),
			sticker_price: jQuery("#sticker_price1").val()			
		};
	
		//determine
		jQuery.ajax({   
			type: "POST",
			data : formdata,
			cache: false,  
			url: "./results1.php", 
			success: function(data) {
					var values = JSON.parse(data);
					if(values['status'] === "ok") {
						jQuery("#apr1").val(values['apr']*100);
						jQuery("#number_of_months1").val(Math.ceil(values['number_of_months']));
						jQuery("#periodic_payment1").val(values['periodic_payment']);
						jQuery("#sticker_price1").val(values['sticker_price']);
						jQuery("#down_payment1").val(values['down_payment']);
					} else {
						alert(values['error']);
					}
				},
			async: true
		});
		return false;
	});
});
</script>
</body>
</html>
