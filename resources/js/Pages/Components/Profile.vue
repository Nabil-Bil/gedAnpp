<template>
  <div>
    <Sidebar
      v-model:visible="SideBarVisibility"
      position="right"
      class="p-sidebar-md"
    >
      <div class="flex flex-col justify-between" style="height: 90vh">
        <div>
            <h2>Name : {{ UserData.name }}</h2>
            <h2>Email : {{UserData.email}}</h2>
        </div>
        <div>
          <Button label="Logout" class="p-button-text" @click="logout" />
        </div>
      </div>
    </Sidebar>
  </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
export default {
  props: {
    SideBarVisibility: Boolean,
    UserData: Object,
  },
  setup() {
    const logout = () => {
      Inertia.post("/logout");
    };
    return {
      logout,
    };
  },
};
</script>
