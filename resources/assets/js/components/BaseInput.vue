<template>
    <div class="form-group">
        <label :for="id">{{label}}</label>
        <input :type="type"
               :class="{'form-control': true, 'is-invalid': this.errors}"
               :id="id"
               :name="id"
               :value="value"
               @input="input"
               v-on="listeners">
        <div v-if="this.errors" class="invalid-feedback">
            <span class="block" v-for="err in errors">{{err}}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'BaseInput',
        props: ['id','label' ,'type', 'value','errors'],
        methods: {
            input(event) {
                this.$emit('input', event.target.value);
            },
        },
        computed: {
            // Setup transparent wrapper component implementation
            listeners() {
                const { input, ...listeners } = this.$listeners;
                return listeners;
            },
        },
    }
</script>

<style scoped>

</style>