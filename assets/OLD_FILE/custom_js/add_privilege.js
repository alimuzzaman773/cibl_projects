appViewModel = null;

var viewModel = function(){
	
	this.privilegePartners = ko.observableArray();
	
	this.privilegeTypes = ko.observableArray();
	
	this.selectedPrivilegePartner = ko.observable();
	
	this.products = ko.observableArray();
	
	
	this.privilegeProductMapping = ko.observableArray();
	
	this.removePrivilegeProductMapping = function(item)
	{
		this.privilegeProductMapping.remove(item);
	}.bind(this);
	
};



var Product = function(id, name)
{
	this.id = id;
	this.name = name;
}

var PrivilegePartner = function(id, name)
{
	this.id = id;
	this.name = name;	
}

var PrivilegeType = function(id,name)
{
	this.id = id;
	this.name = name;	
}

var PrivilegeProductMapping = function(product, privilegeType, description)
{
	this.product = ko.observable(product);
	this.privilegeType = ko.observable(privilegeType);
	this.description = ko.observable(description);
}

$(document).ready(function(){
	appViewModel = new viewModel();
	
	ko.applyBindings(appViewModel);
	
	populatePrivilegePartners();
	populatePrivilegeTypes();
	populateProductItems();
	
	appViewModel.privilegeProductMapping.push(new PrivilegeProductMapping(null, null, ""));
});


function populatePrivilegePartners()
{
	$.ajax({
	  url: "/ebl/index.php/PrivilegePartnerSetup/getPrivilegePartners",
	  type: 'GET'
	}).done(function(data) {
	
		data.forEach(function(singleData){
		
			var privilegePartner = new PrivilegePartner(singleData.PrivilegePartnerID, singleData.PrivilegePartnerName);
			
			appViewModel.privilegePartners.push(privilegePartner);
		});
	});
}


function populateProductItems()
{
	$.ajax({
	  url: "/ebl/index.php/ProductSetup/getProducts",
	  type: 'GET'
	}).done(function(data) {
	
		data.forEach(function(singleData){
		
			console.log(singleData);
		
			var product = new Product(singleData.ProductID, singleData.ProductName);
			appViewModel.products.push(product);
		});
	});
}


function populatePrivilegeTypes()
{
	$.ajax({
	  url: "/ebl/index.php/PrivilegeTypeSetup/getPrivilegeTypes",
	  type: 'GET'
	}).done(function(data) {
	
		var data = data.types;
		
		data.forEach(function(singleData){
		
			var privilegeType = new PrivilegeType(singleData.PrivilegeTypeID, singleData.PrivilegeTypeName);
			
			appViewModel.privilegeTypes.push(privilegeType);
		});
	});
}

function addnewproduct()
{
	appViewModel.privilegeProductMapping.push(new PrivilegeProductMapping(null, null, ""));
}


function save()
{
	
	var postData = {
		privilegePartner : appViewModel.selectedPrivilegePartner()
	};
	
	var products_temp = appViewModel.privilegeProductMapping();
	var products = [];
	
	for(var i=0; i<products_temp.length; i++)
	{
		//console.log("a");
		var product = products_temp[i];
		//console.log(product.product().id);
		//console.log(product.product().name);
		//console.log(product.description());
		
		var product_ = 
		{ 
			productid : product.product().id,
			productname : product.product().name,
			description : product.description(),
			privilegetypeid : product.privilegeType().id
		}
		
		products.push(product_);
	}
	
	postData.products = products;
	
	//console.log(postData);
	
	
	$.ajax({
        type: 'POST',
        url: 'insertDataFromJSON',
        data:  /*JSON.stringify*/(postData),
        //contentType: 'application/json',
        //dataType: 'json',
        success: function(data){
			if(data == "1") window.location = "/ebl/index.php/PrivilegeSetup/index";
		},
        failure: function(errMsg) {
            console.log(errMsg);
        }
	});
	
}

