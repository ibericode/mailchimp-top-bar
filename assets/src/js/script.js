const cookies = require('./cookies.js')
const Loader = require('./loader.js')
const COOKIE_NAME = 'mctb_bar_hidden'

/**
 * @param {function} fn callback
 * @parma {int} delay in ms
 */
function debounce (fn, delay) {
  let timeout
  return function () {
    clearTimeout(timeout)
    timeout = setTimeout(fn, delay)
  }
}

function Bar () {
  const wrapperEl = document.getElementById('mailchimp-top-bar')
  const config = window.mctb
  const barEl = wrapperEl.querySelector('.mctb-bar')
  const iconEl = document.createElement('span')
  const formEl = barEl.querySelector('form')
  let barHeight
  let barPadding
  let responseEl = wrapperEl.querySelector('.mctb-response')
  let visible = !cookies.exists(COOKIE_NAME)
  let originalBodyPadding = 0
  let bodyPadding = 0
  const isBottomBar = (config.position === 'bottom')
  const state = config.state

  // remove "no_js" field (which is used to detect bots and prevent spam)
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
  iconEl.innerHTML = visible ? config.icons.hide : config.icons.show
  iconEl.addEventListener('click', toggle)

  // count input fields (3 because of hidden input honeypot)
  if (barEl.querySelectorAll('input:not([type="hidden"])').length > 3) {
    wrapperEl.className += ' multiple-input-fields'
  }

  window.requestAnimationFrame(calculateDimensions)
  window.addEventListener('load', calculateDimensions)

  // fade response 4 seconds after showing bar
  if (responseEl) {
    window.setTimeout(fadeResponse, 4000)
  }

  window.addEventListener('resize', debounce(calculateDimensions, 100))

  function submitForm (evt) {
    evt.preventDefault()

    const loader = new Loader(formEl)
    loader.start()

    const data = new FormData(formEl)
    const request = new XMLHttpRequest()
    request.onload = function () {
      // remove loading indicator
      loader.stop()

      // parse json response
      let response
      if (this.status >= 200 && this.status < 400) {
        try {
          response = JSON.parse(this.responseText)
        } catch (error) {
          console.log('MailChimp Top Bar: failed to parse AJAX response.\n\nError: "' + error + '"')
          return
        }

        state.success = !!response.success
        state.submitted = true

        // maybe redirect to url from settings
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

  function iconFitsInsideBar () {
    // would the close icon fit inside the bar?
    let elementsWidth = 0
    for (let i = 0; i < barEl.firstElementChild.children.length; i++) {
      elementsWidth += barEl.firstElementChild.children[i].clientWidth
    }

    return (elementsWidth + iconEl.clientWidth + 200) < barEl.clientWidth
  }

  function calculateDimensions () {
    // make sure bar is visible
    if (!visible) {
      barEl.style.visibility = 'hidden'
    }
    barEl.style.display = ''
    barEl.style.height = ''
    barEl.style.paddingTop = ''
    barEl.style.paddingBottom = ''

    // measure bar padding and height
    // we use this as our animation target values
    const styles = window.getComputedStyle(barEl)
    barHeight = styles.height
    barPadding = styles.paddingTop

    // calculate & set new body padding if bar is currently visible
    bodyPadding = (originalBodyPadding + barEl.clientHeight) + 'px'
    if (visible) {
      document.body.style[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding
    }

    wrapperEl.className = wrapperEl.className.replace('mctb-icon-inside-bar', '')
    if (iconFitsInsideBar()) {
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
    barEl.style.visibility = ''
    barEl.style.height = visible ? barHeight : 0
    barEl.style.paddingTop = visible ? barPadding : 0
    barEl.style.paddingBottom = visible ? barPadding : 0
  }

  /**
   * @param {Event} evt
   */
  function removeTransition (evt) {
    evt.target.style.transition = ''
    evt.target.removeEventListener('transitionend', removeTransition)
  }
  /**
   * @param {HTMLElement} el
   * @param {object} styles
   */
  function animate (el, styles) {
    el.style.transition = 'all 0.6s ease'
    el.addEventListener('transitionend', removeTransition)
    window.requestAnimationFrame(() => css(el, styles))
  }

  /**
   * @param {HTMLElement} el
   * @param {object} styles
   */
  function css (el, styles) {
    for (const prop in styles) {
      el.style[prop] = styles[prop]
    }
  }

  /**
   * Show the bar
   * @param {boolean} manual
   * @returns {boolean}
   */
  function show (manual) {
    if (visible) {
      return false
    }

    const barStyles = {
      height: barHeight,
      paddingTop: barPadding,
      paddingBottom: barPadding
    }
    const bodyStyles = {}
    bodyStyles[isBottomBar ? 'paddingBottom' : 'paddingTop'] = bodyPadding
    if (manual) {
      animate(barEl, barStyles)
      animate(document.body, bodyStyles)
      cookies.erase(COOKIE_NAME)
    } else {
      css(barEl, barStyles)
      css(document.body, bodyStyles)
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

    const barStyles = {
      height: 0,
      paddingBottom: 0,
      paddingTop: 0
    }
    const bodyStyles = {}
    bodyStyles[isBottomBar ? 'paddingBottom' : 'paddingTop'] = originalBodyPadding + 'px'
    if (manual) {
      animate(barEl, barStyles)
      animate(document.body, bodyStyles)
      cookies.create(COOKIE_NAME, state.success ? 'used' : 'hidden', config.cookieLength)
    } else {
      css(barEl, barStyles)
      css(document.body, bodyStyles)
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
    return visible ? hide(true) : show(true)
  }

  // Return values
  return {
    element: wrapperEl,
    toggle,
    show,
    hide
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const linkEl = document.createElement('link')
  linkEl.href = window.mctb.stylesheet
  linkEl.rel = 'stylesheet'
  document.head.appendChild(linkEl)
  window.MailChimpTopBar = new Bar()
})
