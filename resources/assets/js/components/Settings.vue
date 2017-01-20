<template>
    <div class="row">
        <vue-progress-bar></vue-progress-bar>
        <div class="col-md-3">
            <settings-nav></settings-nav>
        </div>
        <div class="col-md-9">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import SettingsNav from '../components/SettingsNav.vue';

    export default {
        mounted() {

        },
        components: {
            SettingsNav
        }
    }
</script>
