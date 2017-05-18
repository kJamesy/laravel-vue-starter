<template>
    <div class="mt-5">
        <i class="fa fa-spinner fa-spin" v-if="fetchingData"></i>

        <template v-if="! fetchingData">
            <div v-if="appUserHasPermissionOnUser('read', resource)" class="table-responsive">
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
                            <th scope="row">Username</th>
                            <td>{{ resource.username }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Active (Can Login)</th>
                            <td>{{ resource.active ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Role</th>
                            <td>
                                {{ resource.is_super_admin ? 'Super Admin' : 'User' }}
                                <span v-if="resource.is_super_admin" class="text-warning"> <i class="fa fa-certificate"></i> </span>
                            </td>
                        </tr>
                        <tr v-if="permissions">
                            <th scope="row">Permissions</th>
                            <td>{{ permissions }}</td>
                        </tr>
                        <tr>
                            <th scope="row">User Since</th>
                            <td>{{ resource.created_at | dateToTheMinute }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Last Profile Update</th>
                            <td>{{ resource.updated_at | dateToTheMinute }}</td>
                        </tr>
                    </tbody>
                </table>
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
                resource: {id: '', first_name: '', last_name: '', phone: '', email: '', username: '', active: null, created_at: '', updated_at: '', is_super_admin: null, meta: {}},
                listRoute: 'admin_users.index',
            }
        },
        computed: {
            permissions() {
                let vm = this;
                let permissionsMarkup = '';

                if ( vm.resource.meta ) {
                    let meta = JSON.parse(vm.resource.meta);

                    _.forEach(meta, function(value, key) {
                       if ( value === true )
                           permissionsMarkup += _.upperFirst( _.replace(key, '_', ' ') ) + '; ';
                    });
                }

                return permissionsMarkup;
            }
        },
        methods: {
            showResource() {
                this.appShowResource();
            }
        },
    }
</script>
