<template>
    <div class="mt-5">
        <i class="fa fa-spinner fa-spin" v-if="fetchingData"></i>

        <template v-if="! fetchingData">
            <div v-if="appUserHasPermission('update')">
                <h3 class="mb-5">
                    <i class="fa fa-edit"></i> {{ resource.first_name }} {{ resource.last_name }}
                </h3>

                <form v-on:submit.prevent='updateResource' v-if="! fetchingData ">
                    <div class="form-group row" v-bind:class="validationErrors.first_name ? 'has-danger' : ''">
                        <label class="col-md-4 form-control-label" for="first_name">First Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="first_name" v-model.trim="resource.first_name" v-bind:class="validationErrors.first_name ? 'form-control-danger' : ''" autofocus>
                            <small class="form-control-feedback">
                                {{ validationErrors.first_name }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row" v-bind:class="validationErrors.last_name ? 'has-danger' : ''">
                        <label class="col-md-4 form-control-label" for="last_name">Last Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="last_name" v-model.trim="resource.last_name" v-bind:class="validationErrors.last_name ? 'form-control-danger' : ''">
                            <small class="form-control-feedback">
                                {{ validationErrors.last_name }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row" v-bind:class="validationErrors.phone ? 'has-danger' : ''">
                        <label class="col-md-4 form-control-label" for="phone">Phone</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="phone" placeholder="07..." v-model.trim="resource.phone" v-bind:class="validationErrors.phone ? 'form-control-danger' : ''">
                            <small class="form-control-feedback">
                                {{ validationErrors.phone }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row" v-bind:class="validationErrors.email ? 'has-danger' : ''">
                        <label class="col-md-4 form-control-label" for="email">Email</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="email" v-model.trim="resource.email" v-bind:class="validationErrors.email ? 'form-control-danger' : ''">
                            <small class="form-control-feedback">
                                {{ validationErrors.email }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row" v-bind:class="validationErrors.username ? 'has-danger' : ''">
                        <label class="col-md-4 form-control-label" for="username">Username</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="username" v-model.trim="resource.username" v-bind:class="validationErrors.username ? 'form-control-danger' : ''">
                            <small class="form-control-feedback">
                                {{ validationErrors.username }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row" v-bind:class="validationErrors.password ? 'has-danger' : ''">
                        <label class='col-md-4 form-control-label' for="password">Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" id="password" v-model="resource.password" v-bind:class="validationErrors.password ? 'form-control-danger' : ''">
                            <small class="form-control-feedback">
                                {{ validationErrors.password }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row" v-bind:class="validationErrors.password_confirmation ? 'has-danger' : ''">
                        <label class='col-md-4 form-control-label' for="password_confirmation">Password Confirmation</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" id="password_confirmation" v-model="resource.password_confirmation" v-bind:class="validationErrors.password_confirmation ? 'form-control-danger' : ''">
                            <small class="form-control-feedback">
                                {{ validationErrors.password_confirmation }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row checkbox mb-4">
                        <div class="col-md-8 offset-md-4">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" v-model="resource.active">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Active [Only active users can log in]</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary btn-outline-primary">Update</button>
                            <form action="" class="form-inline pull-right">
                                <label class="form-control-label mr-sm-2" for="more-options">More Options</label>
                                <select class="custom-select form-control mb-2 mb-sm-0" v-model="moreOption" id="more-options">
                                    <option v-for="option in moreOptions" v-bind:value="option.value" v-if="appUserHasPermission(option.value)">
                                        {{ option.text }}
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
            <div v-else="">
                <i class="fa fa-warning"></i> {{ appUnauthorisedErrorMessage }}
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$nextTick(function() {
                this.getResource();
            });
        },
        data() {
            return {
                fetchingData: true,
                resource: {id: '', first_name: '', last_name: '', phone: '', email: '', username: '', password: '', password_confirmation: '', active: null},
                validationErrors: {first_name: '', last_name: '', phone: '', email: '', username: '', password: '', password_confirmation: ''},
                listRoute: 'admin_members.index',
                moreOptions: [
                    { text: 'Select Option', value: '' },
                    { text: 'Delete Member', value: 'delete' },
                ],
                moreOption: ''
            }
        },
        methods: {
            getResource() {
                this.appGetResource();
            },
            updateResource() {
                this.appUpdateResource();
            },
            deleteResource() {
                this.appDeleteResource();
            },
        },
        watch: {
            moreOption(action) {
                let vm = this;

                if ( action.length ) {
                    if ( action === 'delete' && vm.appUserHasPermission(action) ) {
                        swal({title: 'Hey, are you sure about this?', type: "warning", showCancelButton: true, confirmButtonText: _.capitalize(action)}, function (confirmed) {
                            if (confirmed)
                                vm.deleteResource();
                            else
                                vm.moreOption = '';
                        });
                    }
                }
            },
        }
    }
</script>
