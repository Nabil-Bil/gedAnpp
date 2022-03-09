<template>
  <div class="flex min-h-screen">
    <div class="w-64 bg-gray-50 border-r border-gray-200">
      <div class="py-4 px-6">
        <a href="#" class="flex justify-center">
          <p class="font-bold text-3xl h-9">Logo</p>
        </a>
      </div>

        <div class="mb-10" v-for="link in links" :key="link.name">
            <Link
          class="flex items-center px-6 py-2.5 text-gray-500 hover:text-blue-600 group"
          :href="link.href"
        >
        <i class="pi group-hover:text-blue-600 h-5 w-5 text-gray-400 mr-2" :class="link.icon" />
          <p class="font-bold text-lg">{{ link.name }}</p>
        </Link>
        </div>
    </div>

    <div class="flex-1">
      <div
        class="
          flex
          justify-between
          py-3
          px-6
          bg-gray-50
          border-b
          space-x-5
          items-center
        "
      >
        <div class="flex flex-row">
          <div class="mx-5">item</div>
          <div class="mx-5">item</div>
          <div class="mx-5">item</div>
        </div>
        <div
          class="profile-picture rounded-full cursor-pointer"
          @click="sideBarVisibility = true"
          :style="{backgroundImage: 'url('+ UserData.path+')'}"
        ></div>
      </div>
      <ProfileVue :SideBarVisibility="sideBarVisibility" :UserData="UserData"></ProfileVue>

      <main>
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import ProfileVue from "../Components/Profile.vue";

export default {
  components: {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Link,
    ProfileVue
  },

  setup() {
    const sideBarVisibility = ref(false);
    const links=ref([{
            name:'Home',
            href:'/dashboard',
            icon:'pi-home'
        },
         {
            name:'Register',
            href:'/dashboard/register',
            icon:'pi-sign-in'
        },
         {
            name:'Other',
            href:'#',
            icon:'pi-sign-out'
        },]);



    return {
      sideBarVisibility,
      links,
    };
  },
  props: ["UserData",],
};
</script>

<style>
.profile-picture {
  width: 50px;
  height: 50px;
  background-size: cover;
}
</style>
