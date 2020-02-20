import Vue from 'vue';
import VueRouter from 'vue-router';
import ExampleComponent from "./components/ExampleComponent";
import ContactsCreate from "./views/ContactsCreate"

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            path: '/', component: ExampleComponent,
            meta: { title: 'Welcome' }
        },
        {
            path: '/contacts', component: ContactsCreate,
            meta: { title: 'Welcome' }
        },
    ],
    mode: 'history'
});
