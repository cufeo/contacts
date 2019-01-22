<template>
    <div class="modal fade" :id="id" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="state !== 'error'" class=" alert alert-warning">{{body}}</div>
                    <div v-else class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Unexpected Error :(</h4>
                        <p>{{errorMsg}}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button v-if="state !== 'actionInProgress'" type="button" class="btn btn-secondary"
                            data-dismiss="modal">Cancel
                    </button>

                    <button v-if="state === 'pendingAction'" type="button" class="btn btn-danger"
                            @click="onSubmitClicked">
                        Confirm
                    </button>
                    <button v-else-if="state === 'actionInProgress'" type="button" class="btn btn-danger">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{actionInProgressLabel}}
                    </button>
                    <button v-else-if="state === 'success'" type="button" class="btn btn-success" data-dismiss="modal">
                        Done
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "BaseModal",
        props: {
            id: String,
            title: String,
            body: String,
            contentId: Number,
            actionInProgressLabel: String,
            state: {
                type: String,
                default: 'pendingAction',
                validator: function (value) {
                    // The value must match one of these strings
                    return ['pendingAction', 'actionInProgress', 'error', 'success'].indexOf(value) !== -1
                }
            },
            errorMsg: {
                type: String,
                default: 'Oops, an unexpected error occurred :( Please try a next time.'
            },
        },
        methods: {
            onSubmitClicked() {
                this.$emit('submitClicked', this.contentId);
            }
        }
    }
</script>

<style scoped>

</style>