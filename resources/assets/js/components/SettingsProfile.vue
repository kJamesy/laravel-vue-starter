<template>
    <div class="settings-profile">
        <div class="panel panel-default">
            <div class="panel-heading"><h3>Edit Profile</h3></div>

            <div class="panel-body">
                <i class="fa fa-spinner fa-spin fa-4x fa-fw" v-if="fetchingData"></i>
                <form v-on:submit.prevent='updateProfile' v-if="! fetchingData">
                    <div class="form-group" v-bind:class="validation.first_name ? 'has-error' : ''">
                        <label for="first_name">First Name <span v-if="validation.first_name" class="text-danger">{{ validation.first_name }}</span></label>
                        <input type="text" class="form-control" id="first_name" placeholder="First Name" v-model.trim="profile.first_name">
                    </div>
                    <div class="form-group" v-bind:class="validation.last_name ? 'has-error' : ''">
                        <label for="last_name">Last Name <span v-if="validation.last_name" class="text-danger">{{ validation.last_name }}</span></label>
                        <input type="text" class="form-control" id="last_name" placeholder="Last Name" v-model.trim="profile.last_name">
                    </div>
                    <div class="form-group" v-bind:class="validation.username ? 'has-error' : ''">
                        <label for="username">Username <span v-if="validation.username" class="text-danger">{{ validation.username }}</span></label>
                        <input type="text" class="form-control" id="username" placeholder="Username" v-model.trim="profile.username">
                    </div>
                    <div class="form-group" v-bind:class="validation.email ? 'has-error' : ''">
                        <label for="email">Email <span v-if="validation.email" class="text-danger">{{ validation.email }}</span> </label>
                        <input type="text" class="form-control" id="email" placeholder="Email" v-model.trim="profile.email">
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
                this.fetchProfile();
            });
        },
        data() {
            return {
                fetchingData: true,
                profile: {},
                validation: {first_name: '', last_name: '', username: '', email: ''}
            }
        },
        methods: {
            fetchProfile() {
                let vm = this;
                let progress = vm.$Progress;

                vm.$http.get(vm.resourceUrl + '/show').then(function(response) {
                    if ( response.data )
                        vm.profile = response.data;
                    vm.fetchingData = false;
                    progress.finish();
                }, function(error) {
                    swal('An Error Occurred', 'Please refresh the page and try again', 'error');
                    progress.fail();
                });
            },
            updateProfile() {
                let vm = this;
                let progress = vm.$Progress;
                progress.start();
                vm.fetchingData = true;
                vm.clearValidationErrors();

                vm.$http.put(vm.resourceUrl + '/' + vm.profile.id, vm.profile).then(function(response) {
                    swal({ title: "Success", text: 'Profile updated', type: 'success', animation: 'slide-from-bottom', timer: 3000 });
                    progress.finish();
                    vm.fetchingData = false;
                }, function(error) {
                    if ( error.status && error.status == 422 && error.data ) {
                        swal({title: "An Error Occurred", text: 'Please check the highlighted fields and try again', type: 'error', animation: 'slide-from-top', timer: 3000});

                        _.forEach(error.data, function(message, field) {
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
                this.$set(this, 'validation', {first_name: '', last_name: '', username: '', email: ''});
            }
        }

    }
</script>
