<template>
    <div class="mt-5">
        <i class="fa fa-spinner fa-spin" v-if="fetchingData"></i>
        <div class="table-responsive" v-if="! fetchingData">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th scope="row">First Name</th>
                    <td>{{ profile.first_name }}</td>
                </tr>
                <tr>
                    <th scope="row">Last Name</th>
                    <td>{{ profile.last_name }}</td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td>{{ profile.email }}</td>
                </tr>
                <tr>
                    <th scope="row">Username</th>
                    <td>{{ profile.username }}</td>
                </tr>
                <tr>
                    <th scope="row">Last Profile Update</th>
                    <td>{{ profile.updated_at | dateToTheMinute }}</td>
                </tr>
                </tbody>
            </table>
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
