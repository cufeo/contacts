<template>
    <div>
        <router-view></router-view>
        <base-loading></base-loading>
    </div>
</template>

<script>
    import BaseLoading from "../components/BaseLoading";
    export default {
        name: "App",
        components:{BaseLoading},
    }
</script>

<style scoped>

</style>