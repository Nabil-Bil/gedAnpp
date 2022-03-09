<template>
    <div>
        <DashboardLayoutVue :UserData="user_data">Main</DashboardLayoutVue>
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

