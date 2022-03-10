<template>
  <div>
    <DashboardLayoutVue :UserData="user_data"
      ><iframe
        class="w-full"
        style="height:85vh;"
        src="http://www.africau.edu/images/default/sample.pdf"
      ></iframe
    ></DashboardLayoutVue>
  </div>
</template>

<script>
import DashboardLayoutVue from "../Layouts/DashboardLayout.vue";
export default {
  components: {
    DashboardLayoutVue,
  },
  props: ["user_data"],
  setup() {
    return {};
  },
};
</script>

<style lang="scss" scoped>
</style>