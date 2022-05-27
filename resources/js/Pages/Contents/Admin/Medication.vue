<template>
  <div>
    <DashboardLayoutVue :UserData="user_data" :errors="errors">
      <template #Items>
        <div class="px-10">
          <Button @click="active = 0" class="p-button-text" label="Medications" />
          <Button @click="active = 1" class="p-button-text" label="Actif Ingredients" />
          <Button @click="active = 2" class="p-button-text" label="Forms" />
          <Button @click="active = 3" class="p-button-text" label="Dosages" />
          <Button @click="active = 4" class="p-button-text" label="Presentations" />
        </div>
      </template>
      <TabView :activeIndex="active">

        <TabPanel header="Medication">
          <MedicationData :dosages="dosages" :errors="errors" :forms="forms" :medications="medications"
            :pharmaceutical_establishments="pharmaceutical_establishments" :presentations="presentations" />

        </TabPanel>
        <TabPanel header="Actif Ingredient">
            <composition-vue :data="dcis" destroyUrl="/dashboard/dci/destroy" name="Actif Ingredient"
              url="/dashboard/dci" :errors="errors" />
        </TabPanel>
        <TabPanel header="Forms">
          <composition-vue :data="forms" destroyUrl="/dashboard/form/destroy" name="Form" url="/dashboard/form"
            :errors="errors" />
        </TabPanel>
        <TabPanel header="Dosages">
          <composition-vue :data="dosages" destroyUrl="/dashboard/dosage/destroy" name="Dosage" url="/dashboard/dosage"
            :errors="errors" />
        </TabPanel>
        <TabPanel header="Presentations">
          <composition-vue :data="presentations" destroyUrl="/dashboard/presentation/destroy" name="Presentation"
            url="/dashboard/presentation" :errors="errors" />
        </TabPanel>
      </TabView>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { ref } from "@vue/reactivity";
import MedicationData from "../../Components/MedicationData.vue";

import CompositionVue from "../../Components/Composition.vue";

export default {
  components: {
    DashboardLayoutVue,
    CompositionVue,
    MedicationData,
  },
  props: [
    "user_data",
    "forms",
    "dosages",
    "presentations",
    "medications",
    "pharmaceutical_establishments",
    "errors",
    "dcis"
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
