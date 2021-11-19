document.addEventListener("DOMContentLoaded", () => {

    // Global functions
    navShrink();
    smoothScroll();
    openVideoDialog();

    // MOBILE
    toggleNav();
    toggleSearchField();
});

function toggleSearchField() {

    // Fetch page defaults
    var searchInput = getByClass('is-search-input');
    var searchSection = getByClass('search-section');

    // Click listener
    $('.trg--search').click(function() {

        if (searchSection.classList.contains('closed')) {

            // Remove closed class
            searchSection.classList.remove('closed');
            // Focus on input
            searchInput.focus();

            $('.trg--search').addClass('active');


        } else {

            // Add closed class
            searchSection.classList.add('closed');

            $('.trg--search').removeClass('active');
        }
    });
}

function smoothScroll() {

    // Select all links with hashes
    $('a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        // .not('.tab-nav')
        .click(function(event) {
            // On-page links
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
                location.hostname == this.hostname
            ) {
                // Figure out element to scroll to
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                // Does a scroll target exist?
                if (target.length) {
                    // Only prevent default if animation is actually gonna happen
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80,
                    }, 700, function() {});
                }
            }
        });
}

// Detect scroll for nav shrinking
function navShrink() {

    window.onscroll = function() {

        var navbar = get('header');

        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {

            navbar.classList.add('shrink');

        } else {

            navbar.classList.remove('shrink');
        }
    };
}

// Models & Functions
// Function that shows and hides the navigation when the
// navicon icon is clicked. It is triggered from the
// event listener attached to the navicon icon.
function toggleSiteNavigation(navicon) {

    // Fetch page defaults
    var siteNavSection = get('nav');

    if (!navicon.classList.contains('open')) {

        // Remove active from navicon
        navicon.classList.add('open');

        // Open site navigation
        siteNavSection.classList.remove('closed');
        // siteNavSection.classList.add('opened');

        // Disable Scrolling
        disableScroll();

    } else {

        // Add active class
        navicon.classList.remove('open');

        // Close site navigation
        siteNavSection.classList.add('closed');
        // siteNavSection.classList.remove('opened');

        // Enable Scrolling
        enableScroll();
    }
}

function toggleNav() {
    var navicon = getByClass('trg--nav');

    // Event listener for mobile navigation
    navicon.addEventListener('click', function() {

        // Call toggle site navigation model
        toggleSiteNavigation(navicon);
    });
}

var scrollPosition = '';
// Stop scrolling
function disableScroll() {
    // Stop scrolling on desktop devices
    getByTag('body').style.overflow = 'hidden';

    if (getViewportWidth() < 1080) {
        // Stop scrolling for mobile devices
        scrollPosition = window.pageYOffset;
        getByTag('body').style.position = 'fixed';
        getByClass('main').style.position = 'relative';
        getByClass('main').style.top = '-' + scrollPosition + 'px';
    }
}

// Start scrolling
function enableScroll() {
    // Enable scrolling on desktop devices
    getByTag('body').style.overflow = 'visible';

    if (getViewportWidth() < 1080) {
        // Enable scrolling for mobile devices
        getByTag('body').style.position = 'static';
        getByClass('main').style.position = 'static';
        getByClass('main').style.top = 'auto';
        window.scrollTo(0, scrollPosition);
    }
}








function openVideoDialog() {

    $('.open--video-dialog').click(function() {

        // Get clicked data
        var videoID = $(this).data('video-id');
        var iframe = '';

        // YouTube embed
        // mute=2 -> it's a bug - but it works
        iframe = '<iframe src="https://www.youtube.com/embed/' + videoID + '?rel=0" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

        // Place video frame inside dialog
        $('.video-iframe').html(iframe);

        // Open dialog
        openDialog('.video-dialog')

        // Listen to close dialog
        closeVideoDialog();
    });
}

function closeVideoDialog() {

    $('.close--video-dialog').click(function(e) {

        if (e.target === this) closeDialog('.video-dialog', true);
    });
}

function openDialog(dialog) {
    $(dialog).parent().removeClass('closed');

    // Disable Scrolling
    disableScroll();
}

function closeDialog(dialog, video = false) {
    // Close dialog and overlay
    $(dialog).parent().addClass('closed');

    // Clear video content
    if (video) {
        $('.video-iframe').html('');
    }

    // Enable Scrolling
    enableScroll();
}



// Detect esc key & close nav if opened
document.onkeydown = function(evt) {

    evt = evt || window.event;
    if (evt.keyCode == 27) {

        $('.scroll-dialog').each(function() {
            if (!$(this).hasClass('closed')) {
                evt.preventDefault();
                $(this).click();
            }
        });
    }
};



function get(elementId) {

    // Fetch element by its ID attribute
    return document.getElementById(elementId);
}

function getAllByClass(className) {

    // Fetch all elements by their className
    return document.getElementsByClassName(className);
}

function getByClass(className) {

    // Fetch elements by their className and return the first result
    return getAllByClass(className)[0];
}

function getByTag(tagName) {

    // Fetch elements by their className and return the first result
    return document.getElementsByTagName(tagName)[0];
}

// Get the viewport width
function getViewportWidth() {
    return Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
}