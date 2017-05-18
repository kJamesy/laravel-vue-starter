'use strict';

const AppHelpers = {
    install(Vue, options) {

        Vue.mixin({
            mounted() {
                this.$nextTick(function() {
                    this.appInitialiseRouteParams();
                });
            },
            data() {
                return {
                    appCurrentRouteIdParam: '',
                }
            },
            methods: {
                appInitialiseRouteParams() {
                    let vm = this;
                    let route = vm.$route;

                    if ( route && route.params && route.params.id )
                        vm.appCurrentRouteIdParam = route.params.id;
                }
            },
            watch: {
                '$route'(route) {
                    let vm = this;
                    vm.appCurrentRouteIdParam = ( route && route.params && route.params.id ) ? route.params.id : '';
                }
            }
        });
    }
};

export default AppHelpers;