const cookies = require('./cookies.js')
const animator = require('./animator.js')
const Loader = require('./loader.js')
const COOKIE_NAME = 'mctb_bar_hidden'

function throttle (fn, threshold, scope) {
  threshold || (threshold = 600)
  let last
  let deferTimer
  return function () {
    const context = scope || this
    const now = +new Date()
    const args = arguments
    if (last && now < last + threshold) {
      // hold on to it
      clearTimeout(deferTimer)
      deferTimer = setTimeout(function () {
        last = now
        fn.apply(context, args)
      }, threshold)
    } else {
      last = now
      fn.apply(context, args)
    }
  }
}

function Bar (wrapperEl, config) {
  const barEl = wrapperEl.querySelector('.mctb-bar')
  const iconEl = document.createElement('span')
  const formEl = barEl.querySelector('form')
  let responseEl = wrapperEl.querySelector('.mctb-response')
  let visible = false
  let originalBodyPadding = 0
  let bodyPadding = 0
  const isBottomBar = (config.position === 'bottom')
  const state = config.state

  // Functions
  function init () {
    // remove "no_js" field
    const noJsField = barEl.querySelector('input[name="_mctb_no_js"]')
    noJsField.parentElement.removeChild(noJsField)

    formEl.addEventListener('submit', submitForm)

    // save original bodyPadding
    if (isBottomBar) {
      wrapperEl.insertBefore(iconEl, barEl)
      originalBodyPadding = (parseInt(document.body.style.paddingBottom) || 0)
    } else {
      wrapperEl.insertBefore(iconEl, barEl.nextElementSibling)
      originalBodyPadding = (parseInt(document.body.style.paddingTop) || 0)
    }

    // configure icon
    iconEl.className = 'mctb-close'
    iconEl.innerHTML = config.icons.show
    iconEl.addEventListener('click', toggle)

    // count input fields (3 because of hidden input honeypot)
    if (barEl.querySelectorAll('input:not([type="hidden"])').length > 3) {
      wrapperEl.className += ' multiple-input-fields'
    }

    // calculate initial dimensions
    calculateDimensions()

    // on dom repaint, bar height changes. re-calculate in next repaint.
    window.requestAnimationFrame(calculateDimensions)

    // Show the bar straight away?
    const cookieValue = cookies.read(COOKIE_NAME)
    if (cookieValue === null) {
      show()
    }

    // fade response 4 seconds after showing bar
    if (responseEl) {
      window.setTimeout(fadeResponse, 4000)
    }

    window.addEventListener('resize', throttle(calculateDimensions))
  }

  function submitForm (evt) {
    const loader = new Loader(formEl)
    const data = new FormData(formEl)
    let request = new XMLHttpRequest()
    request.onreadystatechange = function () {
      let response

      // are we done?
      if (this.readyState !== 4) {
        return
      }

      loader.stop()

      if (this.status >= 200 && this.status < 400) {
        try {
          response = JSON.parse(this.responseText)
        } catch (error) {
          console.log('MailChimp Top Bar: failed to parse AJAX response.\n\nError: "' + error + '"')
          return
        }

        state.success = !!response.success
        state.submitted = true

        if (response.success && response.redirect_url) {
          window.location.href = response.redirect_url
          return
        }

        showResponseMessage(response.message)

        // clear form
        if (state.success) {
          formEl.reset()
        }
      } else {
        // Server error :(
        console.log(this.responseText)
      }
    }
    request.open('POST', window.location.href, true)
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    request.send(data)
    request = null

    loader.start()
    evt.preventDefault()
  }

  function showResponseMessage (msg) {
    if (responseEl) {
      responseEl.parentNode.removeChild(responseEl)
    }

    responseEl = document.createElement('div')
    responseEl.className = 'mctb-response'

    const labelEl = document.createElement('label')
    labelEl.className = 'mctb-response-label'
    labelEl.innerText = msg
    responseEl.appendChild(labelEl)
    formEl.parentNode.insertBefore(responseEl, formEl.nextElementSibling)

    calculateDimensions()
    window.setTimeout(fadeResponse, 4000)
  }

  function calculateDimensions () {
    // make sure bar is visible
    const origBarDisplay = barEl.style.display
    if (origBarDisplay !== 'block') {
      barEl.style.visibility = 'hidden'
    }
    barEl.style.display = 'block'

    // calculate & set new body padding if bar is currently visible
    bodyPadding = (originalBodyPadding + barEl.clientHeight) + 'px'
    if (visible) {
      document.body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding
    }

    // would the close icon fit inside the bar?
    let elementsWidth = 0
    for (let i = 0; i < barEl.firstElementChild.children.length; i++) {
      elementsWidth += barEl.firstElementChild.children[i].clientWidth
    }

    wrapperEl.className = wrapperEl.className.replace('mctb-icon-inside-bar', '')
    if (elementsWidth + iconEl.clientWidth + 200 < barEl.clientWidth) {
      wrapperEl.className += ' mctb-icon-inside-bar'

      // since icon is now absolutely positioned, we need to set a min height
      if (isBottomBar) {
        wrapperEl.style.minHeight = iconEl.clientHeight + 'px'
      }
    }

    // fix response height
    if (responseEl) {
      responseEl.style.height = barEl.clientHeight + 'px'
      responseEl.style.lineHeight = barEl.clientHeight + 'px'
    }

    // reset bar again, we're done measuring
    barEl.style.display = origBarDisplay
    barEl.style.visibility = ''
  }

  /**
     * Show the bar
     *
     * @returns {boolean}
     */
  function show (manual) {
    if (visible) {
      return false
    }

    if (manual) {
      cookies.erase(COOKIE_NAME)
      animator.toggle(barEl, 'slide')

      // animate body padding
      const styles = {}
      styles[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding
      animator.animate(document.body, styles)
    } else {
      // Add bar height to <body> padding
      barEl.style.display = 'block'
      document.body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding
    }

    iconEl.innerHTML = config.icons.hide
    visible = true

    return true
  }

  /**
     * Hide the bar
     *
     * @returns {boolean}
     */
  function hide (manual) {
    if (!visible) {
      return false
    }

    if (manual) {
      cookies.create(COOKIE_NAME, state.success ? 'used' : 'hidden', config.cookieLength)
      animator.toggle(barEl, 'slide')

      // animate body padding
      const styles = {}
      styles[isBottomBar ? 'paddingBottom' : 'paddingTop'] = originalBodyPadding
      animator.animate(document.body, styles)
    } else {
      barEl.style.display = 'none'
      document.body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = originalBodyPadding + 'px'
    }

    visible = false
    iconEl.innerHTML = config.icons.show

    return true
  }

  /**
     * Fade out the response message
     */
  function fadeResponse () {
    if (!responseEl) {
      return
    }

    responseEl.style.opacity = '0'
    window.setTimeout(() => {
      // remove response element so form is usable again
      responseEl.parentElement.removeChild(responseEl)

      // hide bar if sign-up was successful
      if (state.submitted && state.success) {
        hide(true)
      }
    }, 1000)
  }

  /**
     * Toggle visibility of the bar
     *
     * @returns {boolean}
     */
  function toggle () {
    if (animator.animated(barEl)) {
      return false
    }

    return visible ? hide(true) : show(true)
  }

  // Code to run upon object instantiation
  init()

  // Return values
  return {
    element: wrapperEl,
    toggle: toggle,
    show: show,
    hide: hide
  }
}

module.exports = Bar
