<template>
    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <router-link v-bind:to="{ name: 'settings.index' }" tag="a" class="nav-link" exact><i class="fa fa-home"></i> Dashboard</router-link>
        </li>
        <li class="nav-item">
            <router-link v-bind:to="{ name: 'settings.profile' }" tag="a" class="nav-link" exact><i class="fa fa-user"></i> Profile</router-link>
        </li>
        <li class="nav-item">
            <router-link v-bind:to="{ name: 'settings.edit_profile' }" tag="a" class="nav-link"><i class="fa fa-pencil-square-o"></i> Edit</router-link>
        </li>
        <li class="nav-item">
            <router-link v-bind:to="{ name: 'settings.edit_password' }" tag="a" class="nav-link"><i class="fa fa-fw fa-btn fa-lock"></i> Password</router-link>
        </li>
    </ul>
</template>
