/**
 * @param {HTMLElement} button
 */
function getButtonText (button) {
  return button.innerHTML ? button.innerHTML : button.value
}

/**
 * @param {HTMLElement} button
 * @param {string} text
 */
function setButtonText (button, text) {
  button.innerHTML ? button.innerHTML = text : button.value = text
}

/**
 * @param {HTMLFormElement} formElement
 */
function Loader (formElement) {
  this.form = formElement
  this.button = formElement.querySelector('input[type="submit"],button[type="submit"]')
  this.char = '\u00B7'

  if (this.button) {
    this.originalButton = this.button.cloneNode(true)
  }

  this.start()
}

/**
 * @param {string} c
 */
Loader.prototype.setCharacter = function (c) {
  this.char = c
}

/**
 * Start the loading indicator
 */
Loader.prototype.start = function () {
  if (this.button) {
    // loading text
    const loadingText = this.button.getAttribute('data-loading-text')
    if (loadingText) {
      setButtonText(this.button, loadingText)
      return
    }

    // Show AJAX loader
    const styles = window.getComputedStyle(this.button)
    this.button.style.width = styles.width
    setButtonText(this.button, this.char)
    this.loadingInterval = window.setInterval(this.tick.bind(this), 500)
  } else {
    this.form.style.opacity = '0.5'
  }
}

/**
 * Single step in the loading indicator's "animation"
 */
Loader.prototype.tick = function () {
  // count chars, start over at 5
  const text = getButtonText(this.button)
  const loadingChar = this.char
  setButtonText(this.button, text.length >= 5 ? loadingChar : text + ' ' + loadingChar)
}

/**
 * Stops the loading indicator
 */
Loader.prototype.stop = function () {
  if (this.button) {
    this.button.style.width = this.originalButton.style.width
    const text = getButtonText(this.originalButton)
    setButtonText(this.button, text)
    window.clearInterval(this.loadingInterval)
  } else {
    this.form.style.opacity = ''
  }
}

export default Loader
