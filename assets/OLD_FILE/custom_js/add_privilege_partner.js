appViewModel = null;

var viewModel = function(){
	
	this.privilegePartnerName = ko.observable();
	
	this.privilegePartnerAddress = ko.observable();
	
	this.privilegeTypes = ko.observableArray();
	
	
	this.selectedTypes = ko.observableArray([]);
	
	
};


var PrivilegeType = function(id, name)
{
	this.id = id;
	this.name = name;	
}


$(document).ready(function(){
	appViewModel = new viewModel();
	
	ko.applyBindings(appViewModel);
	
	populatePrivilegeTypes();
	
});


function populatePrivilegeTypes()
{
	$.ajax({
	  url: "/ebl/index.php/PrivilegeTypeSetup/getPrivilegeTypes",
	  type: 'GET'
	}).done(function(data) {
	
		data = data.types;
	
		data.forEach(function(singleData){
		
			var privilegeType = new PrivilegeType(singleData.PrivilegeTypeID, singleData.PrivilegeTypeName);
			
			appViewModel.privilegeTypes.push(privilegeType);
		});
	});
}



function save()
{
	
	var postData = {
		privilegePartnerName : appViewModel.privilegePartnerName(),
		privilegePartnerAddress : appViewModel.privilegePartnerAddress(),
		selectedTypes : appViewModel.selectedTypes()
	};
	
	console.log(postData);
	
	$.ajax({
        type: 'POST',
        url: '/ebl/index.php/PrivilegePartnerSetup/insertDataFromJSON',
        data:  postData,
        success: function(data){
			if(data == "1") window.location = "/ebl/index.php/PrivilegePartnerSetup/index";
			//console.log(data);
		},
        failure: function(errMsg) {
            console.log(errMsg);
        }
	});
	
}

