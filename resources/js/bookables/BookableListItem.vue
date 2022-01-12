<template>
    <!-- <div class="col d-flex align-items-stretch"> -->

    <div class="card w-100">
        <router-link :to="{ name: 'bookable', params: { id } }">
            <div class="card-header">
                {{ title }}
            </div>
        </router-link>
        <div class="card-body">
            <p class="lead">{{ description }}</p>
        </div>
    </div>
    <!-- </div> -->
</template>

<script>
export default {
    props: {
        title: String,
        description: String,
        id: Number,
    },
};
</script>
