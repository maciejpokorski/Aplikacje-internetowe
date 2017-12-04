
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

Vue.component(
    'user-manager',
    require('./components/superAdminControl/userManager.vue')
);

Vue.component(
    'role-manager',
    require('./components/superAdminControl/roleManager.vue')
);

Vue.component(
    'permission-manager',
    require('./components/superAdminControl/permissionManager.vue')
);

Vue.component(
    'dash-board',
    require('./components/superAdminControl/dashBoard.vue')
);

Vue.component('simple-panel-wrapper', {
    props: ['size', 'offset', 'title'],
    template:
    `
        <div :class="[size, offset]">
            <div class="panel panel-default">
                <div class="panel-heading">{{title}}</div>
                <div class="panel-body">
                    <slot></slot>
                </div>
            </div>
        </div>
     `
})


Vue.component('example-component', require('./components/ExampleComponent.vue'));

//new Vue instance, use as Event Dispatcher (global communication BUS)
window.Event = new Vue();

const app = new Vue({
    el: '#app'
});
