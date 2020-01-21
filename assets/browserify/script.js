const Bar = require('./bar.js')

document.addEventListener('DOMContentLoaded', () => {
  const element = document.getElementById('mailchimp-top-bar')
  window.MailChimpTopBar = new Bar(element, window.mctb)
})
