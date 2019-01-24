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
                    <div v-if="state !== 'error'" class="alert alert-warning">{{body}}</div>
                    <div v-else class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">{{errorTitle}}</h4>
                        <p>{{errorMessage}}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button v-if="state !== 'actionInProgress'" type="button" class="btn btn-secondary"
                            data-dismiss="modal">Cancel
                    </button>

                    <button v-if="state === 'pendingConfirmation'" type="button" class="btn btn-danger"
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
        props: ['id', 'title', 'body', 'actionInProgressLabel', 'errorTitle', 'errorMessage', 'state'],
        computed: {
            element() {
                return $(`#${this.id}`);
            }
        },
        watch: {
            state(value) {
                switch (value) {
                    case 'hidden':
                        this.element.modal('hide');
                        break;
                    case 'pendingConfirmation':
                        this.element.modal('show');
                        break;
                    case 'actionInProgress':
                    case 'success':
                    case 'error':
                        break;
                    default:
                        throw `[BaseModal]: Unknown value "${value}" passed to state property`;
                }
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