'use strict';

var Bar = require('./bar.js');

// Init bar
ready(function() {
    window.MailChimpTopBar = new Bar( document.getElementById('mailchimp-top-bar'), window.mctb );
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


