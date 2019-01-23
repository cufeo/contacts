<template>
    <div class="container-fluid app-datatable-container">
        <datatable
                ref="datatable"
                :columns="columns"
                :resource-url="apiUrl"
                @show-record="onShowContactClicked"
                @delete-record="onDeleteContactClicked"
                @edit-record="onEditContactClicked"
                @refreshed="onContactsRefreshed">
            <div class="row justify-content-between app-header">
                <h6 class="col"><strong>List Of Contacts</strong></h6>
                <div class="col text-right app-add-btn">
                    <button class='btn btn-sm btn-outline-primary' @click="onAddContactClicked()">
                        <i class="fas fa-address-book pr-1"></i> Add Contact
                    </button>
                </div>
            </div>
        </datatable>
    </div>

</template>

<script>
    import Datatable from "../components/Datatable";
    import {ContactsApi} from "../utils/contacts-api";
    import {EventBus} from "../utils/event-bus";

    export default {
        components: {Datatable},
        props: ['initialContacts'],
        data() {
            return {
                contacts: [],
                columns: [
                    {label: 'ID #', name: 'id'},
                    {label: 'BIRTH DATE', name: 'birth_date'},
                    {label: 'NAME', name: 'first_name'},
                    {label: 'CITY', name: 'city'},
                    {label: 'EMAIL', name: 'email'},
                    {label: 'PHONE NUMBER', name: 'phone'},
                    {label: 'COMPANY', name: 'company_name'},
                    {label: null, name: 'last_name'}, // not visible
                    {label: null, name: 'function'}, // not visible
                    {label: null, name: 'company_address'}, // not visible,
                ],
                apiUrl: 'api/contacts',
                deleteConfirmationMessage: {
                    title: 'Delete Contact',
                    body: (contact) => `Are you sure you want to delete this contact: ${contact['first_name']} ${contact['last_name']} (${contact['email']}) ?`
                },
            }
        },
        methods: {
            onShowContactClicked(contact) {
                this.$router.push({name: 'contactShow', params: {id: contact.id, cachedContact: contact}});
            },
            onEditContactClicked(contact) {
                this.$router.push({name: 'contactShow', params: {id: contact.id, cachedContact: contact}});
            },
            onDeleteContactClicked(contact) {
                const vm = this;
                EventBus.$on('submit-app-modal', vm.onDeleteContactConfirmed);
                EventBus.$emit('show-app-modal', {
                    title: vm.deleteConfirmationMessage.title,
                    body: vm.deleteConfirmationMessage.body(contact),
                    contentId: contact.id,
                    actionInProgressLabel: 'Deleting...',
                    errorTitle: 'Ooops something went wrong :(',
                    errorMessage: 'Please reload the page and try again.',
                    state: 'pendingAction',
                });
            },
            async onDeleteContactConfirmed(contactId) {
                const vm = this;
                EventBus.$emit('set-app-modal-state', 'actionInProgress');
                try {
                    await ContactsApi.delete(contactId);
                    EventBus.$emit('hide-app-modal');
                    this.$refs.datatable.$emit('refresh');
                } catch (error) {
                    EventBus.$emit('set-app-modal-state', 'error');
                }
            },
            onAddContactClicked() {
                this.$router.push({name: 'contactCreate'});
            },
            onContactsRefreshed(contacts){
                EventBus.$data.contactsCache = contacts;
            }

        }
    }
</script>
<style>
    .app-add-btn {
        margin-bottom: 1rem;
    }
    .app-datatable-container {
        padding-top: 1rem;
        padding-bottom: 1rem;

    }
</style>