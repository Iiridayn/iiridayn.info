function ajax(method, url, handler, sync) {
	sync = typeof sync !== 'undefined' ? sync : true;

	xhr = new XMLHttpRequest();
	xhr.open(method, url, sync);
	xhr.onload = handler;
	xhr.send();
	//console.log(xhr.status);
	//console.log(xhr.responseText)
}

// onload
(function () {
	var ids = [];
	var className = 'purchased';
	var show = false;

	// li click to deactivate
	// TODO: un-reserve items
	var liClick = function (event) {
		event = event || window.event;
		if (event.target.tagName === 'A') return;

		var li = this;
		var method = 'PUT';
		if (li.className === className) {
			method = 'DELETE';
		}

		ajax(method, "/api/wishlist/"+li.id, function () {
			// TODO: error messaging, notification of already claimed
			// 201 = created, 200 = claimed, 500 = fail
			if (xhr.status !== 500) {
				if (show) {
					ids.push(li.id);
					// don't change the strike through until mouseout
					li.onmouseout = function () {
						li.className = li.className !== className?className:'';
						li.onmouseout = null;
					}
				}
			}
		});
	}

	// attach li onclick event
	var lis = document.getElementsByTagName('li');
	for (i = 0; i < lis.length; i++) {
		if (lis[i].id) {
			lis[i].onclick = liClick;
		}
	}

	// button click
	var buttonClick = function () {
		var text = button.textContent;
		show = !show;

		if (show) {
			ajax('GET', "/api/wishlist", function () {
				ids = xhr.responseText.trim("\n").split("\n");
			}, false);
			button.textContent = text.replace("Show", "Hide");
		} else {
			button.textContent = text.replace("Hide", "Show");
		}

		// modify purchased items
		for (i = 0; i < ids.length; i++) {
			var li = document.getElementById(ids[i])
			if (!li) continue;
			li.className = show?className:'';
		}
	}

	// button
	var h2 = document.getElementsByTagName('h2')[0];
	var button = document.createElement('button');
	button.textContent = "Show Purchased Items";
	button.onclick = buttonClick;
	h2.parentElement.insertBefore(button, h2);
})();
