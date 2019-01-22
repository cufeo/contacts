<template>
    <div class="container-fluid app-datatable-container">
        <datatable
                :columns="columns"
                :resource="resource"
                :delete-confirmation="deleteConfirmation">
            <div class="row justify-content-between app-header">
                <h6 class="col"><strong>List Of Contacts</strong></h6>
                <div class="col text-right">
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
                    {label: null, name: 'last_name'} // not visible
                ],
                apiUrl: 'api/contacts',
                deleteConfirmation: {
                    title: 'Delete Contact',
                    body: (record) => `Are you sure you want to delete this contact: ${record['first_name']} ${record['last_name']} (${record['email']}) ?`
                },
                resource: {
                    api: ContactsApi.endpoint,
                    // Must return a promise
                    async delete(recordId) {
                        return await ContactsApi.delete(recordId);
                    },
                    edit: (contactId) => this.$router.push({ name: 'contactShow', params: { id: contactId } }),
                    // edit: (contactId) => window.location.href = `contacts/${contactId}/edit`,
                    show: (contactId) => this.$router.push({ name: 'contactShow', params: { id: contactId } }),
                    create: () => this.$router.push({ name: 'contactCreate'}),
                }
            }

        },
        methods: {
            onAddContactClicked() {
                this.resource.create();
            }
        }
    }
</script>
<style>
    .app-datatable-container {
        padding-top: 1rem;
        padding-bottom: 1rem;

    }
</style>