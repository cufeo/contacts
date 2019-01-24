<template>
    <div class="container-fluid app-datatable-container">
        <datatable
                ref="datatable"
                :columns="columns"
                :resource-url="apiUrl"
                @show-record="onShowContactClicked"
                @delete-record="onDeleteContactClicked"
                @edit-record="onEditContactClicked">
            <div class="row justify-content-between app-header">
                <h6 class="col"><strong>List Of Contacts</strong></h6>
                <div class="col text-right app-add-btn">
                    <button class='btn btn-sm btn-outline-primary' @click="onAddContactClicked()">
                        <i class="fas fa-address-book pr-1"></i> Add Contact
                    </button>
                </div>
            </div>
        </datatable>
        <!-- Modal for obtaining confirmation to delete the contact-->
        <base-modal id="datatable-modal"
                    title="Delete Contact"
                    :body="modalBody"
                    action-in-progress-label="Deleting..."
                    error-title="Ooops something went wrong :("
                    error-message="Please reload the page and try again."
                    :state="modalState"
                    @submit="onDeleteContactConfirmed"></base-modal>
    </div>

</template>

<script>
    import Datatable from "../components/Datatable";
    import {ContactsApi} from "../utils/contacts-api";
    import BaseModal from "../components/BaseModal";

    export default {
        components: {Datatable, BaseModal},
        data() {
            return {
                // For Datatable component
                columns: [
                    {label: 'ID #', name: 'id'},
                    {label: 'BIRTH DATE', name: 'birth_date'},
                    {label: 'NAME', name: 'first_name'},
                    {label: 'CITY', name: 'city'},
                    {label: 'EMAIL', name: 'email'},
                    {label: 'PHONE NUMBER', name: 'phone'},
                    {label: 'COMPANY', name: 'company_name'},
                    {label: null, name: 'last_name'}, // not displayed, for caching only
                    {label: null, name: 'function'}, // not displayed, for caching only
                    {label: null, name: 'company_address'}, // not displayed, for caching only
                ],
                apiUrl: 'api/contacts',

                // For modal component
                modalContact: null,
                modalState: 'hidden'
            }
        }, computed: {
            modalBody() {
                return this.modalContact ? `Are you sure you want to delete this contact: ${this.modalContact['first_name']} ${this.modalContact['last_name']} (${this.modalContact['email']}) ?` : '';
            }
        },
        methods: {
            onShowContactClicked(contact) {
                this.$router.push({name: 'contactShow', params: {id: contact.id, contact: contact}});
            },
            onEditContactClicked(contact) {
                this.$router.push({name: 'contactShow', params: {id: contact.id, contact: contact}});
            },
            onDeleteContactClicked(contact) {
                this.modalContact = contact;
                this.modalState = 'pendingConfirmation';
            },
            async onDeleteContactConfirmed() {
                const vm = this;
                this.modalState = 'actionInProgress';
                try {
                    await ContactsApi.delete(vm.modalContact.id);
                    this.modalState = 'hidden';
                    this.$refs.datatable.$emit('refresh');
                } catch (error) {
                    console.log(error.response);
                    this.modalState = 'error';
                }
            },
            onAddContactClicked() {
                this.$router.push({name: 'contactCreate'});
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