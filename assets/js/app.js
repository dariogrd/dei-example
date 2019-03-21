/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

import 'vuetify/dist/vuetify.css';
import '@mdi/font/css/materialdesignicons.css';
import '../sass/app.scss';

import Vue from 'vue';
import Vuetify from 'vuetify';
import Tasks from './components/Tasks';

Vue.use(Vuetify);

const app = new Vue({
    el: '#app',
    data: {
        drawer: null,
    },
    components: {
        Tasks
    }
});