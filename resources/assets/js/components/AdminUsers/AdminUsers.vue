<template>
    <div class="ml-md-5">
        <vue-progress-bar></vue-progress-bar>
        <app-nav></app-nav>
        <router-view></router-view>
    </div>
</template>

<script>
    import AppNav from './Nav.vue';

    export default {
        components: {
            AppNav
        }
    }
</script>
