<template>
    <div>
        <span v-if="!loading"><slot></slot></span>
        <span v-if="loading"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
    </div>
</template>

<script>
export default {
    props: {
        loading: Boolean
    },
}
</script>