<template>
    <div>
        <span class="text-danger" v-if="!termsState">Please agree to the terms and conditions.</span>
        <div class="row justify-content-between">
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="terms" id="terms" :checked="termsAccepted" v-model="termsState">
                    <label class="form-check-label" for="terms">
                        I agree with the <a href="/">terms</a>
                    </label>
                </div>
            </div>

            <div class="col text-right">
                <button type="submit" class="btn btn-primary" :disabled="!termsState" @click="onSubmitClicked">
                    {{submitButtonText}}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "BaseFormSubmit",
        props: ['submitButtonText', 'termsAccepted'],
        data() {
            return {
                termsState: this.termsAccepted,
            }
        },
        methods: {
            onSubmitClicked() {
                this.$emit('submit');
            }
        }
    }
</script>

<style scoped>

</style>