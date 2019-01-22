/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

import Vue from 'vue';
import VueRouter from 'vue-router';
import App from "./views/App";
import Contact from "./views/Contact";
import Contacts from "./views/Contacts";


Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            redirect: '/contacts',
        },
        {
            path: '/contacts',
            name: 'contacts',
            component: Contacts
        },
        {
            path: '/contacts/create',
            name: 'contactCreate',
            component: Contact,
            props: {
                mode: 'create'
            }
        },
        {
            path: '/contacts/:id',
            name: 'contactShow',
            component: Contact,
            props: {
                mode: 'edit'
            }
        }
    ],
});

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router
});
