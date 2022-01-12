<template>
    <div class="container">
        <div class="col" v-if="bookable === null">
            <h1>Loading...</h1>
        </div>
        <!--
        <div class="col" v-else>
            <bookable-list-item v-bind="bookable"></bookable-list-item>
        </div> -->
        <div class="row" v-else>
            <div class="col-md-8">
                <div class="card w-100">
                    <div class="card-header">
                        <h2>{{ bookable.title }}</h2>
                    </div>
                    <div class="card-body">
                        {{ bookable.description }}
                    </div>
                </div>

                <list-review :bookable-id="this.$route.params.id"></list-review>
            </div>

            <div class="col-md-4">
                <availability
                    :bookable-id="this.$route.params.id"
                    @availability="checkPrice($event)"
                ></availability>

                <hr class="my-3" />

                <transition name="fade">
                    <price-breakdown
                        :price="price"
                        v-if="price"
                        class="mb-4"
                    ></price-breakdown>
                </transition>

                <transition name="fade">
                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-block"
                        v-if="price"
                        @click="addToBasket"
                        :disabled="inBasketAlready"
                    >
                        Book now!
                    </button>
                </transition>

                <transition name="fade">
                    <button
                        v-if="inBasketAlready"
                        type="button"
                        class="btn btn-secondary btn-block"
                        @click="removeFromBasket"
                    >
                        Remove in Basket
                    </button>
                </transition>
                
                <div class="text-muted warning font-italic" v-if="inBasketAlready">
                    This item is in you're basket. If you want to change the
                    date, remove first in your basket
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Availability from "./Availability";
import ListReview from "./ListReview";
import { mapState } from "vuex";
import PriceBreakdown from "./PriceBreakdown";

export default {
    data() {
        return {
            bookable: null,
            price: null,
        };
    },

    components: {
        Availability,
        ListReview,
        PriceBreakdown,
    },

    created() {
        axios
            .get(`/api/bookables/${this.$route.params.id}`)
            .then((response) => {
                this.bookable = response.data.data;
            });
    },

    computed: {
        ...mapState({
            lastSearch: "lastSearch",
        }),
        inBasketAlready() {
            if ( null === this.bookable.id) {
                return false;
            }

            return this.$store.getters.inBasketAlready(this.bookable.id);
        }
    },
    // mapState({
    //     lastSearch: "lastSearch",
    //     inBasketAlready(state) {
    //         if (null === this.bookable) {
    //             return false;
    //         }

    //         return state.basket.items.reduce(
    //             (result, item) =>
    //                 result || item.bookable.id === this.bookable.id,
    //             false
    //         );
    //     },
    // }),

    methods: {
        async checkPrice(hasAvailabitlity) {
            // console.log(hasAvailabitlity);
            if (!hasAvailabitlity) {
                this.price = null;
                return;
            }

            try {
                this.price = (
                    await axios.get(
                        `/api/bookables/${this.bookable.id}/price?from=${this.lastSearch.from}&to=${this.lastSearch.to}`
                    )
                ).data.data;
            } catch (error) {
                this.price = null;
            }
        },
        addToBasket() {
            this.$store.dispatch("addToBasket", {
                bookable: this.bookable,
                price: this.price,
                dates: this.lastSearch,
            });
        },
        removeFromBasket() {
            this.$store.dispatch("removeFromBasket", this.bookable.id);
        },
    },
};
</script>

<style scoped>
.warning {
    font-size: 0.7rem;
}
</style>
