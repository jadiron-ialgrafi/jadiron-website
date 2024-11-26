/* Description: Custom JS file */

// البحث عن العنصر بواسطة ID
const goToTop = document.getElementById("goToTop");

// إضافة حدث النقر
goToTop.addEventListener("click", function (event) {
    event.preventDefault(); // منع السلوك الافتراضي للرابط
    
    // التمرير بسلاسة إلى الأعلى
    window.scrollTo({
        top: 0, // التمرير إلى الأعلى
        behavior: "smooth" // التمرير بسلاسة
    });
});


/* Navigation*/
// Collapse the navbar by adding the top-nav-collapse class
window.onscroll = function () {
	scrollFunction();
	scrollFunctionBTT(); // back to top button
};

window.onload = function () {
	scrollFunction();
};

function scrollFunction() {
	if (document.documentElement.scrollTop > 30) {
		document.getElementById("navbar").classList.add("top-nav-collapse");
	} else if ( document.documentElement.scrollTop < 30 ) {
		document.getElementById("navbar").classList.remove("top-nav-collapse");
	}
}

// Navbar on mobile
let elements = document.querySelectorAll(".nav-link:not(.dropdown-toggle)");

for (let i = 0; i < elements.length; i++) {
	elements[i].addEventListener("click", () => {
		document.querySelector(".offcanvas-collapse").classList.toggle("open");
	});
}

document.querySelector(".navbar-toggler").addEventListener("click", () => {
  	document.querySelector(".offcanvas-collapse").classList.toggle("open");
});

// Hover on desktop
function toggleDropdown(e) {
	const _d = e.target.closest(".dropdown");
	let _m = document.querySelector(".dropdown-menu", _d);

	setTimeout(
		function () {
		const shouldOpen = _d.matches(":hover");
		_m.classList.toggle("show", shouldOpen);
		_d.classList.toggle("show", shouldOpen);

		_d.setAttribute("aria-expanded", shouldOpen);
		},
		e.type === "mouseleave" ? 300 : 0
	);
}

// On hover
const dropdownCheck = document.querySelector('.dropdown');

if (dropdownCheck !== null) { 
	document.querySelector(".dropdown").addEventListener("mouseleave", toggleDropdown);
	document.querySelector(".dropdown").addEventListener("mouseover", toggleDropdown);

	// On click
	document.querySelector(".dropdown").addEventListener("click", (e) => {
		const _d = e.target.closest(".dropdown");
		let _m = document.querySelector(".dropdown-menu", _d);
		if (_d.classList.contains("show")) {
			_m.classList.remove("show");
			_d.classList.remove("show");
		} else {
			_m.classList.add("show");
			_d.classList.add("show");
		}
	});
}
  

/* Card Slider - Swiper */
var cardSlider = new Swiper('.card-slider', {
	autoplay: {
		delay: 4000,
		disableOnInteraction: false
	},
	loop: true,
	navigation: {
		nextEl: '.swiper-button-next',
		prevEl: '.swiper-button-prev'
	}
});


/* Filter - Isotope */
const gridCheck = document.querySelector('.grid');

if (gridCheck !== null) { 
	// init Isotope
	var iso = new Isotope( '.grid', {
		itemSelector: '.element-item',
		layoutMode: 'fitRows'
	});

	// bind filter button click
	var filtersElem = document.querySelector('.filters-button-group');
	filtersElem.addEventListener( 'click', function( event ) {
		// only work with buttons
		if ( !matchesSelector( event.target, 'button' ) )  {
			return;
		}
		var filterValue = event.target.getAttribute('data-filter');
		// use matching filter function
		iso.arrange({ filter: filterValue });
	});
	
	// change is-checked class on buttons
	var buttonGroups = document.querySelectorAll('.button-group');
	for ( var i=0, len = buttonGroups.length; i < len; i++ ) {
		var buttonGroup = buttonGroups[i];
		radioButtonGroup( buttonGroup );
	}
	
	function radioButtonGroup( buttonGroup ) {
		buttonGroup.addEventListener( 'click', function( event ) {
			// only work with buttons
			if ( !matchesSelector( event.target, 'button' ) )  {
				return;
			}
			buttonGroup.querySelector('.is-checked').classList.remove('is-checked');
			event.target.classList.add('is-checked');
		});
	}
}


/* Back To Top Button */
// Get the button
myButton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
function scrollFunctionBTT() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		myButton.style.display = "block";
	} else {
		myButton.style.display = "none";
	}
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
	document.body.scrollTop = 0; // for Safari
	document.documentElement.scrollTop = 0; // for Chrome, Firefox, IE and Opera
}
// Initialize PureCounter for Counter Animations in the Statistics Section
function initializeCounters() {
	const counters = document.querySelectorAll('.purecounter');
	counters.forEach(counter => {
		new PureCounter({
			start: 0,
			end: parseInt(counter.getAttribute("data-purecounter-end")),
			duration: parseFloat(counter.getAttribute("data-purecounter-duration")),
			delay: 10,
			once: true,
			scrollDelay: 0
		});
	});
}

// Trigger Counter Animations When Section Is in View (Using Intersection Observer)
if ("IntersectionObserver" in window) {
	let observer = new IntersectionObserver((entries, observer) => {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				initializeCounters();
				observer.unobserve(entry.target); // Stop observing after triggered
			}
		});
	}, { threshold: 0.5 }); // Trigger when 50% visible

	// Observe the statistics section
	document.querySelectorAll(".counter-section").forEach(section => {
		observer.observe(section);
	});
}

document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll(".counter-value");
    const duration = 3000; // Counter duration in milliseconds

    function animateCounters(entries, observer) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const targetValue = +counter.getAttribute("data-target");
                const startValue = 0;
                const startTime = performance.now();

                function updateCounter(currentTime) {
                    const elapsedTime = currentTime - startTime;
                    const progress = Math.min(elapsedTime / duration, 1);
                    const currentValue = Math.floor(progress * targetValue);

                    counter.textContent = currentValue;

                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = targetValue;
                    }
                }

                requestAnimationFrame(updateCounter);
                observer.unobserve(counter); // Stop observing once animation is done
            }
        });
    }

    const observer = new IntersectionObserver(animateCounters, {
        threshold: 0.5,
    });

    counters.forEach((counter) => observer.observe(counter));
});

document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll(".counter-value");
    const duration = 3000; // Counter duration in milliseconds

    function animateCounters(entries, observer) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const targetValue = +counter.getAttribute("data-target");
                const startValue = 0;
                const startTime = performance.now();

                function updateCounter(currentTime) {
                    const elapsedTime = currentTime - startTime;
                    const progress = Math.min(elapsedTime / duration, 1);
                    const currentValue = Math.floor(progress * targetValue);

                    counter.textContent = currentValue;

                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = targetValue;
                    }
                }

                requestAnimationFrame(updateCounter);
                observer.unobserve(counter); // Stop observing once animation is done
            }
        });
    }

    const observer = new IntersectionObserver(animateCounters, {
        threshold: 0.5,
    });

    counters.forEach((counter) => observer.observe(counter));
});

document.addEventListener("DOMContentLoaded", function() {
    const serviceItems = document.querySelectorAll('.service-item');
    
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    serviceItems.forEach(item => {
        observer.observe(item);
    });
});


(function($) {
	// إعداد شريط التفاعل لقياس رضا العملاء
	var satisfactionSlider = document.getElementById('satisfaction-level');
	if (satisfactionSlider != undefined) {
		noUiSlider.create(satisfactionSlider, {
			start: [3],
			step: 1,
			connect: [true, false],
			tooltips: [true],
			range: {
				'min': 1,
				'max': 5
			},
			format: wNumb({
				decimals: 0
			})
		});

		satisfactionSlider.noUiSlider.on('update', function (values) {
			var value = values[0];
			var satisfactionValue = $('#satisfaction-value');
			satisfactionValue.text(value);

			// تحديث الألوان بناءً على القيمة
			if (value == 1) satisfactionValue.css('color', 'red');
			else if (value == 2) satisfactionValue.css('color', 'orange');
			else if (value == 3) satisfactionValue.css('color', 'yellow');
			else if (value == 4) satisfactionValue.css('color', 'yellowgreen');
			else if (value == 5) satisfactionValue.css('color', 'green');
		});
	}
	
	// تهيئة التحقق من صحة النموذج
	$('#register-form').validate({
		rules : {
			name : { required: true },
			phone : { required: true },
			service : { required: true },
			contact_method: { required: true },
			contact_time: { required: true }
		},
		onfocusout: function(element) {
			$(element).valid();
		},
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "هذا الحقل مطلوب"
	});
})(jQuery);


document.addEventListener("DOMContentLoaded", function () {
    const levels = document.querySelectorAll(".satisfaction-level");
    
    levels.forEach((level) => {
        level.addEventListener("click", function () {
            levels.forEach((l) => l.classList.remove("active"));
            this.classList.add("active");
        });
    });
});



document.addEventListener("DOMContentLoaded", () => {
    const images = document.querySelectorAll(".item-img");
    images.forEach((img) => {
        const src = img.getAttribute("data-src");
        if (src) {
            img.style.backgroundImage = `url(${src})`;
        }
    });
});
