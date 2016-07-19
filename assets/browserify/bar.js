'use strict';

var body = document.body;
var cookies = require('./cookies.js');
var animator = require('./animator.js');

function Bar( wrapperEl, config ) {

    // Vars & State
    var barEl = wrapperEl.querySelector('.mctb-bar');
    var iconEl = document.createElement('span');
    var responseEl = wrapperEl.querySelector('.mctb-response');
    var visible = false,
        originalBodyPadding = 0,
        barHeight = 0,
        bodyPadding = 0,
        isBottomBar = ( config.position === 'bottom' );


    // Functions

    function init() {

        // remove "no_js" field
        var noJsField = barEl.querySelector('input[name="_mctb_no_js"]');
        noJsField.parentElement.removeChild(noJsField);

        // calculate real bar height
        var origBarPosition = barEl.style.position;
        barEl.style.display = 'block';
        barEl.style.position = 'relative';
        barHeight = barEl.clientHeight;

        // save original bodyPadding
        if( isBottomBar ) {
            wrapperEl.insertBefore( iconEl, barEl );
            originalBodyPadding = ( parseInt( body.style.paddingBottom )  || 0 );
        } else {
            wrapperEl.insertBefore( iconEl, barEl.nextElementSibling );
            originalBodyPadding = ( parseInt( body.style.paddingTop )  || 0 );
        }

        // get real bar height (if it were shown)
        bodyPadding = ( originalBodyPadding + barHeight ) + "px";

        // fade response 4 seconds after showing bar
        window.setTimeout(fadeResponse, 4000);

        // fix response height
        if( responseEl ) {
            responseEl.style.lineHeight = barHeight + "px";
        }

        // Configure icon
        iconEl.setAttribute('class', 'mctb-close');
        iconEl.innerHTML = config.icons.show;
        iconEl.addEventListener('click', toggle);

        // would the close icon fit inside the bar?
        var elementsWidth = 0;
        for( var i=0; i<barEl.firstElementChild.children.length; i++ ) {
            elementsWidth+= barEl.firstElementChild.children[i].clientWidth;
        }
        if( elementsWidth + iconEl.clientWidth + 200 < barEl.clientWidth ) {
            wrapperEl.className += ' mctb-icon-inside-bar';

            // since icon is now absolutely positioned, we need to set a min height
            if( isBottomBar ) {
                wrapperEl.style.minHeight = iconEl.clientHeight + "px";
            }
        }

        // hide bar again, we're done measuring
        barEl.style.display = 'none';
        barEl.style.position = origBarPosition;

        // Show the bar straight away?
        if( cookies.read( "mctb_bar_hidden" ) != 1 ) {
            show()
        }
    }

    /**
     * Show the bar
     *
     * @returns {boolean}
     */
    function show( manual ) {

        if( visible ) {
            return false;
        }

        if( manual ) {
            cookies.erase( 'mctb_bar_hidden' );
            animator.toggle(barEl, "slide");

            // animate body padding
            var styles = {};
            styles[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding;
            animator.animate(body, styles);
        } else {
            // Add bar height to <body> padding
            barEl.style.display = 'block';
            body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding;
        }

        iconEl.innerHTML = config.icons.hide;
        visible = true;

        return true;
    }

    /**
     * Hide the bar
     *
     * @returns {boolean}
     */
    function hide(manual) {
        if( ! visible ) {
            return false;
        }

        if( manual ) {
            cookies.create( "mctb_bar_hidden", 1, config.cookieLength );
            animator.toggle(barEl, "slide");

            // animate body padding
            var styles = {};
            styles[isBottomBar ? 'paddingBottom' : 'paddingTop'] = originalBodyPadding;
            animator.animate(body, styles);
        } else {
            barEl.style.display = 'none';
            document.body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = originalBodyPadding + "px";
        }

        visible = false;
        iconEl.innerHTML = config.icons.show;

        return true;
    }

    /**
     * Fade out the response message
     */
    function fadeResponse() {
        if( responseEl ) {

            animator.toggle(responseEl, "fade");

            // auto-dismiss bar if we're good!
            if( config.is_submitted && config.is_success ) {
                window.setTimeout( function() { hide( true );}, 1000 );
            }
        }
    }

    /**
     * Toggle visibility of the bar
     *
     * @returns {boolean}
     */
    function toggle() {
        if(animator.animated(barEl)) {
            return false;
        }

        return visible ? hide(true) : show(true);
    }

    // Code to run upon object instantiation
    init();

    // Return values
    return {
        element: wrapperEl,
        toggle: toggle,
        show: show,
        hide: hide
    }

}

module.exports = Bar;