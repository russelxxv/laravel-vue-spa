<template>
    <div>
        <nav class="navbar navbar-expand-lg bg-white border-bottom navbar-light">
            <router-link class="navbar-brand mr-auto" :to="{ name: 'home' }"
                >Home</router-link
            >

            <ul class="navbar-nav">
                <li class="nav-item" v-if="isLoggedIn">
                    <router-link
                        class="btn nav-button"
                        :to="{ name: 'basket' }"
                    >
                        Basket
                        <span
                            v-if="itemsInBasket"
                            class="badge badge-secondary"
                            >{{ itemsInBasket }}</span
                        >
                    </router-link>
                </li>

                <li class="nav-item" v-if="!isLoggedIn">
                    <router-link class="nav-link" :to="{ name:'register' }">Register</router-link>
                </li>

                <li class="nav-item" v-if="!isLoggedIn">
                    <router-link class="nav-link" :to="{ name:'login' }">Login</router-link>
                </li>

                <li class="nav-item" v-if="isLoggedIn">
                    <a href="#" class="nav-link" @click="logout">Logout</a>
                </li>
            </ul>
        </nav>

        <div class="container mt-4 mb-4 pr-4 pl-4">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
    data() {
        return {
            from: null,
            to: null,
        };
    },

    methods: {
        async logout() {
            try {
                await axios.post('/logout');
                this.$store.dispatch('logOut');
            } catch (error) {
                this.$store.dispatch('logOut');
            }
        }
    },

    computed: {
        ...mapState({
            lastSearchComputed: "lastSearch",
            isLoggedIn: "isLoggedIn",
        }),
        ...mapGetters({
            itemsInBasket: "itemsInBasket",
            // inBasketAlready: "inBasketAlready"
        }),
    },
};
</script>
