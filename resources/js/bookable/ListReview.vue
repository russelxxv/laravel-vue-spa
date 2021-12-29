<template>
    <div class="container">
        <h6 class="text-uppercase text-secondary font-weight-bolder pt-4">Review List</h6>

        <div class="col text-secondary" v-if="loading">Loading...</div>
        <div v-else>
            <div class="border-bottom" v-for="(review, index) in reviews" :key="'review'+index">
                <div class="row pt-4">
                    <div class="col-md-6">
                        Pepito Manaloto
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <star-rating :value="review.rating" class="fa-lg"></star-rating>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-small">
                        {{ review.created_at | fromNow }}
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col-md-12">
                        {{ review.content }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    
    props: ['bookableId'],

    data() {
        return {
            reviews: null,
            loading: false,
        }
    },

    created() {
        this.loading = true;
        axios
            .get(`/api/bookables/${this.bookableId}/reviews`)
            .then((response) => {
                this.reviews = response.data.data;
                // this.loading = false;
            })
            .catch((error) => {
                console.log(error);
            })
            .then(() => this.loading = false);
    },

}
</script>