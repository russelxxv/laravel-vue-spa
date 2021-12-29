<template>
    <div>
        <nav class="navbar bg-white border-bottom navbar-light">
            <router-link class="navbar-brand mr-auto" :to="{name: 'home'}">Home</router-link>
            <router-link class="btn nav-button" :to="{name: 'home'}">Second</router-link>
        </nav>

        <div class="container mt-4 mb-4 pr-4 pl-4">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {

    data() {
        return {
            from: null,
            to: null
        }
    },

    computed: {
        ...mapState({
            lastSearchComputed: "lastSearch"
        })
    }
}
</script>