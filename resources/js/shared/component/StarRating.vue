<template>
    <div class="d-flex">
        <i
            class="fas fa-star"
            v-for="(fullStar, index) in fullStars"
            :key="'fullStar' + index"
            @click="$emit('input', fullStar)"
        ></i>
        <i class="fas fa-star-half-alt" v-if="halfStar"></i>
        <i
            class="far fa-star"
            v-for="(emptyStar, index) in emptyStars"
            :key="'emptyStar' + index"
            @click="$emit('input', fullStars + emptyStar)"
        ></i>
    </div>
</template>

<script>
export default {
    props: {
        value: Number,
    },

    computed: {
        fullStars() {
            return Math.round(this.value);
        },

        halfStar() {
            const fraction = Math.round(
                (this.value - Math.floor(this.value)) * 100
            );
            return false;
        },

        emptyStars() {
            return 5 - Math.ceil(this.value);
        },
    },
};
</script>
