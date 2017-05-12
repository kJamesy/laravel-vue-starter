<template>
    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <router-link v-bind:to="{ name: 'admin_users.index' }" tag="a" class="nav-link" exact><i class="fa fa-home"></i> All Users</router-link>
        </li>
        <li class="nav-item">
            <router-link v-bind:to="{ name: 'admin_users.create' }" tag="a" class="nav-link" exact><i class="fa fa-pencil"></i> New User</router-link>
        </li>
    </ul>
</template>
