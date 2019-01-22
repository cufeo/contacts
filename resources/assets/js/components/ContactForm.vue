<template>
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
                        <label for="birth_date">Birth Date</label>
                        <div class="input-group date datepicker">
                            <input type="text"
                                   :class="{'form-control': true, 'is-invalid': this.errors && this.errors.errors.birth_date}"
                                   id="birth_date"
                                   name="birth_date"
                                   v-model="contact.birth_date">
                            <div class="input-group-addon input-group-append">
                                <div class="input-group-text far fa-calendar"></div>
                            </div>
                            <div v-if="this.errors && this.errors.errors.birth_date" class="invalid-feedback">
                                <span class="block" v-for="err in errors.errors.birth_date">{{err}}</span>
                            </div>
                        </div>
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
                    <base-form-submit @submit="onFormSubmit" :submit-button-text="mode === 'edit' ? 'Update' : 'Create'"
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
</template>

<script>
    import BaseFormSubmit from "./BaseFormSubmit";
    import {validationMixin} from 'vuelidate';
    import {capitlize} from "../utils/string-capitalizer";


    export default {
        name: 'ContactForm',
        components: {BaseFormSubmit},
        props: ['mode', 'contact', 'errors'],
        methods: {
            onFormSubmit() {
                this.$emit('submit');
            }
        },
        mixins: [validationMixin],
        mounted() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
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