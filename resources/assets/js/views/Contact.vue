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
                            <base-input id="email" type="email" label="Email Address*" v-model="contact.email" :errors="getErrors('email')"></base-input>
                            <base-input id="first_name" type="text" label="First Name*" capitalize="true" v-model="contact.first_name" :errors="getErrors('first_name')"></base-input>
                            <base-input id="last_name" type="text" label="Last Name*" capitalize="true" v-model="contact.last_name" :errors="getErrors('last_name')"></base-input>
                            <div class="form-group">
                                <label>Birth Date</label>
                                <datepicker input-class="form-control bg-white" calendar-button-icon="far fa-calendar" :bootstrap-styling="true"
                                            v-model="contact.birth_date" name="birth_date"
                                            format="yyyy-MM-dd">
                                    <div slot="afterDateInput" class="input-group-append">
                                        <div class="input-group-text far fa-calendar"></div>
                                    </div>
                                </datepicker>
                            </div>
                            <base-input id="city" type="text" label="City" v-model="contact.city" :errors="getErrors('city')"></base-input>
                            <base-input id="phone" type="text" label="Phone Number*" v-model="contact.phone" :errors="getErrors('phone')"></base-input>
                            <base-input id="function" type="text" label="Function" v-model="contact.function" :errors="getErrors('function')"></base-input>
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
                            <base-input type="text" id="company_name" label="Name" v-model="contact.company_name" :errors="getErrors('company_name')"></base-input>
                            <base-input type="text" id="company_address" label="Address" v-model="contact.company_address" :errors="getErrors('company_address')"></base-input>
                        </div>
                        <div class="card-footer pb-5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <base-loading :show="loading"></base-loading>
    </div>
</template>

<script>
    import {ContactsApi} from "../utils/contacts-api";
    import BaseFormSubmit from "../components/BaseFormSubmit";
    import Datepicker from "vuejs-datepicker";
    import BaseInput from "../components/BaseInput";
    import BaseLoading from "../components/BaseLoading";

    export default {
        name: 'Contact',
        props: ['mode'],
        components: {BaseFormSubmit, Datepicker, BaseInput, BaseLoading},
        data() {
            return {
                contact: {},
                loading: false,
                errors: null
            }
        },
        async beforeRouteEnter(to, from, next) {
            let contactId = to.params.id;
            let contact = to.params.contact;
            if (contact) {
                next(vm => vm.contact = contact);
            } else if (contactId) {
                try {
                    let contact = (await ContactsApi.get(contactId)).data;
                    next(vm => vm.contact = contact);
                } catch (error) {
                    next(vm => vm.onDataFetchingError(error.response));
                }
            } else {
                next();
            }
        },
        async beforeRouteUpdate(to, from, next) {
            this.contact = null;
            this.error = null;
            let contactId = to.params.id;
            let contact = to.params.contact;
            if (contact) {
                next(vm => vm.contact = contact);
            } else if (contactId) {
                try {
                    this.loading = true;
                    let contact = (await ContactsApi.get(contactId)).data;
                    next(vm => vm.contact = contact);
                } catch (error) {
                    next(vm => vm.onDataFetchingError(error.response));
                } finally {
                    this.loading = false;
                }
            } else {
                next();
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
                    this.loading = true;
                    await ContactsApi.update(this.contact);
                    this.onDataSavingSuccess();
                } catch (error) {
                    this.onDataSavingError(error.response);
                } finally {
                    this.loading = false;
                }

            },
            async createContact() {
                try {
                    this.loading = true;
                    await ContactsApi.create(this.contact);
                    this.onDataSavingSuccess();
                } catch (error) {
                    this.onDataSavingError(error.response);
                } finally {
                    this.loading = false;

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