<template>
    <div>
        <success v-if="success">You've left a review, thank you very much!</success>
        <fatal-error v-if="error"></fatal-error>
        <div class="row" v-if="!success && !error">
            <div :class="[{ 'col-md-4': oneColumn }, { 'd-none': twoColumns }]">
                <div class="card">
                    <div v-if="loading">Loading...</div>
                    <div class="card-body" v-if="booking != null">
                        <p>
                            You stayed at
                            <router-link
                                :to="{
                                    name: 'bookable',
                                    params: {
                                        id: booking.bookable.bookable_id,
                                    },
                                }"
                                >{{ booking.bookable.title }}</router-link
                            >
                        </p>
                        <p>From {{ booking.from }} to {{ booking.to }}</p>
                    </div>
                </div>
            </div>
            <div
                :class="[
                    { 'col-md-8': oneColumn },
                    { 'col-md-12': twoColumns },
                ]"
            >
                <div>
                    <div v-if="alreadyReviewed">
                        <h2 class="text-muted">
                            You've already reviewed. Thank you for taking a time
                            😊!
                        </h2>
                    </div>
                    <div v-else>
                        <div class="form-group">
                            <label class="text-muted"
                                >Select the star rating (1 is worst - 5 is
                                best)</label
                            >
                            <star-rating
                                v-model="review.rating"
                                class="fa-3x"
                            ></star-rating>
                        </div>

                        <div class="form-group">
                            <label class="text-muted"
                                >Describe your experience with</label
                            >
                            <textarea
                                v-model="review.content"
                                name="content"
                                class="form-control"
                                cols="30"
                                rows="10"
                                placeholder="described your experience"
                                :class="[{ 'is-invalid': errorFor('content') }]"
                            ></textarea>
                            <v-errors :errors="errorFor('content')"></v-errors>
                        </div>

                        <button
                            type="type"
                            class="btn btn-primary"
                            @click.prevent="submit"
                            :disabled="submittingForm"
                        >
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { is404, is422 } from "./../shared/utils/response";
import validationError from "./../shared/mixins/validationError";

export default {
    mixins: [validationError],
    data() {
        return {
            review: {
                rating: 5,
                content: null,
                id: null,
            },
            existingReview: null,
            loading: false,
            booking: null,
            error: false,
            submittingForm: false,
            success: false,
        };
    },

    async created() {
        this.review.id = this.$route.params.id;
        this.loading = true;

        try {
            this.existingReview = (
                await axios.get(`/api/reviews/${this.review.id}`)
            ).data.data;
        } catch (err) {
            if (is404(err)) {
                try {
                    return (this.booking = (
                        await axios.get(
                            `/api/booking-by-review/${this.review.id}`
                        )
                    ).data.data);
                } catch (errr) {
                    return (this.error = !is404(errr));
                }
            } else {
                this.error = true;
            }
        }

        this.loading = false;
    },

    computed: {
        alreadyReviewed() {
            return this.hasReview && !this.booking;
        },
        hasReview() {
            return this.existingReview !== null;
        },
        hasBooking() {
            return this.booking !== null;
        },
        oneColumn() {
            return this.loading || !this.alreadyReviewed;
        },
        twoColumns() {
            return !this.loading && this.alreadyReviewed;
        },
    },

    methods: {
        submit() {
            this.submittingForm = true;
            this.errors = null;
            this.success = false;

            axios
                .post(`/api/reviews`, this.review)
                .then((response) => {
                    this.success = 201 === response.status;
                })
                .catch((err) => {
                    if (is422(err)) {
                        console.log("is 422");
                        const errors = err.response.data.errors;
                        if (errors["content"] && 1 === _.size(errors)) {
                            this.errors = errors;
                            return;
                        }
                    }

                    this.error = true;
                })
                .then(() => (this.submittingForm = false));
        },
    },
};
</script>
