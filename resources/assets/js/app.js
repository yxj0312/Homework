
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import InstantSearch from 'vue-instantsearch';

window.Vue = require('vue');
require('./bootstrap');

Vue.use(InstantSearch);

let authorizations = require('./authorizations');

/*global Vue*/
// Vue.prototype.authorize = function (handler) {
Vue.prototype.authorize = function(...params) {
  /* Additional admin privileges. */
  // Add this from backend Auth::user() in the app.blade.php

  // let user = window.App.user;
  if (!window.App.signedIn) return false;

  if (typeof params[0] === "string") {
    return authorizations[params[0]](params[1]);
  }

  // If u not signed in, it returns false
  // Otherwise, it triggers that callback function - handler, and pass in with 'user'
  // And whatever u returned from that function, determine if the user is authorized.

  // return user ? handler(user) : false;
  return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('paginator', require('./components/Paginator.vue'));
Vue.component("user-notifications", require("./components/UserNotifications.vue"));
Vue.component('avatar-form', require("./components/AvatarForm.vue"));
Vue.component('wysiwyg', require("./components/Wysiwyg.vue"));
Vue.component("dropdown", require("./components/Dropdown.vue"));
Vue.component('channel-dropdown', require('./components/ChannelDropdown.vue')); 
Vue.component('logout-button', require('./components/LogoutButton'));
Vue.component("login", require("./components/Login"));
Vue.component("register", require("./components/Register"));


Vue.component('thread-view', require('./pages/Thread.vue'));



/* eslint-disable no-unused-vars*/
const app = new Vue({
    el: '#app'
});
