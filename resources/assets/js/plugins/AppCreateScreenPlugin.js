'use strict';

const AppCreatecreenPlugin = {
    install(Vue, options) {

        Vue.mixin({
            mounted() {
                this.$nextTick(function() {
                });
            },
            data() {
                return {

                }
            },
            methods: {
                appGoTime() {
                    let vm = this;
                    let progress = vm.$Progress;

                    progress.start();

                    _.delay(function() {
                        progress.finish();
                        vm.fetchingData = false;
                    }, 500);
                },
                appCreateResource() {
                    let vm = this;
                    let progress = vm.$Progress;

                    progress.start();
                    vm.fetchingData = true;
                    vm.appClearValidationErrors();

                    vm.$http.post(vm.appResourceUrl, vm.resource).then(function(response) {
                        vm.appCustomSuccessAlert('Resource created');

                        vm.appClearResource();
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
                        else if ( error.status && error.status === 403 && error.data )
                            vm.appCustomErrorAlert(error.data.error);
                        else
                            vm.appGeneralErrorAlert();

                        progress.fail();
                        vm.fetchingData = false;
                    });
                },
                appClearResource() {
                    let vm = this;
                    if ( typeof vm.resource === 'object' ) {
                        _.forEach(vm.resource, function (val, idx) {
                            if ( idx === 'active' )
                                vm.$set(vm.resource, idx, 1);
                            else
                                vm.$set(vm.resource, idx, '');
                        });
                    }
                },
                appClearValidationErrors() {
                    let vm = this;
                    if ( typeof vm.validationErrors === 'object' ) {
                        _.forEach(vm.validationErrors, function (err, attr) {
                            vm.$set(vm.validationErrors, attr, '');
                        });
                    }
                },
            },
            watch: {

            },
        });

    }
};

export default AppCreatecreenPlugin;