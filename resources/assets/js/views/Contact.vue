<template>
    <div>
        <nav class="navbar shadow-sm">
            <span class="navbar-brand">Contact Details</span>
            <router-link to="/" class="btn btn-outline-primary">Return</router-link>
        </nav>
        <div class="container-fluid p-4">
            <div class="row justify-content-between">
                <div class="col">
                    <div class="card border-top-2">
                        <div class="card-body">
                            <base-input id="email" label="Email Address*" v-model="contact.email" :errors="getErrors('email')"></base-input>
                            <base-input id="first_name" label="First Name*" v-model="contact.first_name" :errors="getErrors('first_name')"></base-input>
                            <base-input id="last_name" label="Last Name*" v-model="contact.last_name" :errors="getErrors('last_name')"></base-input>
                            <div class="form-group">
                                <label>Birth Date</label>
                                <datepicker input-class="form-control bg-white" calendar-button-icon="far fa-calendar" :bootstrap-styling="true"
                                            v-model="contact.birth_date" name="birth_date"
                                            :format="formatBirthDate">
                                    <div slot="afterDateInput" class="input-group-append">
                                        <div class="input-group-text far fa-calendar"></div>
                                    </div>
                                </datepicker>
                            </div>
                            <base-input id="city" label="City" v-model="contact.city" :errors="getErrors('city')"></base-input>
                            <base-input id="phone" label="Phone Number*" v-model="contact.phone" :errors="getErrors('phone')"></base-input>
                            <base-input id="function" label="Function" v-model="contact.function" :errors="getErrors('function')"></base-input>
                            <p>
                                * Required Fields
                            </p>
                        </div>
                        <div class="card-footer">
                            <base-form-submit @submit="onFormSubmit"
                                              :submit-button-text="mode === 'edit' ? 'Update' : 'Create'"
                                              :terms-accepted="mode === 'edit'"></base-form-submit>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4 app-company-title">Company</h5>
                            <base-input id="company_name" label="Name" v-model="contact.company_name" :errors="getErrors('company_name')"></base-input>
                            <base-input id="company_address" label="Address" v-model="contact.company_address" :errors="getErrors('company_address')"></base-input>
                        </div>
                        <div class="card-footer pb-5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {ContactsApi} from "../utils/contacts-api";
    import {EventBus} from "../utils/event-bus";
    import BaseFormSubmit from "../components/BaseFormSubmit";
    import Datepicker from "vuejs-datepicker";
    import * as moment from 'moment';
    import {capitlize} from "../utils/string-capitalizer";
    import BaseInput from "../components/BaseInput";

    export default {
        name: 'Contact',
        props: ['mode'],
        components: {BaseFormSubmit, Datepicker, BaseInput},
        data() {
            return {
                contact: {},
                state: 'pendingAction', // either: 'pendingAction', 'actionInProgress', 'success', 'error'
                errors: null
            }
        },
        async beforeRouteEnter(to, from, next) {
            let contactId = to.params.id;
            let cachedContact = to.params.cachedContact;
            if (cachedContact) {
                next(vm => vm.contact = cachedContact);
            } else if (contactId) {
                try {
                    EventBus.$emit('show-app-loading');
                    let contact = (await ContactsApi.get(contactId)).data;
                    next(vm => vm.contact = contact);
                } catch (error) {
                    next(vm => vm.onDataFetchingError(error.response));
                } finally {
                    EventBus.$emit('hide-app-loading');
                }
            } else {
                next();
            }
        },
        async beforeRouteUpdate(to, from, next) {
            this.contact = null;
            this.error = null;
            let contactId = to.params.id;
            let cachedContact = to.params.cachedContact;
            if (cachedContact) {
                next(vm => vm.contact = cachedContact);
            } else if (contactId) {
                try {
                    EventBus.$emit('show-app-loading');
                    let contact = (await ContactsApi.get(contactId)).data;
                    next(vm => vm.contact = contact);
                } catch (error) {
                    next(vm => vm.onDataFetchingError(error.response));
                } finally {
                    EventBus.$emit('hide-app-loading');
                }
            } else {
                next();
            }
        },
        computed: {
            firstName() {
                return this.contact.first_name;
            },
            lastName() {
                return this.contact.last_name;
            }

        },
        watch: {
            firstName(newVal) {
                this.contact.first_name = capitlize(newVal);
            },
            lastName(newVal) {
                this.contact.last_name = capitlize(newVal);
            }
        },
        methods: {
            onFormSubmit() {
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
                    EventBus.$emit('show-app-loading');
                    await ContactsApi.update(this.contact);
                    this.onDataSavingSuccess();
                } catch (error) {
                    this.onDataSavingError(error.response);
                } finally {
                    EventBus.$emit('hide-app-loading');
                }

            },
            async createContact() {
                try {
                    EventBus.$emit('show-app-loading');
                    await ContactsApi.create(this.contact);
                    this.onDataSavingSuccess();
                } catch (error) {
                    this.onDataSavingError(error.response);
                } finally {
                    EventBus.$emit('hide-app-loading');

                }
            },
            onDataSavingSuccess() {
                this.$router.push({name: 'contacts'});
            },
            onDataSavingError(error) {
                this.errors = error.data;
            },
            onDataFetchingError(error) {
            },
            formatBirthDate(date) {
                return moment(date).format("YYYY-MM-DD").toString();
            },
            // Helpers
            getErrors(field) {
                return this.errors && this.errors.errors[field] ? this.errors.errors[field] : null
            }
        }
    }
</script>

<style scoped>
    nav {
        background-color: rgb(248, 249, 251);
        color: rgb(107, 108, 110);
    }

    .card {
        border-top-width: 0.2rem;
    }

    .app-company-title {
        opacity: 0.65;
    }
</style>