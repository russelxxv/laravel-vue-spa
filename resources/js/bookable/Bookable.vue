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
                        <h2>{{bookable.title}}</h2>
                    </div>
                    <div class="card-body">
                        {{bookable.description}}
                    </div>
                </div>

                <list-review :bookable-id="this.$route.params.id"></list-review>
            </div>

            <div class="col-md-4">
                <availability :bookable-id="this.$route.params.id"></availability>
            </div>
        </div>
    </div>
</template>


<script>

import Availability from "./Availability";
import ListReview from './ListReview';

export default {

    data() {
        return {
            bookable: null,
        };
    },

    components: {
        Availability,
        ListReview,
    },

    created() {
         axios
            .get(`/api/bookables/${this.$route.params.id}`)
            .then(response => {
                this.bookable = response.data.data
            });
    }
}
</script>