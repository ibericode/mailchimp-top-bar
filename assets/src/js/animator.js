const duration = 600
const easeOutQuad = t => t * (2 - t)

function css (element, styles) {
  for (const property in styles) {
    if (!styles.hasOwnProperty(property)) {
      continue
    }

    element.style[property] = styles[property]
  }
}

function initObjectProperties (properties, value) {
  const newObject = {}
  for (let i = 0; i < properties.length; i++) {
    newObject[properties[i]] = value
  }
  return newObject
}

function copyObjectProperties (properties, object) {
  const newObject = {}
  for (let i = 0; i < properties.length; i++) {
    newObject[properties[i]] = object[properties[i]]
  }
  return newObject
}

/**
 * Checks if the given element is currently being animated.
 *
 * @param element
 * @returns {boolean}
 */
function animated (element) {
  return !!element.getAttribute('data-animated')
}

/**
 * Toggles the element using the given animation.
 *
 * @param element
 * @param animation Either "fade" or "slide"
 */
function toggle (element, animation) {
  const nowVisible = element.style.display !== 'none' || element.offsetLeft > 0

  // create clone for reference
  const clone = element.cloneNode(true)
  const cleanup = function () {
    element.removeAttribute('data-animated')
    element.setAttribute('style', clone.getAttribute('style'))
    element.style.display = nowVisible ? 'none' : ''
  }

  // store attribute so everyone knows we're animating this element
  element.setAttribute('data-animated', 'true')

  // toggle element visiblity right away if we're making something visible
  if (!nowVisible) {
    element.style.display = ''
  }

  let hiddenStyles, visibleStyles

  // animate properties
  if (animation === 'slide') {
    hiddenStyles = initObjectProperties(['height', 'borderTopWidth', 'borderBottomWidth', 'paddingTop', 'paddingBottom'], 0)
    visibleStyles = {}

    if (!nowVisible) {
      const computedStyles = window.getComputedStyle(element)
      visibleStyles = copyObjectProperties(['height', 'borderTopWidth', 'borderBottomWidth', 'paddingTop', 'paddingBottom'], computedStyles)
      css(element, hiddenStyles)
    }

    // don't show a scrollbar during animation
    element.style.overflowY = 'hidden'
    animate(element, nowVisible ? hiddenStyles : visibleStyles, cleanup)
  } else {
    hiddenStyles = { opacity: 0 }
    visibleStyles = { opacity: 1 }
    if (!nowVisible) {
      css(element, hiddenStyles)
    }

    animate(element, nowVisible ? hiddenStyles : visibleStyles, cleanup)
  }
}

function animate (element, targetStyles, fn) {
  let startTime = null
  const styles = window.getComputedStyle(element)
  const diff = {}
  const startStyles = {}

  for (const property in targetStyles) {
    if (!targetStyles.hasOwnProperty(property)) {
      continue
    }

    // calculate step size & current value
    const to = parseFloat(targetStyles[property])
    const current = parseFloat(styles[property])

    // is there something to do?
    if (current === to) {
      continue
    }

    startStyles[property] = current
    diff[property] = to - current
  }

  const tick = function (timestamp) {
    if (!startTime) startTime = timestamp
    const progress = Math.min((timestamp - startTime) / duration, 1.00)

    for (const property in diff) {
      if (!diff.hasOwnProperty(property)) {
        continue
      }

      const suffix = property !== 'opacity' ? 'px' : ''
      element.style[property] = startStyles[property] + (diff[property] * easeOutQuad(progress)) + suffix
    }

    if (progress < 1.00) {
      return window.requestAnimationFrame(tick)
    }

    // animation finished!
    if (fn) {
      fn()
    }
  }

  window.requestAnimationFrame(tick)
}

module.exports = {
  toggle: toggle,
  animate: animate,
  animated: animated
}
