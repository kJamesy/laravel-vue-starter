<template>
    <div class="settings-profile">
        <div class="panel panel-default">
            <div class="panel-heading"><h3>Change Your Password</h3></div>

            <div class="panel-body">
                <i class="fa fa-spinner fa-spin fa-4x fa-fw" v-if="fetchingData"></i>
                <form v-on:submit.prevent='updatePassword' v-if="! fetchingData">
                    <div class="form-group" v-bind:class="validation.current_password ? 'has-error' : ''">
                        <label for="current_password">Current Password <span v-if="validation.current_password" class="text-danger">{{ validation.current_password }}</span> </label>
                        <input type="password" class="form-control" id="current_password" placeholder="Current Password" v-model.trim="profile.current_password">
                    </div>
                    <div class="form-group" v-bind:class="validation.password ? 'has-error' : ''">
                        <label for="password">New Password <span v-if="validation.password" class="text-danger">{{ validation.password }}</span> </label>
                        <input type="password" class="form-control" id="password" placeholder="New Password" v-model.trim="profile.password">
                    </div>
                    <div class="form-group" v-bind:class="validation.password_confirmation ? 'has-error' : ''">
                        <label for="password_confirmation">Password Confirmation <span v-if="validation.password_confirmation" class="text-danger">{{ validation.password_confirmation }}</span> </label>
                        <input type="password" class="form-control" id="password_confirmation" placeholder="Password Confirmation" v-model.trim="profile.password_confirmation">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$nextTick(function() {
                this.resourceUrl = settingsLinks.baseUri;
                this.$Progress.start();
                this.goTime();
            });
        },
        data() {
            return {
                fetchingData: true,
                profile: {current_password: '', password: '', password_confirmation: ''},
                validation: {current_password: '', password: '', password_confirmation: ''}
            }
        },
        methods: {
            goTime() {
                let vm = this;
                _.delay(function() {
                    vm.$Progress.finish();
                    vm.fetchingData = false;
                }, 400);
            },
            updatePassword() {
                let vm = this;
                let progress = vm.$Progress;
                progress.start();
                vm.fetchingData = true;
                vm.clearValidationErrors();

                vm.$http.put(vm.resourceUrl + '/update-password', vm.profile).then(function(response) {
                    swal({ title: "Success", text: 'Password updated', type: 'success', animation: 'slide-from-bottom', timer: 3000 });
                    progress.finish();
                    vm.fetchingData = false;
                }, function(error) {
                    if ( error.status && error.status == 422 && error.data ) {
                        swal({title: "An Error Occurred", text: 'Please check the highlighted fields and try again', type: 'error', animation: 'slide-from-top', timer: 3000});

                        _.forEach(error.data, function(message, field) {
                            if ( _.includes(message[0], 'confirmation') )
                                vm.$set(vm.validation, 'password_confirmation', message[0]);
                            else
                                vm.$set(vm.validation, field, message[0]);
                        });
                    }

                    else if ( error.status && error.status == 404 )
                        swal({ title: "An Error Occurred", text: error.data.error, type: 'error', animation: 'slide-from-top'});
                    else
                        swal('An Error Occurred', 'Please refresh the page and try again.', 'error');
                    progress.fail();
                    vm.fetchingData = false;
                });
            },
            clearValidationErrors() {
                this.$set(this, 'validation', {current_password: '', password: '', password_confirmation: ''});
            }
        }
    }
</script>
