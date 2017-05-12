
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueProgressBar from 'vue-progressbar';
Vue.use(VueProgressBar, { color: '#F8CA00', failedColor: '#FF003C', thickness: '5px'});
Vue.component('pagination', require('vue-bootstrap-pagination'));

import AppListScreenPlugin from './plugins/AppListScreenPlugin';
Vue.use(AppListScreenPlugin);

import AppCreateScreenPlugin from './plugins/AppCreateScreenPlugin';
Vue.use(AppCreateScreenPlugin);

import AppEditScreenPlugin from './plugins/AppEditScreenPlugin';
Vue.use(AppEditScreenPlugin);

/**
 * Settings
 */
import Admin from './components/Admin/Admin.vue';
import AdminDashboard from './components/Admin/Dashboard.vue';
import AdminProfile from './components/Admin/Profile.vue';
import AdminEditProfile from './components/Admin/EditProfile.vue';
import AdminEditPassword from './components/Admin/EditPassword.vue';

if ( $('#admin-app').length ) {
    let router = new VueRouter({
        mode: 'history',
        base: links.base,
        linkActiveClass: 'active',
        routes: [
            { path: '/', name: 'settings.index', component: AdminDashboard },
            { path: '/profile', name: 'settings.profile', component: AdminProfile },
            { path: '/edit-profile', name: 'settings.edit_profile', component: AdminEditProfile },
            { path: '/edit-password', name: 'settings.edit_password', component: AdminEditPassword },
            { path: '*', redirect: { name: 'settings.index' } }
        ]
    });

    new Vue({
        el: '#admin-app',
        components: {
            Admin
        },
        router: router
    });
}


/**
 * Users
 */
import AdminUsers from './components/AdminUsers/AdminUsers.vue';
import AdminUsersAll from './components/AdminUsers/AllUsers.vue';
import AdminUsersNew from './components/AdminUsers/NewUser.vue';
import AdminUsersEdit from './components/AdminUsers/EditUser.vue';
import AdminUsersEditPermissions from './components/AdminUsers/EditUserPermissions.vue';

if ( $('#admin-users-app').length ) {
    let router = new VueRouter({
        mode: 'history',
        base: links.base,
        linkActiveClass: 'active',
        routes: [
            { path: '/', name: 'admin_users.index', component: AdminUsersAll },
            { path: '/create', name: 'admin_users.create', component: AdminUsersNew },
            { path: '/:id(\\d+)/edit', name: 'admin_users.edit', component: AdminUsersEdit },
            { path: '/:id(\\d+)/permissions', name: 'admin_users.edit_permissions', component: AdminUsersEditPermissions },
            { path: '*', redirect: { name: 'admin_users.index' } }
        ]
    });

    new Vue({
        el: '#admin-users-app',
        components: {
            AdminUsers
        },
        router: router
    });
}