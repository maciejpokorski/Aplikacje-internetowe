
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

Vue.component(
    'test-manager',
    require('./components/testsComponents/testManager.vue')
);

Vue.component(
    'single-test-manager',
    require('./components/testsComponents/singleTestManager.vue')
);

Vue.component(
    'question-manager',
    require('./components/testsComponents/questionManager.vue')
);

Vue.component(
    'single-question-manager',
    require('./components/testsComponents/singleQuestionManager.vue')
);

Vue.component(
    'answer-manager',
    require('./components/testsComponents/answerManager.vue')
);

Vue.component(
    'test-attempt-manager',
    require('./components/testsComponents/testAttemptManager.vue')
);

Vue.component('simple-panel-wrapper', {
    props: ['size', 'offset', 'title', "href"],
    template:
    `
        <div :class="[size, offset]">
            <div class="panel panel-default">
                <div class="panel-heading" v-if="href">
                  <a :href='href'>{{title}}</a>
                </div>
                <div class="panel-heading" v-else>
                    {{title}}
                </div>
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
