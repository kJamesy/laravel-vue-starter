<template>
    <div class="mt-5">
        <i class="fa fa-spinner fa-spin" v-if="fetchingData"></i>
        <div v-if="! fetchingData">
            Welcome back, {{ profile.name }}
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$nextTick(function() {
                this.$Progress.start();
                this.fetchProfile();
            });
        },
        data() {
            return {
                fetchingData: true,
                profile: {}
            }
        },
        methods: {
            fetchProfile() {
                let vm = this;
                let progress = vm.$Progress;

                vm.$http.get(vm.appResourceUrl + '/show').then(function(response) {
                    if ( response.data )
                        vm.profile = response.data;
                    vm.fetchingData = false;
                    progress.finish();
                }, function(error) {
                    vm.appGeneralErrorAlert();
                    progress.fail();
                });
            }
        }
    }
</script>
