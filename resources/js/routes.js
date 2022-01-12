import VueRouter from "vue-router";
// import ExampleComponent from "./components/ExampleComponent";
import Bookables from "./bookables/Bookables";
import Bookable from "./bookable/Bookable";
import Review from "./review/review";
import Basket from "./basket/Basket";
import Login from "./auth/Login";
import Register from "./auth/Register";
import PasswordReset from "./auth/PasswordReset";

const routes = [
    {
        path: "/",
        component: Bookables,
        name: "home"
    },
    {
        path: "/bookable/:id",
        component: Bookable,
        name: "bookable"
    },
    {
        path: "/review/:id",
        component: Review,
        name: "review"
    },
    {
        path: '/basket',
        component: Basket,
        name: "basket"
    },
    {
        path: '/auth/login',
        component: Login,
        name: "login"
    },
    {
        path: '/auth/register',
        component: Register,
        name: "register"
    },
    {
        path: '/auth/password/reset',
        component: PasswordReset,
        name: "password_reset"
    }
];


// Instatiate the routes
const router = new VueRouter({
    routes,
    mode: "history",
});

export default router;