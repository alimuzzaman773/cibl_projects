appViewModel = null;

branchWiseServices = [];

var viewModel = function(){
	
	this.persons = ko.observableArray();
	
};



var Person = function(PersonID, name, branch_id, branch_name, service_id, service_name, department_name, designation_name)
{
	this.PersonID = PersonID;
	this.name = name;
    this.branch_id = branch_id;
	this.branch_name = branch_name;
	this.department_name = department_name;
	this.designation_name = designation_name;
	this.service_id = service_id;
	this.service_name = service_name;

};


$(document).ready(function(){

        appViewModel = new viewModel();
	
		ko.applyBindings(appViewModel);

        populatePersonsList();
	
	
});

function populatePersonsList()
{
	$.ajax({
	  url: "/ebl/index.php/ServiceWisePersonSetup/getPersonList",
	  type: 'GET'
	}).done(function(data) {
	
		data = data.contacts;
                
                console.log(data);
                
                data.forEach(function(singleData){
			var person = new Person(singleData.Id, singleData.PersonName, singleData.BranchID, singleData.BranchName, singleData.ServiceID, singleData.ServiceName, singleData.DepartmentName, singleData.DesignationName);
			
                        appViewModel.persons.push(person);
		});
	});
}






