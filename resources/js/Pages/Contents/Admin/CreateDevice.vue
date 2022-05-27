<template>
  <DashboardLayoutVue :UserData="user_data" :errors="errors">
    <form class="card p-24" @submit.prevent="store" method="POST">
      <h2 class="pb-10 font-bold text-xl">Create New Device</h2>
      <div class="formgrid grid items-center">
        <template  v-for="input of inputs" :key="input.id">
          <div v-if="input.type == 'text'" class="field col-12 md:col-6">
            <label :for="input.id">{{ input.label }}</label>

            <InputText :id="input.id" class="w-full" v-model="input.value"
              :class="errors[input.id] ? 'p-invalid' : ''" />
          </div>
          <div v-else-if="input.type=='textarea'"  class="field col-12">
            <label :for="input.id">{{ input.label }}</label>
          <TextArea rows="4" class="text-base text-color surface-overlay p-2 border-1 border-solid surface-border border-round appearance-none outline-none focus:border-primary w-full"></TextArea>

          </div>
          <div v-else-if="
            input.type == 'dropdown' &&
            input.id != 'pharmaceutical_establishment'
          "
          class="field col-12 md:col-6"
          >
            <label :for="input.id">{{ input.label }}</label>
            <Dropdown v-model="input.value" :options="input.data" optionLabel="value" optionValue="id"
              :placeholder="'Select New ' + input.label" :filter="true" :filterPlaceholder="'Find ' + input.label"
              class="w-full" :class="errors[input.id] ? 'p-invalid' : ''" />
          </div>
          <div v-else-if="input.id == 'pharmaceutical_establishment'" class="field col-12 md:col-6">
            <label :for="input.id">{{ input.label }}</label>
            <Dropdown v-model="input.value" :options="input.data" optionLabel="name" optionValue="id"
              :placeholder="'Select New ' + input.label" :filter="true" :filterPlaceholder="'Find ' + input.label"
              class="w-full" :class="errors[input.id] ? 'p-invalid' : ''" />
          </div>
          
          <div v-else class="flex items-center field col-12 md:col-6" >
            <CheckBox v-model="input.value" :binary="true"></CheckBox>
            <label :for="input.id" class="mx-4">{{ input.label }}</label>

          </div>


          <small :id="input.id + '-error'" class="p-error">{{
              errors[input.id]
          }}</small>
        </template>
        <div class="flex justify-end w-full">
          <Button label="Register" type="submit" />
        </div>
      </div>
    </form>
  </DashboardLayoutVue>
</template>

<script>
import { ref } from "@vue/reactivity";
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { Inertia } from "@inertiajs/inertia";
export default {
  components: {
    DashboardLayoutVue,
  },
  setup(props) {
    const inputs = ref([
      {
        id: "code",
        label: "Code",
        value: "",
        errors: "",
        type: "text",
      },
      {
        id: "name",
        label: "Name",
        value: "",
        errors: "",
        type: "text",
      },

      {
        id: "type",
        label: "Type",
        value: "",
        errors: "",
        type: "text",
      },
      {
        id: "de_holder",
        label: "DE holder",
        value: "",
        errors: "",
        type: "text",
      },

      {
        id: "pharmaceutical_establishment",
        label: "Pharmaceutical Establishment",
        value: "",
        errors: "",
        type: "dropdown",
        data: props.pharmaceutical_establishments,
      },
      {
        id: "designation",
        label: "Designation",
        value: "",
        errors: "",
        type: "dropdown",
        data:props.designations
      },
      {
        id: "classification",
        label: "Classification",
        value: "",
        errors: "",
        type: "dropdown",
        data:props.classifications
      },

      {
        id: "characteristic",
        label: "Characteristic",
        value: "",
        errors: "",
        type: "textarea",
      },
      {
        id: "status",
        label: "Essential?",
        value: false,
        errors: "",
        type: "checkbox",
      },
    ]);

    function store() {
      const data = {};
      inputs.value.forEach((element) => {
        const key = element.id;
        const value = element.value;
        data[key] = value;
      });
      Inertia.post("/dashboard/device", data);
    }

    return {
      store,
      inputs,
    };
  },
  props: ["user_data", "errors", "pharmaceutical_establishments",'designations','classifications'],
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>