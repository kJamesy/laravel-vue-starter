'use strict';

const AppShowScreenPlugin = {
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
                appShowResource() {
                    let vm = this;
                    let progress = vm.$Progress;

                    progress.start();

                    vm.$http.get(vm.appResourceUrl + '/' + vm.id).then(function (response) {
                        if (response.data && response.data.resource) {
                            if (typeof vm.resource === 'object') {
                                _.forEach(vm.resource, function (val, idx) {
                                    if (response.data.resource[idx])
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
                    }, function (error) {
                        if (error.status && error.status === 403 && error.data) {
                            swal({
                                title: "Uh oh!",
                                text: error.data.error,
                                type: 'error',
                                animation: 'slide-from-top'
                            }, function () {
                                window.location.replace(vm.appUserHome);
                            });
                        }
                        else if (error.status && error.status === 404 && error.data)
                            vm.appCustomErrorAlert(error.data.error);
                        else
                            vm.appGeneralErrorAlert();
                        progress.fail();
                        vm.fetchingData = false;
                    });
                },
            }
        });

    }
};

export default AppShowScreenPlugin;