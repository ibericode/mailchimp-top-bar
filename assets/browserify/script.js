'use strict';

var Bar = require('./bar.js');

// Init bar
ready(function() {
    var element = document.getElementById('mailchimp-top-bar');
    window.MailChimpTopBar = new Bar( element, window.mctb );
});

/**
 * DOMContentLoaded (IE8 compatible)
 *
 * @param fn
 */
function ready(fn) {
    if (document.readyState != 'loading'){
        fn();
    } else if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', fn);
    }
}


