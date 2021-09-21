// init Isotope
const element = document.querySelector(".kt-post-grid-wrap");
if ( element.classList.contains("kt-post-grid-layout-grid-wrap") ) {
	var iso = new Isotope( '.kt-post-grid-layout-grid-wrap', {
		itemSelector: '.kt-post-masonry-item',
		layoutMode: 'fitRows'
	});
} else if ( element.classList.contains("kt-post-grid-layout-masonry-wrap") ) {
	var iso = new Isotope( '.kt-post-grid-layout-masonry-wrap', {
		itemSelector: '.kt-post-masonry-item',
		layoutMode: 'masonry'
	});
}

  // bind filter button click
  var filtersElem = document.querySelector('.kb-post-filter-container');
  filtersElem.addEventListener( 'click', function( event ) {
	// only work with buttons
	if ( !matchesSelector( event.target, 'button' ) ) {
	  return;
	}
	var filterValue = event.target.getAttribute('data-filter');
	// use matching filter function
	iso.arrange({ filter: filterValue });
  });

  // change is-checked class on buttons
  var buttonGroups = document.querySelectorAll('.kb-post-filter-container');
  for ( var i=0, len = buttonGroups.length; i < len; i++ ) {
	var buttonGroup = buttonGroups[i];
	radioButtonGroup( buttonGroup );
  }

  function radioButtonGroup( buttonGroup ) {
	buttonGroup.addEventListener( 'click', function( event ) {
	  // only work with buttons
	  if ( !matchesSelector( event.target, 'button' ) ) {
		return;
	  }
	  buttonGroup.querySelector('.is-active').classList.remove('is-active');
	  event.target.classList.add('is-active');
	});
  }
