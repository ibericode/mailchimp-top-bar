const elSelectList = document.getElementById('select-mailchimp-list')
const msgRequiresFields = document.getElementById('message-list-requires-fields')

/*
 * Functions
 */
function maybeShowRequiredFieldsNotice () {
  msgRequiresFields.style.display = 'none'
  const listId = elSelectList.value
  const xhr = new XMLHttpRequest();
  xhr.open('GET', window.ajaxurl + '?action=mc4wp_get_list_details&ids=' + listId, true);
  xhr.onload = function() {
    if (this.status >= 400) {
      console.error("Error retrieving list details");
      return;
    }
    const lists = JSON.parse(this.responseText);
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
  };
  xhr.send(null);
}

// init colorpickers
window.jQuery('.mc4wp-color').wpColorPicker()

// if a list changes, check which fields are required
elSelectList.addEventListener('change', maybeShowRequiredFieldsNotice)

// check right away
maybeShowRequiredFieldsNotice()
