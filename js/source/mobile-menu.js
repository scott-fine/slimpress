// Mobile nav menu

// Show on toggle
var showNav = (toggleContent, toggleButton) => {
  toggleContent.classList.remove('hide');
  toggleContent.classList.add('show');
  toggleButton.setAttribute('aria-expanded', 'true');
};

// Hide on toggle
var hideNav = (toggleContent, toggleButton) => {
  toggleContent.classList.remove('show');
  toggleContent.classList.add('hide');
  toggleButton.setAttribute('aria-expanded', 'false');
};

// Toggle visibility
var toggle = (toggleContent, toggleButton) => {
	// If content is visible, hide it
	if (toggleContent.classList.contains('show')) {
		hideNav(toggleContent, toggleButton);
		return;
	}
	// Otherwise, show it
	  showNav(toggleContent, toggleButton);
};

var mobileNav = () => {

  var toggleButton = document.getElementById('toggle');
  var toggleContent = document.getElementById('toggle-content');

  toggleButton.addEventListener('click', function (event) {
  	if (!toggleContent) return;
  	toggle(toggleContent, toggleButton);
  });

  // Responsive resize listener
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
};
