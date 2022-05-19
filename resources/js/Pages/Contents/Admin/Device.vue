<template>
  <div>
    <DashboardLayoutVue :UserData="user_data">
      <template #Items>
        <div class="px-10">
          <Button @click="active = 0" class="p-button-text" label="Devices" />
          <Button @click="active = 1" class="p-button-text" label="Designation" />
          <Button @click="active = 2" class="p-button-text" label="Classification" />
        </div>
      </template>
      <TabView :activeIndex="active">

        <TabPanel header="Devices">
          <DeviceDataVue :errors="errors" :designations="designations" :devices="devices"
            :pharmaceutical_establishments="pharmaceutical_establishments" :classifications="classifications" />
            

        </TabPanel>
        <TabPanel header="Designations">
            <composition-vue :data="designations" destroyUrl="/dashboard/designation/destroy" name="Designation"
              url="/dashboard/designation" :errors="errors" />
        </TabPanel>
        <TabPanel header="Classifications">
          <composition-vue :data="classifications" destroyUrl="/dashboard/classification/destroy" name="Classification" url="/dashboard/classification"
            :errors="errors" />
        </TabPanel>
      </TabView>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { ref } from "@vue/reactivity";
import DeviceDataVue from "../../Components/DeviceData.vue";
import CompositionVue from "../../Components/Composition.vue";

export default {
  components: {
    DashboardLayoutVue,
    CompositionVue,
    DeviceDataVue,
  },
  props: [
    "user_data",
    "designations",
    "classifications",
    "devices",
    "pharmaceutical_establishments",
    "errors",
  ],
  setup() {
    const active = ref(0);

    return {
      active,
    };
  },
};
</script>
<style>
.p-tabview-nav {
  display: none;
}

.p-tabview .p-tabview-panels {
  padding-left: 0px;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-right: 0px;
}

span.p-column-title {
  width: 100%;
}

div.p-column-header-content {
  justify-content: center;
}
</style>
