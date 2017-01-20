<style>
    .settings-nav ul.nav > li.active {
        border-right: 2px solid #005B98;
    }
</style>
<template>
    <div class="settings-nav">
        <div class="panel panel-default panel-flush">
            <div class="panel-heading">
                Settings
            </div>
            <div class="panel-body">
                <div class="settings-tabs">
                    <ul role="tablist" class="nav">
                        <router-link v-bind:to="{ name: 'settings.index' }" tag="li" role="presentation" exact>
                            <a><i class="fa fa-fw fa-btn fa-edit"></i> Profile</a>
                        </router-link>
                        <router-link v-bind:to="{ name: 'settings.security' }" tag="li" role="presentation">
                            <a><i class="fa fa-fw fa-btn fa-lock"></i> Security</a>
                        </router-link>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        data(){
            return{

            }
        }
    }
</script>
