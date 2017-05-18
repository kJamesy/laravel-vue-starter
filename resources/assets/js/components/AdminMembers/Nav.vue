<template>
    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <router-link v-bind:to="{ name: 'admin_members.index' }" tag="a" class="nav-link" exact><i class="fa fa-home"></i> All Members</router-link>
        </li>
        <li class="nav-item">
            <router-link v-bind:to="{ name: 'admin_members.create' }" tag="a" class="nav-link" exact><i class="fa fa-pencil"></i> New Member</router-link>
        </li>
        <li class="nav-item" v-if="appCurrentRouteIdParam">
            <router-link v-bind:to="{ name: 'admin_members.view', params: { id: appCurrentRouteIdParam }}" class="nav-link" exact><i class="fa fa-user-o"></i> View Member</router-link>
        </li>
        <li class="nav-item" v-if="appCurrentRouteIdParam">
            <router-link v-bind:to="{ name: 'admin_members.edit', params: { id: appCurrentRouteIdParam }}" class="nav-link" exact><i class="fa fa-edit"></i> Edit Member</router-link>
        </li>
    </ul>
</template>