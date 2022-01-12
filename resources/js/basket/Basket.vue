<template>
    <div>
        <div class="row">
            <div class="col-md-8" v-if="itemsInBasket">
                <div class="d-flex border-bottom mb-2 pb-1">
                    <h6 class="text-muted text-uppercase">Checkout Form</h6>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="first_name">First Name</label>
                        <input
                            type="text"
                            class="form-control"
                            name="first_name"
                            v-model="customer.first_name"
                            :class="[{ 'is-invalid': this.errorFor('customer.first_name') }]"
                        />
                        <v-errors :errors="errorFor('customer.first_name')"></v-errors>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name">Last Name</label>
                        <input
                            type="text"
                            class="form-control"
                            name="last_name"
                            v-model="customer.last_name"
                            :class="[{ 'is-invalid': this.errorFor('customer.last_name') }]"
                        />
                        <v-errors :errors="errorFor('customer.last_name')"></v-errors>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            v-model="customer.email"
                            :class="[{ 'is-invalid': this.errorFor('customer.email') }]"
                        />
                        <v-errors :errors="errorFor('customer.email')"></v-errors>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="address">Address</label>
                        <textarea
                            name="address"
                            class="form-control"
                            cols="30"
                            rows="10"
                            v-model="customer.address"
                            :class="[{ 'is-invalid': this.errorFor('customer.address') }]"
                        ></textarea>
                        <v-errors :errors="errorFor('customer.address')"></v-errors>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="country">Country</label>
                        <input
                            type="text"
                            class="form-control"
                            name="country"
                            v-model="customer.country"
                            :class="[{ 'is-invalid': this.errorFor('customer.country') }]"
                        />
                        <v-errors :errors="errorFor('customer.country')"></v-errors>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="state">State</label>
                        <input
                            type="text"
                            class="form-control"
                            name="state"
                            v-model="customer.state"
                            :class="[{ 'is-invalid': this.errorFor('customer.state') }]"
                        />
                        <v-errors :errors="errorFor('customer.state')"></v-errors>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="zip">Zip</label>
                        <input
                            type="text"
                            class="form-control"
                            name="zip"
                            v-model="customer.zip"
                            :class="[{ 'is-invalid': this.errorFor('customer.zip') }]"
                        />
                        <v-errors :errors="errorFor('customer.zip')"></v-errors>
                    </div>
                </div>
                <div class="d-flex">
                    <button
                        type="submit"
                        class="btn btn-block btn-primary"
                        @click="checkoutBasket"
                        :disabled="loading"
                    >
                        <button-loading :loading="loading"
                            >Book now!</button-loading
                        >
                    </button>
                </div>
            </div>
            <div class="col-md-8" v-else>
                <div class="jumbotron jumbotron-fluid text-center">
                    <h1>Empty</h1>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d-flex justify-content-between border-bottom">
                    <h6 class="text-muted text-uppercase">YOUR CART</h6>

                    <h6 class="text-muted text-uppercase">
                        <span class="badge badge-secondary" v-if="itemsInBasket"
                            >ITEMS {{ itemsInBasket }}</span
                        >
                        <span class="badge badge-secondary" v-else>EMPTY </span>
                    </h6>
                </div>

                <transition-group name="fade">
                    <div v-for="item in basket" :key="item.bookable.id">
                        <div class="d-flex justify-content-between pt-2 mb-2">
                            <span>
                                <router-link
                                    :to="{
                                        name: 'bookable',
                                        params: { id: item.bookable.id },
                                    }"
                                    >{{ item.bookable.title }}</router-link
                                >
                            </span>
                            <span>₱ {{ item.price.total }}</span>
                        </div>
                        <div class="d-flex">
                            <span class="mr-1">
                                {{ stringDate(item.dates.from) }}
                            </span>
                            <small class="font-weight-bolder">to</small>
                            <span class="ml-1">
                                {{ stringDate(item.dates.to) }}
                            </span>
                        </div>
                        <div class="col text-right pb-2 pr-0 border-bottom">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                @click="
                                    $store.dispatch(
                                        'removeFromBasket',
                                        item.bookable.id
                                    )
                                "
                            >
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </transition-group>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
import validationError from "./../shared/mixins/validationError";
import buttonLoading from "./../shared/component/ButtonLoading";

export default {
    mixins: [validationError],

    components: {
        buttonLoading,
    },

    data() {
        return {
            loading: false,
            customer: {
                first_name: null,
                last_name: null,
                email: null,
                address: null,
                country: null,
                state: null,
                zip: null,
            },
        };
    },

    methods: {
        stringDate(value) {
            return moment(value).format("MMM Do YY");
        },

        async checkoutBasket() {
            this.loading = true;
            this.errors = null;
            try {
                await axios.post(`/api/checkout`, {
                    customer: this.customer,
                    bookings: this.basket.map((basketItem) => ({
                        bookableid: basketItem.bookable.id,
                        from: basketItem.dates.from,
                        to: basketItem.dates.to,
                    })),
                });

                this.$store.dispatch("clearBasket");
            } catch (error) {
                this.errors = error.response && error.response.data.errors;
            }
            this.loading = false;
        },
    },

    computed: {
        ...mapGetters({
            itemsInBasket: "itemsInBasket",
        }),
        ...mapState({
            basket: (state) => state.basket.items,
        }),
    },
};
</script>

<style scoped>
a {
    color: black;
}
</style>
