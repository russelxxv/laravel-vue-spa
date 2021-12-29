<template>
    <div class="col text-center success">
        <i class="fas fa-thumbs-up text-primary"></i>
        <h1>Success!</h1>
        <h2>
            <slot></slot>
        </h2>
    </div>
</template>

<style scoped>
    .success{
        font-size: 10rem;
        color: #999;
    }
</style>