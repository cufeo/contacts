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
    import {EventBus} from "../utils/event-bus";

    export default {
        name: "BaseModal",
        data() {
            return {
                id: 'app-modal',
                title: null,
                body: null,
                contentId: null,
                actionInProgressLabel: null,
                errorTitle: 'Something went wrong :(',
                errorMessage: 'Oops, an unexpected error occurred :( Please try a next time.',
                state: 'pendingAction', // Accept: 'pendingAction', 'actionInProgress', 'error', 'success',
                visible: false,
            }
        },
        computed: {
          element() {
              return $(`#${this.id}`);
          }
        },
        mounted() {
            const vm = this;
            EventBus.$on('show-app-modal', function (options) {
                if(!vm.visible) {
                    vm.show(options);
                }
            });
            EventBus.$on('set-app-modal-state', function (newState) {
                vm.state = newState;
            });
            EventBus.$on('hide-app-modal', function () {
                if(vm.visible) {
                    vm.hide();
                }
            });
            vm.element.on('show.bs.modal', function () {
                vm.visible = true;
            });
            vm.element.on('hide.bs.modal', function () {
                vm.visible = false;
            });
        },
        methods: {
            show({title, body, contentId, actionInProgressLabel, errorTitle, errorMessage, state}) {
                const vm = this;
                vm.title = title;
                vm.body = body;
                vm.contentId = contentId;
                vm.actionInProgressLabel = actionInProgressLabel;
                vm.errorTitle = errorTitle;
                vm.errorMessage = errorMessage;
                vm.state = state;
                vm.element.modal(); // display modal on screen
            },
            hide() {
                this.element.modal('hide');
            },
            onSubmitClicked() {
                EventBus.$emit('submit-app-modal', this.contentId);
            }
        }
    }
</script>

<style scoped>

</style>