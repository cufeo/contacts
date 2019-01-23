<template>
    <div>
        <router-view></router-view>
        <base-loading></base-loading>
        <base-modal></base-modal>
    </div>
</template>

<script>
    import BaseLoading from "../components/BaseLoading";
    import BaseModal from "../components/BaseModal";

    export default {
        name: "App",
        components:{BaseLoading, BaseModal},
    }
</script>

<style scoped>

</style>