<template>
  <DashboardLayoutVue :UserData="user_data">
    <form class="card p-24" @submit.prevent="store" method="POST">
      <h2 class="pb-10 font-bold text-xl">Create New Medication</h2>
      <div class="formgrid grid">
        <div
          class="field col-12 md:col-6"
          v-for="input of inputs"
          :key="input.id"
        >
          <label :for="input.id">{{ input.label }}</label>
          <div v-if="input.type=='text'">
            <InputText
            :id="input.id"
            class="w-full"
            v-model="input.value"
            :class="errors[input.id] ? 'p-invalid' : ''"
          />
          </div>
          <div v-else-if="input.type=='dropdown' && input.id!='pharmaceutical_establishment'">
              <Dropdown
            v-model="input.value"
            :options="input.data"
            optionLabel='value'
            optionValue="id"
            :placeholder="'Select New '+input.label"
            :filter="true"
            :filterPlaceholder="'Find '+input.label"
            class="w-full"
            :class="errors[input.id] ? 'p-invalid' : ''"
          />
          </div>
          <div v-else>
            <Dropdown
            v-model="input.value"
            :options="input.data"
            optionLabel='name'
            optionValue="id"
            :placeholder="'Select New '+input.label"
            :filter="true"
            :filterPlaceholder="'Find '+input.label"
            class="w-full"
            :class="errors[input.id] ? 'p-invalid' : ''"
          />
          </div>
          
          <small :id="input.id + '-error'" class="p-error">{{
            errors[input.id]
          }}</small>
        </div>
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
        errors:"",
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
        id: "conditioning",
        label: "Conditioning",
        value: "",
        errors: "",
        type: "text",

      },
       {
        id: "form",
        label: "Form",
        value: "",
        errors: "",
        type: "dropdown",
        data:props.forms

      },

      {
        id: "pharmaceutical_establishment",
        label: "Pharmaceutical Establishment",
        value: "",
        errors: "",
        type: "dropdown",
        data:props.pharmaceutical_establishments

      },
      {
        id: "dosage",
        label: "Dosage",
        value: "",
        errors: "",
        type: "dropdown",
        data:props.dosages


      },
      {
        id: "presentation",
        label: "Presentation",
        value: "",
        errors: "",
        type: "dropdown",
        data:props.presentations


      },
    ]);

    function store() {
      const data = {};
      inputs.value.forEach((element) => {
        const key = element.id;
        const value = element.value;
        data[key] = value;
      });
      Inertia.post("/dashboard/medication", data);
    }

    return {
      store,

      inputs,
    };
  },
  props: ["user_data", "errors",'forms','pharmaceutical_establishments','dosages','presentations'],
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>