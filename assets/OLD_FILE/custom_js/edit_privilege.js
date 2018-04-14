appViewModel = null;
PrivilegeID = null;

var viewModel = function(){
	
	this.privilegeTypes = ko.observableArray();
	this.selectedPrivilegeType = ko.observable();
	
	this.privilegePartners = ko.observableArray();
	this.selectedPrivilegePartner = ko.observable();
	
	this.products = ko.observableArray();
	
	
	this.privilegeProductMapping = ko.observableArray();
	
	this.removePrivilegeProductMapping = function(item)
	{
		this.privilegeProductMapping.remove(item);
	}.bind(this);
	
};

var PrivilegeType = function(id, name)
{
	this.id = id;
	this.name = name;
}

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

var PrivilegeProductMapping = function(product, description)
{
	this.product = ko.observable(product);
	this.description = ko.observable(description);
}

$(document).ready(function(){
	appViewModel = new viewModel();
	
	ko.applyBindings(appViewModel);
	
	populatePrivilegeTypes();
	populatePrivilegePartners();
	populateProductItems();
	getExistingPrivilegeDetails();
	
	appViewModel.privilegeProductMapping.push(new PrivilegeProductMapping(null, ""));
});

function populatePrivilegeTypes()
{
	$.ajax({
	  url: "/ebl/index.php/PrivilegeTypeSetup/getAllPrivilegeTypes",
	  type: 'GET'
	}).done(function(data) {
	
		data.forEach(function(singleData){
			var privilegeType = new PrivilegeType(singleData.PrivilegeTypeID, singleData.PrivilegeTypeName);
			appViewModel.privilegeTypes.push(privilegeType);
		});
	});
}

function populatePrivilegePartners()
{
	$.ajax({
	  url: "/ebl/index.php/PrivilegePartnerSetup/getAllPrivilegePartners",
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
	  url: "/ebl/index.php/ProductSetup/getProductsAsJSON",
	  type: 'GET'
	}).done(function(data) {
	
		data.forEach(function(singleData){
			var product = new Product(singleData.Id, singleData.ProductName);
			appViewModel.products.push(product);
		});
	});
}

function getExistingPrivilegeDetails()
{
	var url_array = document.URL.split("/");
	
	//console.log(url_array[url_array.length - 1]);
	
	PrivilegeID = (url_array[url_array.length - 1]);
	
	$.ajax({
	  url: "/ebl/index.php/PrivilegeSetup/get/" + PrivilegeID,
	  type: 'GET'
	}).done(function(data) {
	
		console.log(data);
		var singleData = data[0];
		//data.forEach(function(singleData){
			
			//console.log(singleData);
			
			var privilegePartner = new PrivilegePartner(singleData.PrivilegePartnerID, singleData.PrivilegePartnerName);
			appViewModel.selectedPrivilegePartner(privilegePartner);
			
			var privilegeType = new PrivilegeType(singleData.PrivilegeTypeID, singleData.PrivilegeTypeName);
			appViewModel.selectedPrivilegeType(privilegeType);
			
			//appViewModel.products([]);
			
			console.log(privilegePartner);
			console.log(privilegeType);
			console.log(singleData.PrivilegeTypeID);
			console.log(singleData.PrivilegePartnerID);
		//});
	});
}

function addnewproduct()
{
	appViewModel.privilegeProductMapping.push(new PrivilegeProductMapping(null, ""));
}


function save()
{
	
	var postData = {
		privilegeType : appViewModel.selectedPrivilegeType(),
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
			description : product.description()
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

