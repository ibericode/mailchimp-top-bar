/**
 * Creates a cookie
 *
 * @param name
 * @param value
 * @param days
 */
function create (name, value, days) {
  const expires = days ? ';max-age=' + days * 24 * 60 * 60 : ''
  document.cookie = encodeURIComponent(name) + '=' + encodeURIComponent(value) + expires + ';path=/;SameSite=lax'
}

/**
 * Checks for existence of a cookie without checking its value
 *
 * @param name
 * @returns {boolean}
 */
function exists (name) {
  return (new RegExp(name + '=')).test(document.cookie)
}

/**
 * Erases a cookie
 *
 * @param name
 */
function erase (name) {
  create(name, '', -1)
}

module.exports = { exists, create, erase }
