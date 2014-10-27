function ajax(method, url, handler) {
	xhr = new XMLHttpRequest();
	xhr.open(method, url);
	xhr.onload = handler;
	xhr.send();
	//console.log(xhr.status);
	//console.log(xhr.responseText)
}

// onload
(function () {
	var ids = [];
	var lis = document.getElementsByTagName('li');

	// hover strikethrough
	// @see: http://stackoverflow.com/a/524721/118153
	var hoverStyle = function () {
		var css = "li[id]:hover { text-decoration: line-through; }";
		var style = document.createElement('style');
		style.id = 'hoverStyle';
		if (style.styleSheet)
			style.styleSheet.cssText=css;
		else
			style.appendChild(document.createTextNode(css));
		document.head.appendChild(style);
	};

	// li click to deactivate
	// TODO: un-reserve items
	var liClick = function () {
		// TODO - if a link in the li was clicked, don't count it
		var id = this.id;
		ajax('PUT', "/api/wishlist/"+id, function () {
			// TODO: error messaging
			// 201 = created, 200 = claimed, 500 = fail
			//console.log(xhr.status);
			if (xhr.status !== 500) {
				ids.push(id);
				var element = document.getElementById(id)
				element.style["text-decoration"] = "line-through";
			}
		});
	}

	// button click
	var buttonClick = function () {
		var decoration = "line-through";
		var text = button.textContent;

		if (text.substr(0, 4) === "Hide") {
			decoration = "";
			button.textContent = text.replace("Hide", "Show");

			var element = document.getElementById('hoverStyle');
			element.parentNode.removeChild(element);
		} else {
			button.textContent = text.replace("Show", "Hide");

			hoverStyle();
		}

		// strike out taken items
		for (i = 0; i < ids.length; i++) {
			var element = document.getElementById(ids[i])
			if (element) {
				element.style["text-decoration"] = decoration;
			}
		}

		// li onclick event
		for (i = 0; i < lis.length; i++) {
			if (lis[i].id) {
				if (decoration)
					lis[i].onclick = liClick;
				else
					lis[i].onclick = null;
			}
		}
	}

	// button
	var h2 = document.getElementsByTagName('h2')[0];
	var button = document.createElement('button');
	button.textContent = "Show Purchased Items";
	button.onclick = buttonClick;
	button.disabled = true;
	h2.parentElement.insertBefore(button, h2);

	ajax('GET', "/api/wishlist", function () {
		ids = xhr.responseText.trim("\n").split("\n");
		button.disabled = false;
	});
})();
