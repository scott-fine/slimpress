// Show on toggle
var show = (toggleContent, toggleButton) => {
  toggleContent.classList.remove('hide');
  toggleContent.classList.add('show');
  // So menu resizes responsively
  toggleContent.style.height = '';
  toggleButton.setAttribute('aria-expanded', 'true');
};

// Hide on toggle
var hide = (toggleContent, toggleButton) => {
  toggleContent.classList.remove('show');
  toggleContent.classList.add('hide');
  toggleButton.setAttribute('aria-expanded', 'false');
};

// Toggle visibility
var toggle = (toggleContent, toggleButton) => {
	// If content is visible, hide it
	if (toggleContent.classList.contains('show')) {
		hide(toggleContent, toggleButton);
		return;
	}
	// Otherwise, show it
	show(toggleContent, toggleButton);
};

window.onload = () => {

  var toggleButton = document.getElementById('toggle');
  var toggleContent = document.getElementById('toggle-content');

  toggleButton.addEventListener('click', function (event) {
  	if (!toggleContent) return;
  	toggle(toggleContent, toggleButton);
  });

  //Responsive resize listener
  window.addEventListener('resize', function() {
      if(window.innerWidth < 992) {
        if(toggleContent.classList.contains('show')) {
          toggle(toggleContent, toggleButton);
        }
      }
      else {
        if(toggleContent.classList.contains('hide')) {
          toggle(toggleContent, toggleButton);
        }
      }
  });
}
