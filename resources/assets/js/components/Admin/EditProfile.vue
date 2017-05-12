<template>
    <div class="mt-5">
        <i class="fa fa-spinner fa-spin" v-if="fetchingData"></i>

        <form v-on:submit.prevent='updateProfile' v-if="! fetchingData">
            <div class="form-group row" v-bind:class="validationErrors.first_name ? 'has-danger' : ''">
                <label class="col-md-4 form-control-label" for="first_name">First Name</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="first_name" v-model.trim="profile.first_name" v-bind:class="validationErrors.first_name ? 'form-control-danger' : ''" autofocus>
                    <small class="form-control-feedback">
                        {{ validationErrors.first_name }}
                    </small>
                </div>
            </div>
            <div class="form-group row" v-bind:class="validationErrors.last_name ? 'has-danger' : ''">
                <label class="col-md-4 form-control-label" for="last_name">Last Name</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="last_name" v-model.trim="profile.last_name" v-bind:class="validationErrors.last_name ? 'form-control-danger' : ''">
                    <small class="form-control-feedback">
                        {{ validationErrors.last_name }}
                    </small>
                </div>
            </div>
            <div class="form-group row" v-bind:class="validationErrors.username ? 'has-danger' : ''">
                <label class="col-md-4 form-control-label" for="username">Username</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="username" v-model.trim="profile.username" v-bind:class="validationErrors.username ? 'form-control-danger' : ''">
                    <small class="form-control-feedback">
                        {{ validationErrors.username }}
                    </small>
                </div>
            </div>
            <div class="form-group row" v-bind:class="validationErrors.email ? 'has-danger' : ''">
                <label class="col-md-4 form-control-label" for="email">Email</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="email" v-model.trim="profile.email" v-bind:class="validationErrors.email ? 'form-control-danger' : ''">
                    <small class="form-control-feedback">
                        {{ validationErrors.email }}
                    </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary btn-outline-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$nextTick(function() {
                this.fetchProfile();
            });
        },
        data() {
            return {
                fetchingData: true,
                profile: {},
                validationErrors: {first_name: '', last_name: '', username: '', email: ''}
            }
        },
        methods: {
            fetchProfile() {
                let vm = this;
                let progress = vm.$Progress;

                progress.start();

                vm.$http.get(vm.appResourceUrl + '/show').then(function(response) {
                    if ( response.data )
                        vm.profile = response.data;
                    vm.fetchingData = false;
                    progress.finish();
                }, function(error) {
                    vm.appGeneralErrorAlert();
                    progress.fail();
                });
            },
            updateProfile() {
                let vm = this;
                let progress = vm.$Progress;
                progress.start();
                vm.fetchingData = true;
                vm.appClearValidationErrors();

                vm.$http.put(vm.appResourceUrl + '/' + vm.profile.id, vm.profile).then(function(response) {
                    vm.appCustomSuccessAlert('Profile updated');
                    progress.finish();
                    vm.fetchingData = false;
                }, function(error) {
                    if ( error.status && error.status === 422 && error.data ) {
                        vm.appValidationErrorAlert();

                        _.forEach(error.data, function(message, field) {
                            vm.$set(vm.validationErrors, field, message[0]);
                        });
                    }
                    else if ( error.status && error.status === 404 )
                        vm.appCustomErrorAlert(error.data.error);
                    else
                        vm.appGeneralErrorAlert();
                    progress.fail();
                    vm.fetchingData = false;
                });
            }
        }

    }
</script>
