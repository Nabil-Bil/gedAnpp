<template>
  <div class="flex min-h-screen">
    <div class="w-64 bg-gray-50 border-r border-gray-200">
      <div class="py-4 px-6">
        <a href="#" class="flex justify-center">
          <p class="font-bold text-3xl h-9">Logo</p>
        </a>
      </div>

      <div class="mb-10" v-for="link in links" :key="link.name">
        <div v-if="link.role==UserData.role || link.role=='any'">
          <Link
            class="flex items-center px-6 py-2.5 hover:text-blue-600 group"
            :href="link.route"
            :class="
              currentRoute == link.route ? 'text-blue-600' : 'text-gray-500'
            "
          >
            <i
              class="pi group-hover:text-blue-600 h-5 w-5 text-gray-400 mr-2"
              :class="link.icon"
            />
            <p class="font-bold text-lg">{{ link.name }}</p>
          </Link>
        </div>
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
          w-full
        "
      >
        <slot name="Items">
          <div></div>
        </slot>
        <div
          class="profile-picture rounded-full cursor-pointer"
          @click="sideBarVisibility = true"
          :style="{
            backgroundImage: 'url(' + UserData.path_image + ')',
          }"
        ></div>
        <Sidebar
          v-model:visible="sideBarVisibility"
          position="right"
          class="p-sidebar-md"
        >
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
      </div>

      <main>
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
import { onMounted, ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import ProfileVue from "../Components/Profile.vue";
import { Inertia } from "@inertiajs/inertia";
import data from "./links.json";

export default {
  components: {
    Link,
    ProfileVue,
  },

  setup() {
    const currentRoute = ref(route(route().current()));
    const sideBarVisibility = ref(false);
    const links = ref([]);

    onMounted(() => {
      data.forEach((element) => {
        links.value.push({
          name: element.name,
          route: route(element.routeName),
          icon: element.icon,
          role: element.role
        });
      });
    });

    function logout() {
      Inertia.post("/logout");
    }

    return {
      sideBarVisibility,
      links,
      currentRoute,
      logout,
    };
  },
  props: ["UserData"],
};
</script>

<style>
.profile-picture {
  width: 50px;
  height: 50px;
  background-size: cover;
}
</style>
