<template>
    <div>
       <DashboardLayoutVue :UserData="user_data">
           Library
           </DashboardLayoutVue>
    </div>
</template>

<script>
import DashboardLayoutVue from "../Layouts/DashboardLayout.vue"
    export default {
        components:{
            DashboardLayoutVue
        },
        props:['user_data']
    }
</script>

<style lang="scss" scoped>

</style>