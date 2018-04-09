$(document).ready(function()
{
	var tree_data;

	$.ajax({
	  url: "getTreeData",
	  type: 'GET'
	}).done(function(data) {
	
	  /*data.forEach(function(singleData){
		
		singleData.label = singleData.label + " " + "<a href='index/read/"+singleData.id+"'>Read</a>" + " " + "<a href='index/edit/"+singleData.id+"'>Read</a>" + " " + "<a href='index/delete/"+singleData.id+"'>Read</a>"
		console.log(singleData);
	  });*/
	
	  tree_data = data;
	  //console.log(data);
	  
	  $(function() {
		$('#tree').tree({
        data: tree_data
		});
	  });
	});
	
	$('#tree').bind(
		'tree.dblclick',
		function(event) {
			//console.log(event.node);
			//window.location = "index/read/" + event.node.id;
		}
	);
	
	$('#tree1').bind(
		'tree.click',
		function(event) {
			
		}
	);

	
	$('#tree1').bind(
		'tree.select',
		function(event) {
			if (event.node) {
				// node was selected
			}
			else {
				// event.node is null
				// a node was deselected
				// e.previous_node contains the deselected node
			}
		}
	);
});