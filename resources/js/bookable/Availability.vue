<template>
    <div>
        <h6 class="text-uppercase text-secondary font-weight-bolder">
            Check Availability
            <transition name="fade">
                <span v-if="noAvailability" class="text-danger"
                    >(NOT AVAILABLE)</span
                >
                <span v-if="hasAvailabitlity" class="text-success"
                    >(AVAILABLE)</span
                >
            </transition>
        </h6>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="from">From</label>
                <input
                    type="text"
                    v-model="from"
                    class="form-control form-control-sm"
                    placeholder="Start Date"
                    name="from"
                    :class="[{ 'is-invalid': this.errorFor('from') }]"
                />
                <v-errors :errors="errorFor('from')"></v-errors>
            </div>

            <div class="form-group col-md-6">
                <label for="to">To</label>
                <input
                    type="text"
                    v-model="to"
                    class="form-control form-control-sm"
                    placeholder="End Date"
                    name="to"
                    :class="[{ 'is-invalid': this.errorFor('to') }]"
                />
                <v-errors :errors="errorFor('to')"></v-errors>
            </div>
        </div>
        <button
            type="button"
            class="btn btn-secondary btn-block"
            @click="check"
            :disabled="loading"
        >
            <button-loading :loading="loading">Check!</button-loading>
        </button>
    </div>
</template>

<script>
import { is422 } from "./../shared/utils/response";
import validationError from "./../shared/mixins/validationError";
export default {
    mixins: [validationError],
    // props: ["bookableId"],
    props: {
        bookableId: [String, Number],
    },

    data() {
        return {
            from: this.$store.state.lastSearch.from,
            to: this.$store.state.lastSearch.to,
            loading: false,
            responseStatusCode: null,
        };
    },

    methods: {
        async check() {
            // console.log(this.$store.state);

            this.loading = true;
            this.errors = null;
            this.$store.dispatch("setLastSearch", {
                from: this.from,
                to: this.to,
            });

            try {
                this.responseStatusCode = (
                    await axios.get(
                        `/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`
                    )
                ).status;
                this.$emit('availability', this.hasAvailabitlity);
            } catch (error) {
                if (is422(error)) {
                    this.errors = error.response.data.errors;
                }
                this.responseStatusCode = error.response.status;
                this.$emit('availability', this.hasAvailabitlity);
            }

            this.loading = false;

            // axios
            //     .get(
            //         `/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`
            //     )
            //     .then((data) => {
            //         this.loading = false;

            //         this.responseStatusCode = data.status;
            //     })
            //     .catch((error) => {
            //         if (is422(error)) {
            //             this.errors = error.response.data.errors;
            //         }
            //         this.responseStatusCode = error.response.status;
            //     })
            //     .then(() => (this.loading = false));
        },
    },

    computed: {
        hasErrors() {
            return 422 === this.responseStatusCode && this.errors !== null;
        },
        hasAvailabitlity() {
            return 200 === this.responseStatusCode;
        },
        noAvailability() {
            return 404 === this.responseStatusCode;
        },
    },
};
</script>

<style scoped>
label {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: grey;
    font-weight: bolder;
}
</style>
