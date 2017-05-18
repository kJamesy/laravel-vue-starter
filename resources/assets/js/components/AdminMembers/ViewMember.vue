<template>
    <div class="mt-5">
        <i class="fa fa-spinner fa-spin" v-if="fetchingData"></i>

        <template v-if="! fetchingData">
            <div v-if="appUserHasPermission('update')">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th scope="row">First Name</th>
                            <td>{{ resource.first_name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Last Name</th>
                            <td>{{ resource.last_name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td>{{ resource.email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Phone</th>
                            <td>{{ resource.phone ? resource.phone : 'None Provided' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Username</th>
                            <td>{{ resource.username }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Active (Can Login)</th>
                            <td>{{ resource.active ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Member Since</th>
                            <td>{{ resource.created_at | dateToTheMinute }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Last Profile Update</th>
                            <td>{{ resource.updated_at | dateToTheMinute }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
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
                this.showResource();
            });
        },
        data() {
            return {
                fetchingData: true,
                resource: {id: '', first_name: '', last_name: '', phone: '', email: '', username: '', active: null, created_at: '', updated_at: ''},
            }
        },
        methods: {
            showResource() {
                this.appShowResource();
            },
        }
    }
</script>
