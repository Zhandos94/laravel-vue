import Vue from 'vue';
import VueRouter from 'vue-router';
import ExampleComponent from "./components/ExampleComponent";
import ContactsCreate from "./views/ContactsCreate";
import ContactsIndex from "./views/ContactsIndex";
import ContactsShow from "./views/ContactsShow";

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            path: '/', component: ExampleComponent,
            meta: { title: 'Welcome' }
        },
        {
            path: '/contacts', component: ContactsIndex,
            meta: { title: 'Welcome' }
        },
        {
            path: '/contacts/create', component: ContactsCreate,
            meta: { title: 'Welcome' }
        },
        {
            path: '/contacts/:id', component: ContactsShow,
            meta: { title: 'Details for Contact' }
        },
    ],
    mode: 'history'
});
