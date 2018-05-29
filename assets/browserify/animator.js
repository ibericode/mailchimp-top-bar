'use strict';

var duration = 800;
var easeOutQuint = function (t) { return 1+(--t)*t*t*t*t };

function css(element, styles) {
    for(var property in styles) {
        element.style[property] = styles[property];
    }
}

function initObjectProperties(properties, value) {
    var newObject = {};
    for(var i=0; i<properties.length; i++) {
        newObject[properties[i]] = value;
    }
    return newObject;
}

function copyObjectProperties(properties, object) {
    var newObject = {}
    for(var i=0; i<properties.length; i++) {
        newObject[properties[i]] = object[properties[i]];
    }
    return newObject;
}

/**
 * Checks if the given element is currently being animated.
 *
 * @param element
 * @returns {boolean}
 */
function animated(element) {
    return !! element.getAttribute('data-animated');
}

/**
 * Toggles the element using the given animation.
 *
 * @param element
 * @param animation Either "fade" or "slide"
 */
function toggle(element, animation) {
    var nowVisible = element.style.display != 'none' || element.offsetLeft > 0;

    // create clone for reference
    var clone = element.cloneNode(true);
    var cleanup = function() {
        element.removeAttribute('data-animated');
        element.setAttribute('style', clone.getAttribute('style'));
        element.style.display = nowVisible ? 'none' : '';
    };

    // store attribute so everyone knows we're animating this element
    element.setAttribute('data-animated', "true");

    // toggle element visiblity right away if we're making something visible
    if( ! nowVisible ) {
        element.style.display = '';
    }

    var hiddenStyles, visibleStyles;

    // animate properties
    if( animation === 'slide' ) {
        hiddenStyles = initObjectProperties(["height", "borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"], 0);
        visibleStyles = {};

        if( ! nowVisible ) {
            var computedStyles = window.getComputedStyle(element);
            visibleStyles = copyObjectProperties(["height", "borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"], computedStyles);
            css(element, hiddenStyles);
        }

        // don't show a scrollbar during animation
        element.style.overflowY = 'hidden';
        animate(element, nowVisible ? hiddenStyles : visibleStyles, cleanup);
    } else {
        hiddenStyles = { opacity: 0 };
        visibleStyles = { opacity: 1 };
        if( ! nowVisible ) {
            css(element, hiddenStyles);
        }

        animate(element, nowVisible ? hiddenStyles : visibleStyles, cleanup);
    }
}

function animate(element, targetStyles, fn) {
    var startTime = performance.now();
    var styles = window.getComputedStyle(element);
    var diff = {};
    var startStyles = {};

    for(var property in targetStyles) {
        // calculate step size & current value
        var to = parseFloat(targetStyles[property]);
        var current = parseFloat(styles[property]);

        // is there something to do?
        if( current == to ) {
            continue;
        }

        startStyles[property] = current;
        diff[property] = to - current;
    }

    var tick = function(t) {
        var progress = Math.min(( t - startTime ) / duration, 1);

        for(var property in diff) {
            var suffix = property !== "opacity" ? "px" : "";
            element.style[property] = startStyles[property] + ( diff[property] * easeOutQuint(progress) ) + suffix;
        }

        if(progress >= 1) {
            if(fn) {
                fn();
            }

            return;
        } 
        
        window.requestAnimationFrame(tick);
    };

    window.requestAnimationFrame(tick);
}


module.exports = {
    'toggle': toggle,
    'animate': animate,
    'animated': animated
};
