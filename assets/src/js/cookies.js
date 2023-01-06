/**
 * Creates a cookie
 *
 * @param {string} name
 * @param {mixed} value
 * @param {int|undefined} days
 */
function create (name, value, days) {
  const expires = days ? ';max-age=' + days * 24 * 60 * 60 : ''
  document.cookie = encodeURIComponent(name) + '=' + encodeURIComponent(value) + expires + ';path=/;SameSite=lax'
}

/**
 * Checks for existence of a cookie without checking its value
 *
 * @param {string} name
 * @returns {boolean}
 */
function exists (name) {
  return (new RegExp(name + '=')).test(document.cookie)
}

/**
 * Erases a cookie
 *
 * @param {string} name
 */
function erase (name) {
  create(name, '', -1)
}

export default { exists, create, erase }
