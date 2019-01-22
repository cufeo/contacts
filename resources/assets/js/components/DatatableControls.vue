<template>
    <div class="btn-group">
        <button type="button" class="btn btn-small" @click="onEvent('editClicked')">
            <i class="fas fa-pencil-alt"></i>
        </button>
        <button type="button" class="btn btn-small" @click="onEvent('deleteClicked')">
            <i class="fas fa-trash-alt"></i>
        </button>
        <button type="button" class="btn btn-small" @click="onEvent('showClicked')">
            <i class="far fa-eye"></i>
        </button>
        <button type="button" class="btn btn-small" @click="onEvent('streetViewClicked')">
            <i class="fas fa-street-view"></i>
        </button>
    </div>

</template>

<script>
    export default {
        name: 'DatatableControls',
        props: ['row'],
        methods: {
            onEvent(name) {
                this.$emit(name, this.row);
            }
        }
    }
</script>

<style scoped>
    button {
        padding-right: 0.5rem;
        padding-left: 0.5rem;
    }
</style>