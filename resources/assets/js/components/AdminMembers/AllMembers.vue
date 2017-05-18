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
                        <select class="custom-select form-control mb-2 mb-sm-0 mr-sm-5" v-model="appQuickEditOption" id="quick-edit">
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
                                        <input type="checkbox" class="custom-control-input" v-model="appSelectAll">
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                </th>
                                <th v-on:click.prevent="appChangeSort('first_name')">Name <span v-html="appGetSortMarkup('first_name')"></span></th>
                                <th v-on:click.prevent="appChangeSort('email')">Email <span v-html="appGetSortMarkup('email')"></span></th>
                                <th v-on:click.prevent="appChangeSort('username')">Username <span v-html="appGetSortMarkup('username')"></span></th>
                                <th v-on:click.prevent="appChangeSort('active')" >Active <span v-html="appGetSortMarkup('active')"></span></th>
                                <th v-on:click.prevent="appChangeSort('updated_at')" >Updated <span v-html="appGetSortMarkup('updated_at')"></span></th>
                                <th v-if="appUserHasPermission('update')"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="resource in orderedAppResources">
                                <td v-if="appUserHasPermission('update')">
                                    <label class="custom-control custom-checkbox mr-0">
                                        <input type="checkbox" class="custom-control-input" v-model="appSelectedResources" v-bind:value="resource.id">
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                </td>
                                <td>{{ resource.name }}</td>
                                <td>{{ resource.email }}</td>
                                <td> {{ resource.username }}</td>
                                <td v-html="appActiveMarkup(resource.active)"></td>
                                <td>{{ resource.updated_at | dateToTheDay }}</td>
                                <td v-if="appUserHasPermission('read')">
                                    <router-link v-bind:to="{ name: 'admin_members.view', params: { id: resource.id }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-user-o"></i></router-link>
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
        methods: {
            fetchResources(orderAttr, orderToggle) {
                this.appFetchResources(this, orderAttr, orderToggle);
            },
            quickEditResources() {
                this.appQuickEditResources();
            },
            exportAll() {
                this.appExportAll();
            },
        },
    }
</script>
