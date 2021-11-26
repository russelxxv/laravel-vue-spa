import VueRouter from "vue-router";
import ExampleComponent from "./components/ExampleComponent";
import Bookables from "./Bookables/Bookables.vue";

const routes = [
    {
        path: "/",
        component: Bookables,
        name: "home"
    },
    {
        path: "/second",
        component: ExampleSecond,
        name: "exampletwo"
    },
];


// Instatiate the routes
const router = new VueRouter({
    routes,
    mode: "history",
});

export default router;