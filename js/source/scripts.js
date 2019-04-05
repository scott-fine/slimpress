// Show an element
var show = function (elem, toggle_btn) {

  elem.classList.remove('hide');
  toggle_btn.setAttribute("aria-expanded", "true");

	// Get the natural height of the element
	var getHeight = function () {
		elem.style.display = 'block'; // Make it visible
		var height = elem.scrollHeight + 'px'; // Get it's height
		elem.style.display = ''; //  Hide it again
		return height;
	};

	var height = getHeight(); // Get the natural height
	elem.classList.add('show'); // Make the element visible
	elem.style.height = height; // Update the max-height

	// Once the transition is complete, remove the inline max-height so the content can scale responsively
	window.setTimeout(function () {
		elem.style.height = '';
	}, false);

};

// Hide an element
var hide = function (elem, toggle_btn) {

  elem.classList.add('hide');
  toggle_btn.setAttribute("aria-expanded", "false");

	// Give the element a height to change from
	elem.style.height = elem.scrollHeight + 'px';
	// When the transition is complete, hide it
	window.setTimeout(function () {
		elem.classList.remove('show');
	}, false);

};

// Toggle element visibility
var toggle = function (elem, toggle_btn) {

	// If the element is visible, hide it
	if (elem.classList.contains('show')) {
		hide(elem, toggle_btn);
		return;
	}
	// Otherwise, show it
	show(elem, toggle_btn);

};

window.onload = function() {

  var toggle_btn = document.getElementById('toggle');
  var content = document.getElementById('toggle-content');

  toggle_btn.addEventListener('click', function (event) {
  	if (!content) return;
  	toggle(content, toggle_btn);
  }, false);

  window.addEventListener("resize", function(){
     if(window.innerWidth < 992){
        if(content.classList.contains('show')){
          toggle(content, toggle_btn);
        }
     }
     else {
         if(content.classList.contains('hide')){
           toggle(content, toggle_btn);
         }
     }
  });

}
