/**
 * Creates a cookie
 *
 * @param name
 * @param value
 * @param days
 */
function create (name, value, days) {
  let expires

  if (days) {
    const date = new Date()
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000))
    expires = '; expires=' + date.toGMTString()
  } else {
    expires = ''
  }
  document.cookie = encodeURIComponent(name) + '=' + encodeURIComponent(value) + expires + '; path=/'
}

/**
 * Reads a cookie
 *
 * @param name
 * @returns {*}
 */
function read (name) {
  const nameEQ = encodeURIComponent(name) + '='
  const ca = document.cookie.split(';')
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i]
    while (c.charAt(0) === ' ') c = c.substring(1, c.length)
    if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length))
  }
  return null
}

/**
 * Erases a cookie
 *
 * @param name
 */
function erase (name) {
  create(name, '', -1)
}

module.exports = { read, create, erase }
