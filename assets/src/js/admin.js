const $ = window.jQuery
const $context = $(document.getElementById('mc4wp-admin'))
const elSelectList = document.getElementById('select-mailchimp-list');
const msgRequiresFields = document.getElementById('message-list-requires-fields')
const ajaxurl = window.ajaxurl

/*
 * Functions
 */
function maybeShowRequiredFieldsNotice () {
  msgRequiresFields.style.display = 'none'
  const listId = elSelectList.value
  $.get(ajaxurl + '?action=mc4wp_get_list_details&ids=' + listId)
    .then(lists => {
      // iterate over selected lists
      for (let i = 0; i < lists.length; i++) {
        const list = lists[i]

        // iterate over list fields
        for (let j = 0; j < list.merge_fields.length; j++) {
          const field = list.merge_fields[j]

          // if field other than EMAIL is required, show notice and stop loop
          if (field.tag !== 'EMAIL' && field.required) {
            msgRequiresFields.style.display = ''
            return
          }
        }
      }
    })
}

// init colorpickers
$context.find('.mc4wp-color').wpColorPicker()

// if a list changes, check which fields are required
elSelectList.addEventListener('change', maybeShowRequiredFieldsNotice);

// check right away
maybeShowRequiredFieldsNotice()
