<script>

window.addEventListener('load', function () {

// VIDEO PLAYER | WATCHED FUNTIONS

var player, timer, timeSpent = [];

// Set cookie/video related data
var percentLimit = 3; // Percent the video counts as watched
var cookieName = 'ASTRAVIDS'
var videoCode = document.getElementById('player').getAttribute('video-code');

// Set watched from cookies
var watched, exists = false;
var watchedVideos = {};
if (checkCookie(cookieName)) {

    watchedVideos = JSON.parse(getCookie(cookieName))

    // console.log(watchedVideos);

    // This video is in progress
    if (watchedVideos.hasOwnProperty(videoCode)) exists = true;
    // This video is already watched
    if (exists && watchedVideos[videoCode] == 1) watched = true;
}

function onYouTubeIframeAPIReady() {

    player = new YT.Player( 'player', {
        events: { 'onStateChange': onPlayerStateChange }
    });
}

function onPlayerStateChange(event) {

    if (event.data === 1) { // Started playing

        if (!timeSpent.length) {

            for (var i = 0, l = parseInt(player.getDuration()); i < l; i++) timeSpent.push(false);
        }
        timer = setInterval(record,100);

    } else {

        clearInterval(timer);
    }
}

function record() {
    timeSpent[ parseInt(player.getCurrentTime()) ] = true;
    calculatePercentage();
}

function calculatePercentage() {

    var percent = 0;
    for (var i = 0, l = timeSpent.length; i < l; i++) {

        if (timeSpent[i]) percent++;
    }

    percent = Math.round(percent / timeSpent.length * 100);

    if (percent > 0 && !exists) {

        // Save in progress video state in cookie
        watchedVideos[videoCode] = 0;

        console.log(watchedVideos);

        // Update/override cookie
        setCookie(cookieName, JSON.stringify(watchedVideos));

        // Flag in progress/exists state
        exists = true;
    }

    if (percent > percentLimit && !watched) {
        
        // Save watched video state in cookie
        watchedVideos[videoCode] = 1;

        console.log(watchedVideos);

        // Update/override cookie
        setCookie(cookieName, JSON.stringify(watchedVideos));

        // Flag watched state
        watched = true;
    }
}

// Start YouTube API
onYouTubeIframeAPIReady();

});







// COOKIES RELATED FUNCTIONS
function setCookie(cname, cvalue)  {
    var expiry = new Date(2147483647 * 1000).toUTCString(); // "Infinite"
    var expires = "expires=" + expiry;
    document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
}

function checkCookie(cname) {
    var cookie = getCookie(cname);
    return (cookie != "") ? cookie : false;
}








// YouTube iFrame API
if (!window['YT']) {var YT = {loading: 0,loaded: 0};}if (!window['YTConfig']) {var YTConfig = {'host': 'http://www.youtube.com'};}if (!YT.loading) {YT.loading = 1;(function(){var l = [];YT.ready = function(f) {if (YT.loaded) {f();} else {l.push(f);}};window.onYTReady = function() {YT.loaded = 1;for (var i = 0; i < l.length; i++) {try {l[i]();} catch (e) {}}};YT.setConfig = function(c) {for (var k in c) {if (c.hasOwnProperty(k)) {YTConfig[k] = c[k];}}};var a = document.createElement('script');a.type = 'text/javascript';a.id = 'www-widgetapi-script';a.src = 'https://s.ytimg.com/yts/jsbin/www-widgetapi-vflBgvvHy/www-widgetapi.js';a.async = true;var c = document.currentScript;if (c) {var n = c.nonce || c.getAttribute('nonce');if (n) {a.setAttribute('nonce', n);}}var b = document.getElementsByTagName('script')[0];b.parentNode.insertBefore(a, b);})();}

</script>