<template>
    <div>
        <nav class="navbar shadow-sm">
            <span class="navbar-brand">Contact Details</span>
            <router-link to="/" class="btn btn-outline-primary">Return</router-link>
        </nav>
        <div class="container-fluid p-4">
            <contact-form :mode="mode" :contact="contact" :errors="errors"
                          @submit="onSubmitClicked"></contact-form>
        </div>
    </div>
</template>

<script>
    import {ContactsApi} from "../utils/contacts-api";
    import ContactForm from "../components/ContactForm";
    import {EventBus} from "../utils/events-bus";

    export default {
        name: 'Contact',
        props: ['mode'],
        components: {ContactForm},
        data() {
            return {
                contact: {},
                errors: null
            }
        },
        async beforeRouteEnter(to, from, next) {
            let contactId = to.params.id;
            if (!contactId) {
                next();
            } else {
                try {
                    EventBus.$emit('loadingCalled');
                    let contact = (await ContactsApi.get(contactId)).data;
                    next(vm => vm.setContact(contact));
                } catch (error) {
                    next(vm => vm.onDataFetchingError(error.response));
                } finally {
                    EventBus.$emit('loadingHanged');
                }
            }

        },
        async beforeRouteUpdate(to, from, next) {
            this.resetData();
            let contactId = to.params.id;
            if (!contactId) {
                next();
            } else {
                try {
                    EventBus.$emit('loadingCalled');
                    let contact = await ContactsApi.get(contactId);
                    next(vm => vm.setContact(contact));
                } catch (error) {
                    next(vm => vm.onDataFetchingError(error.response));
                } finally {
                    EventBus.$emit('loadingHanged');
                }
            }
        },
        methods: {
            resetData() {
                this.contact = null;
                this.error = null;
            },
            setContact(contact) {
                this.contact = contact;
            },
            onDataFetchingError(error) {

            },
            onSubmitClicked() {
                switch (this.mode) {
                    case 'edit':
                        this.updateContact();
                        break;
                    case 'create':
                        this.createContact();
                        break;
                    default:
                        break;
                }
            },
            async updateContact() {
                try {
                    EventBus.$emit('loadingCalled');
                    await ContactsApi.update(this.contact);
                    this.onDataSavingSuccess();
                } catch (error) {
                    this.onDataSavingError(error.response);
                } finally {
                    EventBus.$emit('loadingHanged');
                }

            },
            async createContact() {
                try {
                    EventBus.$emit('loadingCalled');
                    await ContactsApi.create(this.contact);
                    this.onDataSavingSuccess();
                } catch (error) {
                    this.onDataSavingError(error.response);
                } finally {
                    EventBus.$emit('loadingHanged');

                }
            },
            onDataSavingSuccess(){
                this.$router.push({name:'contacts'});
            },
            onDataSavingError(error) {
                this.errors = error.data;
            },

        }
    }
</script>

<style scoped>
    nav {
        background-color: rgb(248,249,251);
        color: rgb(147,148,150);
    }
</style>