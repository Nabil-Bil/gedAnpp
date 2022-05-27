<template>
  <div class="flex min-h-screen">
    <Sidebar v-model:visible="leftSideBarVisibility" position="left" class="p-sidebar-sm bg-gray-50">
      <div class="mb-10" v-for="link in links" :key="link.name">
        <Link class="flex items-center px-6 py-2.5 hover:text-blue-600 group" :href="link.route" :class="
          currentRoute == link.route ? 'text-blue-600' : 'text-gray-500'
        ">
        <i class="pi group-hover:text-blue-600 h-5 w-5 text-gray-400 mr-2" :class="link.icon" />
        <p class="font-bold text-lg">{{ link.name }}</p>
        </Link>
      </div>
    </Sidebar>
    <div class="flex-1">
      <NavBarVue :UserData="UserData" :errors="errors">
        <div class="flex items-center">
          <Button @click="leftSideBarVisibility = true" class="
              p-button p-button-outlined p-button-rounded p-button-secondary
            " icon="pi pi-ellipsis-v"></Button>
          <slot name="Items">
            <div></div>
          </slot>
        </div>
      </NavBarVue>
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
import { onMounted, ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import data from "./links.json";
import NavBarVue from "../Components/NavBar.vue";

export default {
  components: {
    Link,
    NavBarVue
  },

  setup() {
    const currentRoute = ref(route(route().current()));
    const rightSideBarVisibility = ref(false);
    const leftSideBarVisibility = ref(false);
    const links = ref([]);

    onMounted(() => {
      data.forEach((element) => {
        links.value.push({
          name: element.name,
          route: route(element.routeName),
          icon: element.icon,
        });
      });
    });


    return {
      rightSideBarVisibility,
      leftSideBarVisibility,
      links,
      currentRoute,
    };
  },
  props: ["UserData","errors"],
};
</script>


