$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    
	// scroll to bottom of page wenn "add" clicked
	window.scrollTo(0,document.body.scrollHeight);
    
    // replace title from "Field details" to an icon with a pencil
	items=document.getElementsByName('field_details_h3_name');
	item=items[items.length-1];
	item.innerHTML='<i class="glyphicon glyphicon-pencil"></i>';
    
    // fade background color to green to highlight for the user
	items=document.getElementsByName('panel_heading_name');
	item=items[items.length-1];
	item.style="transition: background-color 2000ms linear;";
	item.style.backgroundColor="#A5FF7F";

	// removes the button for adding a comment (db_table_field object is not created yet to associate with)
	items=document.getElementsByName('btnCreateNewComment');
	item=items[items.length-1];
	item.style.visibility="hidden";

	// {... Fix for false init state of cloned checkboxes ({T60})
	// as default, not checked
	items=document.getElementsByClassName('checkBox_is_PrimaryKey'); // getElementsByName doesn't work this time...
	item=items[items.length-1];
	item.checked=false;
	items=document.getElementsByClassName('checkBox_is_BusinessKey');
	item=items[items.length-1];
	item.checked=false;
	items=document.getElementsByClassName('checkBox_is_GDPR_relevant');
	item=items[items.length-1];
	item.checked=false;
	// ({T60}) ...}
});