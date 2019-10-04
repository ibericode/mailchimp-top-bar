(function () { var require = undefined; var module = undefined; var exports = undefined; var define = undefined;(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
'use strict';
/*
 * Variables
 */

var $ = window.jQuery;
var $context = $(document.getElementById('mc4wp-admin'));
var $selectList = $(document.getElementById('select-mailchimp-list'));
var msgRequiresFields = document.getElementById('message-list-requires-fields');
/*
 * Functions
 */

function maybeShowRequiredFieldsNotice() {
  // hide message
  msgRequiresFields.style.display = 'none';
  var listId = $selectList.val();
  $.get(ajaxurl + "?action=mc4wp_get_list_details&ids=" + listId).then(function (lists) {
    // iterate over selected lists
    for (var i = 0; i < lists.length; i++) {
      var list = lists[i]; // iterate over list fields

      for (var j = 0; j < list.merge_fields.length; j++) {
        var field = list.merge_fields[j]; // if field other than EMAIL is required, show notice and stop loop

        if (field.tag !== 'EMAIL' && field.required) {
          msgRequiresFields.style.display = '';
          return;
        }
      }
    }
  });
} // init colorpickers


$context.find('.mc4wp-color').wpColorPicker(); // if a list changes, check which fields are required

$selectList.change(maybeShowRequiredFieldsNotice); // check right away

maybeShowRequiredFieldsNotice();

},{}]},{},[1]);
; })();