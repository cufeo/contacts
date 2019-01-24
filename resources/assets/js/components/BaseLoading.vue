<template>
    <div class="app-loading" v-if="show">
        <div class="app-spinner-wrapper">
            <div class="spinner-border text-primary app-spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="app-loading-overlay"></div>
    </div>
</template>

<script>
    export default {
        name: 'BaseLoading',
        props: ['show']
    }
</script>

<style scoped>
    .app-loading {
        position: fixed;
        top: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        z-index: 5;
    }

    .app-loading-overlay {
        position: fixed;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: white;
        opacity: 0.9;
    }

    .app-spinner-wrapper {
        z-index: 10;
        display: flex;
        margin: 0 auto;
    }

    .app-spinner {
        width: 5rem;
        height: 5rem;
        border-width: 0.4rem;
    }
</style>