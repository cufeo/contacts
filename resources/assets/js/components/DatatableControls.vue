<template>
    <div class="btn-group">
        <button type="button" class="btn btn-small" @click="onEvent('edit')">
            <i class="fas fa-pencil-alt"></i>
        </button>
        <button type="button" class="btn btn-small" @click="onEvent('delete')">
            <i class="fas fa-trash-alt"></i>
        </button>
        <button type="button" class="btn btn-small" @click="onEvent('show')">
            <i class="far fa-eye"></i>
        </button>
        <button type="button" class="btn btn-small" @click="onEvent('show')">
            <i class="fas fa-street-view"></i>
        </button>
    </div>

</template>

<script>
    export default {
        name: 'DatatableControls',
        props: ['id'],
        methods: {
            onEvent(name) {
                this.$emit(name, this.id);
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