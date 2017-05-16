'use strict';

const AppEditcreenPlugin = {
    install(Vue, options) {

        Vue.mixin({
            mounted() {
                this.$nextTick(function() {
                });
            },
            data() {
                return {
                    id: this.$route.params.id,
                }
            },
            methods: {
                appGetResource() {
                    let vm = this;
                    let progress = vm.$Progress;

                    progress.start();

                    vm.$http.get(vm.appResourceUrl + '/' + vm.id + '/edit').then(function(response) {
                        if ( response.data && response.data.resource ) {
                            if ( typeof vm.resource === 'object' ) {
                                _.forEach(vm.resource, function (val, idx) {
                                    if ( response.data.resource[idx] )
                                        vm.$set(vm.resource, idx, response.data.resource[idx]);
                                });
                            }

                            progress.finish();
                        }
                        else {
                            vm.appGeneralErrorAlert();
                            progress.fail();
                        }

                        vm.fetchingData = false;
                    }, function(error) {
                        if ( error.status && error.status === 403 && error.data ) {
                            swal({ title: "Uh oh!", text: error.data.error, type: 'error', animation: 'slide-from-top'}, function(){
                                window.location.replace(vm.appUserHome);
                            });
                        }
                        else if ( error.status && error.status === 404 && error.data )
                            vm.appCustomErrorAlert(error.data.error);
                        else
                            vm.appGeneralErrorAlert();
                        progress.fail();
                        vm.fetchingData = false;
                    });
                },
                appUpdateResource() {
                    let vm = this;
                    let progress = vm.$Progress;

                    progress.start();
                    vm.fetchingData = true;
                    vm.appClearValidationErrors();

                    vm.$http.put(vm.appResourceUrl + '/' + vm.id, vm.resource).then(function(response) {
                        vm.appCustomSuccessAlert('Resource updated');
                        progress.finish();
                        vm.fetchingData = false;
                    }, function(error) {
                        if ( error.status && error.status === 422 && error.data ) {
                            vm.appValidationErrorAlert();

                            _.forEach(error.data, function(message, field) {
                                if ( _.includes(message[0], 'confirmation') )
                                    vm.$set(vm.validationErrors, 'password_confirmation', message[0]);
                                else
                                    vm.$set(vm.validationErrors, field, message[0]);
                            });
                        }
                        else if ( error.status && error.status === 404 )
                            vm.appCustomErrorAlert('Resource not found.');
                        else
                            vm.appGeneralErrorAlert();

                        progress.fail();
                        vm.fetchingData = false;
                    });
                },
                appDeleteResource() {
                    let vm = this;
                    let progress = vm.$Progress;

                    progress.start();
                    vm.fetchingData = true;

                    vm.$http.delete(vm.appResourceUrl + '/' + vm.id).then(function(response) {
                        if ( response.data && response.data.success ) {
                            progress.finish();
                            vm.fetchingData = false;

                            if ( typeof vm.listRoute === 'string' ) {
                                _.delay(function () {
                                    swal({
                                        title: "Excellent!",
                                        text: response.data.success,
                                        type: 'success',
                                        animation: 'slide-from-bottom'
                                    }, function () {
                                        vm.$router.replace({name: vm.listRoute});
                                    });
                                }, 200);
                            }
                        }
                    }, function(error) {
                        if ( error.status && error.status === 404 )
                            vm.appCustomErrorAlert('Resource not found.');
                        else if ( error.status && error.status === 403 && error.data )
                            vm.appCustomErrorAlert(error.data.error);
                        else
                            vm.appGeneralErrorAlert();

                        progress.fail();
                        vm.fetchingData = false;
                    });

                },
            },
        });

    }
};

export default AppEditcreenPlugin;