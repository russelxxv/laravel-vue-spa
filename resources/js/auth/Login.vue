<template>
    <div class="w-50 m-auto">
        <form action="" @submit.prevent="submitLogin">
            <div class="card card-body">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        name="email"
                        v-model="email"
                        class="form-control"
                        placeholder="Enter E-mail"
                        :class="[{ 'is-invalid': errorFor('email') }]"
                    />
                    <v-errors :errors="errorFor('email')"></v-errors>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        name="password"
                        v-model="password"
                        class="form-control"
                        placeholder="Enter Password"
                        :class="[{ 'is-invalid': errorFor('password') }]"
                    />
                    <v-errors :errors="errorFor('password')"></v-errors>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                    :disabled="loading"
                >
                    <button-loading :loading="loading">Log-in</button-loading>
                </button>
                <hr />

                <div class="d-flex justify-content-between">
                    <span>
                        No account yet?
                        <router-link :to="{ name: 'register' }"
                            >Register</router-link
                        >
                    </span>
                    <span>
                        <router-link :to="{ name: 'password_reset' }"
                            >Forgotten Password?</router-link
                        >
                    </span>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import validationError from "./../shared/mixins/validationError";
import buttonLoading from "./../shared/component/ButtonLoading";
import { logIn } from "./../shared/utils/auth";

export default {
    mixins: [validationError],

    data() {
        return {
            email: null,
            password: null,
            loading: false,
        };
    },

    methods: {
        async submitLogin() {
            this.loading = true;
            this.errors = null;

            try {
                await axios.get("/sanctum/csrf-cookie");
                await axios.post("/login", {
                    email: this.email,
                    password: this.password,
                });
                logIn();

                this.$store.dispatch("loadUser");
                this.$router.push({ name: "home" });
            } catch (error) {
                this.errors = error.response && error.response.data.errors;
            }

            this.loading = false;
        },
    },
};
</script>
