appViewModel = null;

branchWiseServices = [];

var viewModel = function(){
	
	this.PersonID = ko.observable();
	this.PersonName = ko.observable();
	this.selectedBranch = ko.observable();
	
	this.persons = ko.observableArray();
	this.branches = ko.observableArray();
	
	
        
};

var BranchWiseService = function(BranchID, ServiceID, ServiceName)
{
        this.BranchID = BranchID;
        this.ServiceID = ServiceID;
        this.ServiceName = ServiceName;
};

var Person = function(PersonID, name, branch_id, branch_name, department, designation)
{
	this.PersonID = PersonID;
	this.name = name;
        this.branch_id = branch_id;
	this.branch_name = branch_name;
	this.department = department;
	this.designation = designation;
        
        this.availableServices = ko.observableArray([]);
        
	this.assignedService = ko.observable();
	
	this.willDisplay = ko.observable(true);
};

var Branch = function(id, name)
{
	this.id = id;
	this.name = name;
}

var Service = function(id, name)
{
	this.id = id;
	this.name = name;	
}



$(document).ready(function(){

	appViewModel = new viewModel();
	
	ko.applyBindings(appViewModel);

        populateBranchWiseServices();

        
	
	//populateBranchesDropDown();
        
        
	

});

function populatePersonsList()
{
	$.ajax({
	  url: "/ebl/index.php/BranchWisePersonSetup/getPersonList",
	  type: 'GET'
	}).done(function(data) {
	
		data = data.contacts;
                
                data.forEach(function(singleData){
						var person = new Person(singleData.Id, singleData.PersonName, singleData.BranchID, singleData.BranchName, singleData.DepartmentName, singleData.DesignationName);
			
						console.log("for person");
			
						console.log(singleData.ServiceID);
						
						
			
                        if(singleData.ServiceID != null)
                            {
								console.log("for service");
								//person.assignedService(new Service(singleData.ServiceID, singleData.ServiceName));
                            }
                            
                        branchWiseServices.forEach(function(singleService){
                            
                            if(singleService.BranchID == person.branch_id)
                                {
                                    person.availableServices.push(new Service(singleService.ServiceID, singleService.ServiceName));
                                }
                            
                        });
                        
                        appViewModel.persons.push(person);
		});
	});
}

function populateBranchesDropDown()
{
	$.ajax({
	  url: "/ebl/index.php/BranchSetup/getBranches",
	  type: 'GET'
	}).done(function(data) {
	
                data = data.branches;
        
		data.forEach(function(singleData){
		
			var branch = new Branch(singleData.Id, singleData.BranchName);
			appViewModel.branches.push(branch);
		});
		
		populatePersonsList();
	});
}


function populateBranchWiseServices()
{
$.ajax({
	  url: "/ebl/index.php/ServicesSetup/getBranchWiseServices",
	  type: 'GET'
	}).done(function(data) {
	
                data = data.services;
        
		data.forEach(function(singleData){
		
                        var branchWiseService = new BranchWiseService(singleData.BranchID, singleData.ServiceID, singleData.ServiceName);
                
			branchWiseServices.push(branchWiseService);
		});
                populateBranchesDropDown();
                
	});    
}




function filterPersonsList()
{

	var i;
	
	for(i=0;i<appViewModel.persons().length; i++)
	{
		var person = appViewModel.persons()[i];
		
		person.willDisplay(false);
		
		if((appViewModel.PersonID() == null || appViewModel.PersonID() == "") && (appViewModel.PersonName() == null || appViewModel.PersonName() == "") && (appViewModel.selectedBranch() == null || appViewModel.selectedBranch() == ""))
		{
			person.willDisplay(true);
		}
		else
		{
			if(person.PersonID == appViewModel.PersonID()) person.willDisplay(true);
			if(person.name == appViewModel.PersonName()) person.willDisplay(true);
			if(appViewModel.selectedBranch() != null && appViewModel.selectedBranch != "" && person.branch_name == appViewModel.selectedBranch().name) person.willDisplay(true);			
			
		}
		
	}

}


function save()
{
	
	var postData = {
		service_persons : []
	};
	
	
	for(i=0;i<appViewModel.persons().length; i++)
	{
		if(appViewModel.persons()[i].willDisplay() == false) continue;
	
		var data = {
			personID : appViewModel.persons()[i].PersonID,
			serviceID : appViewModel.persons()[i].assignedService().id,
                        branchID : appViewModel.persons()[i].branch_id
		};
		
		postData.service_persons.push(data);
		
	}
	
	
	console.log(postData);
	
	
	$.ajax({
        type: 'POST',
        url: '/ebl/index.php/ServiceWisePersonSetup/insertDataFromJSON',
        data:  (postData),
        //contentType: 'application/json',
        //dataType: 'json',
        success: function(data){
			if(data == "1") window.location = "/ebl/index.php/ServiceWisePersonSetup/index";
		},
        failure: function(errMsg) {
            console.log(errMsg);
        }
	});
	
}

