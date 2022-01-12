<template>
    <div class="w-50 m-auto">
        <form action="" @submit.prevent="submitRegister">
            <div class="card card-body">
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input
                        type="text"
                        name="name"
                        v-model="user.name"
                        class="form-control"
                        placeholder="Enter Full Name"
                        :class="[{ 'is-invalid': errorFor('name') }]"
                    />
                    <v-errors :errors="errorFor('name')"></v-errors>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        name="email"
                        v-model="user.email"
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
                        v-model="user.password"
                        class="form-control"
                        placeholder="Enter Password"
                        :class="[{ 'is-invalid': errorFor('password') }]"
                    />
                    <v-errors :errors="errorFor('password')"></v-errors>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Re-type Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        v-model="user.password_confirmation"
                        class="form-control"
                        placeholder="Enter Password"
                        :class="[{ 'is-invalid': errorFor('password_confirmation') }]"
                    />
                    <v-errors :errors="errorFor('password_confirmation')"></v-errors>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                    :disabled="loading"
                >
                    <button-loading :loading="loading">Register</button-loading>
                </button>
                <hr />

                <div class="d-flex justify-content-between">
                    <span>
                        Already have an account?
                        <router-link :to="{ name: 'login' }"
                            >Sign-In</router-link
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
            user: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
            loading: false,
        };
    },

    methods: {
        async submitRegister() {
            this.loading = true;
            this.errors = null;

            try {
                // await axios.get("/sanctum/csrf-cookie");
                const response = await axios.post("/register", this.user);

                if ( 201 === response.status ) {
                    logIn();
                    this.$store.dispatch("loadUser");
                    this.$router.push({ name: "home" });
                }
            } catch (error) {
                this.errors = error.response && error.response.data.errors;
                this.loading = false;
            }

            this.loading = false;
        },
    },
};
</script>
