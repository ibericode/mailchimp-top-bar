(function () { var require = undefined; var module = undefined; var exports = undefined; var define = undefined;(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
'use strict';

var duration = 600;

var easeOutQuad = function easeOutQuad(t) {
  return t * (2 - t);
};

function css(element, styles) {
  for (var property in styles) {
    if (!styles.hasOwnProperty(property)) {
      continue;
    }

    element.style[property] = styles[property];
  }
}

function initObjectProperties(properties, value) {
  var newObject = {};

  for (var i = 0; i < properties.length; i++) {
    newObject[properties[i]] = value;
  }

  return newObject;
}

function copyObjectProperties(properties, object) {
  var newObject = {};

  for (var i = 0; i < properties.length; i++) {
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
  return !!element.getAttribute('data-animated');
}
/**
 * Toggles the element using the given animation.
 *
 * @param element
 * @param animation Either "fade" or "slide"
 */


function toggle(element, animation) {
  var nowVisible = element.style.display !== 'none' || element.offsetLeft > 0; // create clone for reference

  var clone = element.cloneNode(true);

  var cleanup = function cleanup() {
    element.removeAttribute('data-animated');
    element.setAttribute('style', clone.getAttribute('style'));
    element.style.display = nowVisible ? 'none' : '';
  }; // store attribute so everyone knows we're animating this element


  element.setAttribute('data-animated', 'true'); // toggle element visiblity right away if we're making something visible

  if (!nowVisible) {
    element.style.display = '';
  }

  var hiddenStyles, visibleStyles; // animate properties

  if (animation === 'slide') {
    hiddenStyles = initObjectProperties(['height', 'borderTopWidth', 'borderBottomWidth', 'paddingTop', 'paddingBottom'], 0);
    visibleStyles = {};

    if (!nowVisible) {
      var computedStyles = window.getComputedStyle(element);
      visibleStyles = copyObjectProperties(['height', 'borderTopWidth', 'borderBottomWidth', 'paddingTop', 'paddingBottom'], computedStyles);
      css(element, hiddenStyles);
    } // don't show a scrollbar during animation


    element.style.overflowY = 'hidden';
    animate(element, nowVisible ? hiddenStyles : visibleStyles, cleanup);
  } else {
    hiddenStyles = {
      opacity: 0
    };
    visibleStyles = {
      opacity: 1
    };

    if (!nowVisible) {
      css(element, hiddenStyles);
    }

    animate(element, nowVisible ? hiddenStyles : visibleStyles, cleanup);
  }
}

function animate(element, targetStyles, fn) {
  var startTime = null;
  var styles = window.getComputedStyle(element);
  var diff = {};
  var startStyles = {};

  for (var property in targetStyles) {
    if (!targetStyles.hasOwnProperty(property)) {
      continue;
    } // calculate step size & current value


    var to = parseFloat(targetStyles[property]);
    var current = parseFloat(styles[property]); // is there something to do?

    if (current === to) {
      continue;
    }

    startStyles[property] = current;
    diff[property] = to - current;
  }

  var tick = function tick(timestamp) {
    if (!startTime) startTime = timestamp;
    var progress = Math.min((timestamp - startTime) / duration, 1.00);

    for (var _property in diff) {
      if (!diff.hasOwnProperty(_property)) {
        continue;
      }

      var suffix = _property !== 'opacity' ? 'px' : '';
      element.style[_property] = startStyles[_property] + diff[_property] * easeOutQuad(progress) + suffix;
    }

    if (progress < 1.00) {
      return window.requestAnimationFrame(tick);
    } // animation finished!


    if (fn) {
      fn();
    }
  };

  window.requestAnimationFrame(tick);
}

module.exports = {
  toggle: toggle,
  animate: animate,
  animated: animated
};

},{}],2:[function(require,module,exports){
"use strict";

var cookies = require('./cookies.js');

var animator = require('./animator.js');

var Loader = require('./loader.js');

var COOKIE_NAME = 'mctb_bar_hidden';

function throttle(fn, threshold, scope) {
  threshold || (threshold = 600);
  var last;
  var deferTimer;
  return function () {
    var context = scope || this;
    var now = +new Date();
    var args = arguments;

    if (last && now < last + threshold) {
      // hold on to it
      clearTimeout(deferTimer);
      deferTimer = setTimeout(function () {
        last = now;
        fn.apply(context, args);
      }, threshold);
    } else {
      last = now;
      fn.apply(context, args);
    }
  };
}

function Bar(wrapperEl, config) {
  var barEl = wrapperEl.querySelector('.mctb-bar');
  var iconEl = document.createElement('span');
  var formEl = barEl.querySelector('form');
  var responseEl = wrapperEl.querySelector('.mctb-response');
  var visible = false;
  var originalBodyPadding = 0;
  var bodyPadding = 0;
  var isBottomBar = config.position === 'bottom';
  var state = config.state; // Functions

  function init() {
    // remove "no_js" field
    var noJsField = barEl.querySelector('input[name="_mctb_no_js"]');
    noJsField.parentElement.removeChild(noJsField);
    formEl.addEventListener('submit', submitForm); // save original bodyPadding

    if (isBottomBar) {
      wrapperEl.insertBefore(iconEl, barEl);
      originalBodyPadding = parseInt(document.body.style.paddingBottom) || 0;
    } else {
      wrapperEl.insertBefore(iconEl, barEl.nextElementSibling);
      originalBodyPadding = parseInt(document.body.style.paddingTop) || 0;
    } // configure icon


    iconEl.className = 'mctb-close';
    iconEl.innerHTML = config.icons.show;
    iconEl.addEventListener('click', toggle); // count input fields (3 because of hidden input honeypot)

    if (barEl.querySelectorAll('input:not([type="hidden"])').length > 3) {
      wrapperEl.className += ' multiple-input-fields';
    } // calculate initial dimensions


    calculateDimensions(); // on dom repaint, bar height changes. re-calculate in next repaint.

    window.requestAnimationFrame(calculateDimensions); // Show the bar straight away?

    var cookieValue = cookies.read(COOKIE_NAME);

    if (cookieValue === null) {
      show();
    } // fade response 4 seconds after showing bar


    if (responseEl) {
      window.setTimeout(fadeResponse, 4000);
    }

    window.addEventListener('resize', throttle(calculateDimensions));
  }

  function submitForm(evt) {
    var loader = new Loader(formEl);
    var data = new FormData(formEl);
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
      var response; // are we done?

      if (this.readyState !== 4) {
        return;
      }

      loader.stop();

      if (this.status >= 200 && this.status < 400) {
        try {
          response = JSON.parse(this.responseText);
        } catch (error) {
          console.log('MailChimp Top Bar: failed to parse AJAX response.\n\nError: "' + error + '"');
          return;
        }

        state.success = !!response.success;
        state.submitted = true;

        if (response.success && response.redirect_url) {
          window.location.href = response.redirect_url;
          return;
        }

        showResponseMessage(response.message); // clear form

        if (state.success) {
          formEl.reset();
        }
      } else {
        // Server error :(
        console.log(this.responseText);
      }
    };

    request.open('POST', window.location.href, true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.send(data);
    request = null;
    loader.start();
    evt.preventDefault();
  }

  function showResponseMessage(msg) {
    if (responseEl) {
      responseEl.parentNode.removeChild(responseEl);
    }

    responseEl = document.createElement('div');
    responseEl.className = 'mctb-response';
    var labelEl = document.createElement('label');
    labelEl.className = 'mctb-response-label';
    labelEl.innerText = msg;
    responseEl.appendChild(labelEl);
    formEl.parentNode.insertBefore(responseEl, formEl.nextElementSibling);
    calculateDimensions();
    window.setTimeout(fadeResponse, 4000);
  }

  function calculateDimensions() {
    // make sure bar is visible
    var origBarDisplay = barEl.style.display;

    if (origBarDisplay !== 'block') {
      barEl.style.visibility = 'hidden';
    }

    barEl.style.display = 'block'; // calculate & set new body padding if bar is currently visible

    bodyPadding = originalBodyPadding + barEl.clientHeight + 'px';

    if (visible) {
      document.body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding;
    } // would the close icon fit inside the bar?


    var elementsWidth = 0;

    for (var i = 0; i < barEl.firstElementChild.children.length; i++) {
      elementsWidth += barEl.firstElementChild.children[i].clientWidth;
    }

    wrapperEl.className = wrapperEl.className.replace('mctb-icon-inside-bar', '');

    if (elementsWidth + iconEl.clientWidth + 200 < barEl.clientWidth) {
      wrapperEl.className += ' mctb-icon-inside-bar'; // since icon is now absolutely positioned, we need to set a min height

      if (isBottomBar) {
        wrapperEl.style.minHeight = iconEl.clientHeight + 'px';
      }
    } // fix response height


    if (responseEl) {
      responseEl.style.height = barEl.clientHeight + 'px';
      responseEl.style.lineHeight = barEl.clientHeight + 'px';
    } // reset bar again, we're done measuring


    barEl.style.display = origBarDisplay;
    barEl.style.visibility = '';
  }
  /**
     * Show the bar
     *
     * @returns {boolean}
     */


  function show(manual) {
    if (visible) {
      return false;
    }

    if (manual) {
      cookies.erase(COOKIE_NAME);
      animator.toggle(barEl, 'slide'); // animate body padding

      var styles = {};
      styles[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding;
      animator.animate(document.body, styles);
    } else {
      // Add bar height to <body> padding
      barEl.style.display = 'block';
      document.body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding;
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
    if (!visible) {
      return false;
    }

    if (manual) {
      cookies.create(COOKIE_NAME, state.success ? 'used' : 'hidden', config.cookieLength);
      animator.toggle(barEl, 'slide'); // animate body padding

      var styles = {};
      styles[isBottomBar ? 'paddingBottom' : 'paddingTop'] = originalBodyPadding;
      animator.animate(document.body, styles);
    } else {
      barEl.style.display = 'none';
      document.body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = originalBodyPadding + 'px';
    }

    visible = false;
    iconEl.innerHTML = config.icons.show;
    return true;
  }
  /**
     * Fade out the response message
     */


  function fadeResponse() {
    if (!responseEl) {
      return;
    }

    responseEl.style.opacity = '0';
    window.setTimeout(function () {
      // remove response element so form is usable again
      responseEl.parentElement.removeChild(responseEl); // hide bar if sign-up was successful

      if (state.submitted && state.success) {
        hide(true);
      }
    }, 1000);
  }
  /**
     * Toggle visibility of the bar
     *
     * @returns {boolean}
     */


  function toggle() {
    if (animator.animated(barEl)) {
      return false;
    }

    return visible ? hide(true) : show(true);
  } // Code to run upon object instantiation


  init(); // Return values

  return {
    element: wrapperEl,
    toggle: toggle,
    show: show,
    hide: hide
  };
}

module.exports = Bar;

},{"./animator.js":1,"./cookies.js":3,"./loader.js":4}],3:[function(require,module,exports){
"use strict";

/**
 * Creates a cookie
 *
 * @param name
 * @param value
 * @param days
 */
function create(name, value, days) {
  var expires;

  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = '; expires=' + date.toGMTString();
  } else {
    expires = '';
  }

  document.cookie = encodeURIComponent(name) + '=' + encodeURIComponent(value) + expires + '; path=/';
}
/**
 * Reads a cookie
 *
 * @param name
 * @returns {*}
 */


function read(name) {
  var nameEQ = encodeURIComponent(name) + '=';
  var ca = document.cookie.split(';');

  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];

    while (c.charAt(0) === ' ') {
      c = c.substring(1, c.length);
    }

    if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
  }

  return null;
}
/**
 * Erases a cookie
 *
 * @param name
 */


function erase(name) {
  create(name, '', -1);
}

module.exports = {
  read: read,
  create: create,
  erase: erase
};

},{}],4:[function(require,module,exports){
"use strict";

function getButtonText(button) {
  return button.innerHTML ? button.innerHTML : button.value;
}

function setButtonText(button, text) {
  button.innerHTML ? button.innerHTML = text : button.value = text;
}

function Loader(formElement) {
  this.form = formElement;
  this.button = formElement.querySelector('input[type="submit"], button[type="submit"]');
  this.loadingInterval = 0;
  this["char"] = "\xB7";

  if (this.button) {
    this.originalButton = this.button.cloneNode(true);
  }
}

Loader.prototype.setCharacter = function (c) {
  this["char"] = c;
};

Loader.prototype.start = function () {
  if (this.button) {
    // loading text
    var loadingText = this.button.getAttribute('data-loading-text');

    if (loadingText) {
      setButtonText(this.button, loadingText);
      return;
    } // Show AJAX loader


    var styles = window.getComputedStyle(this.button);
    this.button.style.width = styles.width;
    setButtonText(this.button, this["char"]);
    this.loadingInterval = window.setInterval(this.tick.bind(this), 500);
  } else {
    this.form.style.opacity = '0.5';
  }
};

Loader.prototype.tick = function () {
  // count chars, start over at 5
  var text = getButtonText(this.button);
  var loadingChar = this["char"];
  setButtonText(this.button, text.length >= 5 ? loadingChar : text + ' ' + loadingChar);
};

Loader.prototype.stop = function () {
  if (this.button) {
    this.button.style.width = this.originalButton.style.width;
    var text = getButtonText(this.originalButton);
    setButtonText(this.button, text);
    window.clearInterval(this.loadingInterval);
  } else {
    this.form.style.opacity = '';
  }
};

module.exports = Loader;

},{}],5:[function(require,module,exports){
"use strict";

var Bar = require('./bar.js');

document.addEventListener('DOMContentLoaded', function () {
  var element = document.getElementById('mailchimp-top-bar');
  window.MailChimpTopBar = new Bar(element, window.mctb);
});

},{"./bar.js":2}]},{},[5]);
; })();