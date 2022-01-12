<template>
    <div>
        <h6 class="text-uppercase text-secondary font-weight-bolder mt-2">
            price breakdown
        </h6>

        <div class="d-flex justify-content-between border-top border-bottom" v-for="(days, price) in price.breakdown" :key="'price'+price">
            <span>{{ days }} x ₱{{ price }}</span>
            <span>₱{{ days * price }}</span>
        </div>

        <div class="d-flex font-weight-bolder justify-content-between">
            <span>Total</span>
            <span>₱{{ price.total }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        price: Object
    }
}
</script>
