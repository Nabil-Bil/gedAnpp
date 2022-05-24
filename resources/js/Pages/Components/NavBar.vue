<template>
    <nav class="
          flex
          justify-between
          py-3
          px-6
          bg-gray-50
          border-b
          space-x-5
          items-center
          w-full
        ">
        <slot></slot>
        <div></div>
        <div class="flex items-center">
            <slot name="profilePicture"></slot>
            <div class="profile-picture rounded-full cursor-pointer "
                @click="rightSideBarVisibility = true" :style="{
                    backgroundImage: 'url(' + UserData.path_image + ')',
                }">
            </div>
        </div>

        <Sidebar v-model:visible="rightSideBarVisibility" position="right" class="p-sidebar-md">
            <div class="flex flex-col justify-between" style="height: 90vh">
                <div>
                    <h2>First Name : {{ UserData.first_name }}</h2>
                    <h2>Last Name : {{ UserData.last_name }}</h2>
                    <h2>Email : {{ UserData.email }}</h2>
                    <h2>Direction Name : {{ UserData.direction }}</h2>
                </div>
                <div>
                    <Button label="Logout" class="p-button-text" @click="logout" />
                </div>
            </div>
        </Sidebar>
    </nav>
</template>


<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
export default {
    setup() {
        const rightSideBarVisibility = ref(false);
        function logout() {
            Inertia.post("/logout");
        }

        return {
            rightSideBarVisibility,
            logout
        }

    },
    props: ['UserData']
}
</script>

<style>
.profile-picture {
    width: 50px;
    height: 50px;
    background-size: cover;
}
</style>