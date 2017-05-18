
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

import AppShowScreenPlugin from './plugins/AppShowScreenPlugin';
Vue.use(AppShowScreenPlugin);

import AppEditScreenPlugin from './plugins/AppEditScreenPlugin';
Vue.use(AppEditScreenPlugin);

import AppHelpers from './plugins/AppHelpers';
Vue.use(AppHelpers);

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
import AdminUsersView from './components/AdminUsers/ViewUser.vue';
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
            { path: '/:id(\\d+)/view', name: 'admin_users.view', component: AdminUsersView },
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


/**
 * Members
 */
import AdminMembers from './components/AdminMembers/AdminMembers.vue';
import AdminMembersAll from './components/AdminMembers/AllMembers.vue';
import AdminMembersNew from './components/AdminMembers/NewMember.vue';
import AdminMembersView from './components/AdminMembers/ViewMember.vue';
import AdminMembersEdit from './components/AdminMembers/EditMember.vue';

if ( $('#admin-members-app').length ) {
    let router = new VueRouter({
        mode: 'history',
        base: links.base,
        linkActiveClass: 'active',
        routes: [
            { path: '/', name: 'admin_members.index', component: AdminMembersAll },
            { path: '/create', name: 'admin_members.create', component: AdminMembersNew },
            { path: '/:id(\\d+)/view', name: 'admin_members.view', component: AdminMembersView },
            { path: '/:id(\\d+)/edit', name: 'admin_members.edit', component: AdminMembersEdit },
            { path: '*', redirect: { name: 'admin_members.index' } }
        ]
    });

    new Vue({
        el: '#admin-members-app',
        components: {
            AdminMembers
        },
        router: router
    });
}