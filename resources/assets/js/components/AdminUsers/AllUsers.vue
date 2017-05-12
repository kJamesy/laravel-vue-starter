<template>
    <div class="mt-3">
        <i class="fa fa-spinner fa-spin" v-if="fetchingData"></i>
        <div v-if="! fetchingData">
            <div v-if="appUserHasPermission('read')">
                <a href="#" v-on:click.prevent="exportAll" class="btn btn-link pull-right"><i class="fa fa-arrow-circle-o-down"></i></a>
                <div class="clearfix mb-2"></div>
                <form v-on:submit.prevent="appDoSearch">
                    <div class="form-group">
                        <input type="text" v-model.trim="appSearchText" placeholder="Search" class="form-control" />
                    </div>
                </form>
                <div class="mt-md-4 mb-md-4">
                    <form class="form-inline pull-left" v-if="appSelectedResources.length">
                        <label class="form-control-label mr-sm-2" for="quick-edit">Options</label>
                        <select class="custom-select form-control mb-2 mb-sm-0 mr-sm-5" v-model="appQuickEditText" id="quick-edit">
                            <option v-for="option in quickEditOptions" v-bind:value="option.value" v-if="appUserHasPermission(option.value)">
                                {{ option.text }}
                            </option>
                        </select>
                    </form>
                    <form class="form-inline pull-right">
                        <label class="form-control-label mr-sm-2" for="records_per_page">
                            Per Page
                        </label>
                        <select class="custom-select form-control mb-2 mb-sm-0" v-model="appPerPage" id="records_per_page">
                            <option v-for="option in appPerPageOptions" v-bind:value="option.value">
                                {{ option.text }}
                            </option>
                        </select>
                    </form>
                    <div class="clearfix"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="pointer-cursor">
                                <th class="normal-cursor" v-if="appUserHasPermission('update')">
                                    <label class="custom-control custom-checkbox mr-0">
                                        <input type="checkbox" class="custom-control-input" v-model="selectAll">
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                </th>
                                <th v-on:click.prevent="appChangeSort('first_name')">Name <span v-html="appGetSortMarkup('first_name')"></span></th>
                                <th v-on:click.prevent="appChangeSort('email')">Email <span v-html="appGetSortMarkup('email')"></span></th>
                                <th v-on:click.prevent="appChangeSort('username')">Username <span v-html="appGetSortMarkup('username')"></span></th>
                                <th v-on:click.prevent="appChangeSort('active')" >Active <span v-html="appGetSortMarkup('active')"></span></th>
                                <th v-on:click.prevent="appChangeSort('updated_at')" >Updated <span v-html="appGetSortMarkup('updated_at')"></span></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in orderedAppResources">
                                <td v-if="appUserHasPermission('update')">
                                    <template v-if="appUserHasPermissionOnUser('update', user)">
                                        <label class="custom-control custom-checkbox mr-0">
                                            <input type="checkbox" class="custom-control-input" v-model="appSelectedResources" v-bind:value="user.id">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    </template>
                                </td>
                                <td>{{ user.name }} <small v-if="user.is_super_admin" class="text-warning" title="Super Admin" data-toggle="tooltip"> <i class="fa fa-certificate"></i> </small></td>
                                <td>{{ user.email }}</td>
                                <td> {{ user.username }}</td>
                                <td v-html="appActiveMarkup(user.active)"></td>
                                <td>{{ user.updated_at | dateToTheDay }}</td>
                                <td>
                                    <template v-if="appUserHasPermissionOnUser('update', user)">
                                        <router-link v-bind:to="{ name: 'admin_users.edit', params: { id: user.id }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-pencil-square-o"></i></router-link>
                                    </template>
                                    <template v-if="appUserIsCurrentUser(user)">
                                        <a v-bind:href="appUserHome" class="btn btn-sm btn-outline-warning" data-toggle="tooltip" title="Yours truly :)"><i class="fa fa-pencil-square-o"></i></a>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <pagination :pagination="appPagination" :callback="fetchResources" :options="appPaginationOptions"></pagination>
                Page {{ appPagination.current_page }} of {{ appPagination.last_page }} [{{ appPagination.total }} records]

            </div>
            <div v-if="! appUserHasPermission('read')">
                <i class="fa fa-warning"></i> {{ appUnauthorisedErrorMessage }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$nextTick(function() {
                this.appInitialiseSettings();
                this.appInitialiseTooltip();
                this.fetchResources();
            });
        },
        data() {
            return {
                fetchingData: true,
                quickEditOptions: [
                    { text: 'Select Option', value: '' },
                    { text: 'Export', value: 'export' },
                    { text: 'Activate', value: 'activate' },
                    { text: 'Deactivate', value: 'deactivate' },
                    { text: 'Delete', value: 'delete' }
                ],
                quickEditOption: '',
            }
        },
        computed: {
            selectAll: {
                get() {
                    return this.appResourcesIds ? this.appSelectedResources.length === this.appResourcesIds.length : false;
                },
                set(value) {
                    let vm = this;
                    let resourcesIds = _.cloneDeep(vm.appResourcesIds);
                    let selected = [];

                    if ( value ) {
                        _.forEach(resourcesIds, function(id) {
                            if ( id !== vm.appUser.id )
                                selected.push(id);
                        });
                    }

                    this.appSelectedResources = selected;
                }
            }
        },
        methods: {
            fetchResources(orderAttr, orderToggle) {
                this.appFetchResources(this, orderAttr, orderToggle);
            },
            quickEditResources() {
                let vm = this;
                let action = _.toLower(vm.appQuickEditText);
                let selected = vm.appSelectedResources;
                let progress = vm.$Progress;

                if ( action.length && selected.length ) {

                    if ( action === 'export' ) {
                        let urlString = '';

                        _.forEach(selected, function(id, index) {
                            let operand = index ? '&' : '?';
                            urlString += operand + 'resourceIds[]=' + id;
                        });

                        window.location = vm.appResourceUrl + '/export' + urlString;
                    }
                    else {
                        progress.start();

                        vm.$http.put(vm.appResourceUrl + '/' + action + '/quick-edit', {resources: selected}).then(function (response) {
                            if (response.data && response.data.success) {
                                progress.finish();
                                vm.appQuickEditText = '';

                                _.delay(function() {
                                    swal({title: "Excellent!", text: response.data.success, type: 'success', animation: 'slide-from-bottom'}, function () {
                                        vm.fetchResources();
                                    });
                                }, 500);
                            }
                        }, function (error) {
                            if ( error.status && error.status === 403 && error.data )
                                vm.appCustomErrorAlertConfirmed(error.data.error);
                            else if ( error.status && error.status === 404 && error.data )
                                vm.appCustomErrorAlert(error.data.error);
                            else
                                vm.appGeneralErrorAlert();

                            progress.fail();
                            vm.quickEdit = '';
                        });
                    }
                }
            },
            exportAll() {
                window.location = this.appResourceUrl + '/export';
            },
        },
    }
</script>
