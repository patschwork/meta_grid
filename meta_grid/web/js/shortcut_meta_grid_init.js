$(document).ready(function()
{
	// Search
    shortcut.add("ctrl+shift+f", function() {
		location.href="index.php?r=mapper/appglobalsearch&q=";
    }); 
	
	// Go home
    shortcut.add("ctrl+shift+h", function() {
		location.href="index.php";
    });
	
	var pos=location.href.indexOf("index.php?r=");
	var viewPath=location.href.substring(pos,99999);
	// conditional view shortcuts
	if (pos>0) {
		if (viewPath.indexOf("%2F")<=0 && viewPath.indexOf("/")<=0) {
			// Create in index view
			shortcut.add("ctrl+shift+i", function() {
				location.href=location.href + "/create";
			});
		}
		// Update in view
		if (viewPath.indexOf("view&")>0) {
			shortcut.add("ctrl+shift+u", function() {
				location.href=location.href.replace("view&",'update&');
			});

			// Select "Comments" tab
			var elementId="tabComments";
			var elementIdHash="#" + elementId;
			var aHref='a[href="' + elementIdHash + '"]';
			shortcut.add("alt+c", function() {
				$(aHref).tab('show');
				var element_to_scroll_to = document.getElementById(elementId);
				element_to_scroll_to.scrollIntoView();
			});

			// Select "Mapping" tab
			var elementId="tabMapping";
			var elementIdHash="#" + elementId;
			var aHref='a[href="' + elementIdHash + '"]';
			shortcut.add("alt+m", function() {
				$(aHref).tab('show');
				var element_to_scroll_to = document.getElementById(elementId);
				element_to_scroll_to.scrollIntoView();
			});

			// Select "Fields" tab
			var elementId="tabFields";
			var elementIdHash="#" + elementId;
			var aHref='a[href="' + elementIdHash + '"]';
			shortcut.add("alt+f", function() {
				$(aHref).tab('show');
				var element_to_scroll_to = document.getElementById(elementId);
				element_to_scroll_to.scrollIntoView();
			});

			// New Comment
			shortcut.add("alt+shift+c", function() {
				$("#btnCreateNewComment")[0].click();
			});
		}
		// Save in view create or update
		if (viewPath.indexOf("update&")>0 || viewPath.indexOf("create")>0) {
			shortcut.add("ctrl+shift+s", function() {
				document.forms["w0"].submit();
			});
		}
		// Create in index view
		if (viewPath.indexOf("/index")>0 || viewPath.indexOf("%2Findex")>0) {
			shortcut.add("ctrl+shift+i", function() {
				location.href=location.href.replace(viewPath, viewPath.replace("%2Findex","/create").replace("/index","/create"));
			});
		}
	}
});