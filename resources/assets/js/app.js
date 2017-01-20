require('./bootstrap');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueProgressBar from 'vue-progressbar';
Vue.use(VueProgressBar, { color: 'yellow', failedColor: '#FF0000', thickness: '5px'});
Vue.component('pagination', require('vue-bootstrap-pagination'));


/**
 * Settings
 */
import Settings from './components/Settings.vue';
import SettingsProfile from './components/SettingsProfile.vue';
import SettingsSecurity from './components/SettingsSecurity.vue';

if ( $('#settings-app').length ) {
    let router = new VueRouter({
        mode: 'history',
        base: settingsLinks.baseUri,
        linkActiveClass: 'active',
        routes: [
            { path: '/profile', name: 'settings.index', component: SettingsProfile },
            { path: '/security', name: 'settings.security', component: SettingsSecurity },
            { path: '*', redirect: { name: 'settings.index' } }
        ]
    });

    new Vue({
        el: '#settings-app',
        components: {
            Settings
        },
        router: router
    });
}