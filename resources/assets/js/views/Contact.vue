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
                            <div class="form-group">
                                <label for="email">Email Address*</label>
                                <input type="email"
                                       :class="{'form-control': true, 'is-invalid': this.errors && this.errors.errors.email}"
                                       id="email"
                                       name="email"
                                       aria-describedby="emailHelp"
                                       v-model="contact.email">
                                <div v-if="this.errors && this.errors.errors.email" class="invalid-feedback">
                                    <span class="block" v-for="err in errors.errors.email">{{err}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name*</label>
                                <input type="text"
                                       :class="{'form-control': true, 'is-invalid': this.errors && this.errors.errors.first_name}"
                                       id="first_name"
                                       name="first_name"
                                       v-model="contact.first_name">
                                <div v-if="this.errors && this.errors.errors.first_name" class="invalid-feedback">
                                    <span class="block" v-for="err in errors.errors.first_name">{{err}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name*</label>
                                <input type="text"
                                       :class="{'form-control': true, 'is-invalid': this.errors && this.errors.errors.last_name}"
                                       id="last_name"
                                       name="last_name"
                                       v-model="contact.last_name">
                                <div v-if="this.errors && this.errors.errors.last_name" class="invalid-feedback">
                                    <span class="block" v-for="err in errors.errors.last_name">{{err}}</span>
                                </div>
                            </div>
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
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control"
                                       id="city"
                                       name="city"
                                       v-model="contact.city">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number*</label>
                                <input type="text"
                                       :class="{'form-control': true, 'is-invalid': this.errors && this.errors.errors.phone}"
                                       id="phone"
                                       name="phone"
                                       v-model="contact.phone">
                                <div v-if="this.errors && this.errors.errors.phone" class="invalid-feedback">
                                    <span class="block" v-for="err in errors.errors.phone">{{err}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="function">Function</label>
                                <input type="text" class="form-control"
                                       id="function"
                                       name="function"
                                       v-model="contact.function">
                            </div>
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
                            <div class="form-group">
                                <label for="company_name">Name</label>
                                <input type="text" class="form-control"
                                       id="company_name"
                                       name="company_name"
                                       v-model="contact.company_name">
                            </div>
                            <div class="form-group">
                                <label for="company_address">Address</label>
                                <input type="text" class="form-control"
                                       id="company_address"
                                       name="company_address"
                                       v-model="contact.company_address">
                            </div>
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

    export default {
        name: 'Contact',
        props: ['mode'],
        components: {BaseFormSubmit, Datepicker},
        data() {
            return {
                contact: {},
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