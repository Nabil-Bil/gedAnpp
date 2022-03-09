<template>
    <div>
        <DashboardLayoutVue :UserData="user_data">
            

        </DashboardLayoutVue>
    </div>
</template>

<script>
import DashboardLayoutVue from "../Layouts/DashboardLayout.vue"
    export default {
        components:{
            DashboardLayoutVue
        },
        props:{
            'user_data':Object
        }
    }
</script>

<style lang="scss" scoped>

</style>