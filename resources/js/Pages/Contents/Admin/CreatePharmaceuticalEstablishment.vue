<template>
  <DashboardLayoutVue :UserData="user_data" :errors="errors">
    <form class="card p-24" @submit.prevent="store" method="POST">
      <h2 class="pb-10 font-bold text-xl">
        Create New Pharmaceutical Establishment
      </h2>
      <div class="formgrid grid">
        <div
          class="field col-12 md:col-6"
          v-for="input of inputs"
          :key="input.id"
        >
          <label :for="input.id">{{ input.label }}</label>
          <InputText
            :id="input.id"
            class="w-full"
            v-model="input.value"
            :class="errors[input.id] ? 'p-invalid' : ''"
          />
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
        id: "name",
        label: "Name",
        value: "",
        errors: "",
      },
      {
        id: "email",
        label: "Email",
        value: "",
        errors: props.errors["email"],
      },
      {
        id: "fixed",
        label: "Fix Number",
        value: "",
        errors: "",
      },
      {
        id: "mobile",
        label: "Mobile",
        value: "",
        errors: "",
      },
      {
        id: "fax",
        label: "Fax",
        value: "",
        errors: "",
      },
      {
        id: "address",
        label: "Address",
        value: "",
        errors: "",
      },
      {
        id: "nature",
        label: "Nature",
        value: "",
        errors: "",
      },
      {
        id: "agreement",
        label: "Agreement",
        value: "",
        errors: "",
      },
      {
        id: "status",
        label: "Status",
        value: "",
        errors: "",
      },
      {
        id: "manager_name",
        label: "Manager Name",
        value: "",
        errors: "",
      },
      {
        id: "tech_manager_name",
        label: "Technical Manager Name",
        value: "",
        errors: "",
      },
      {
        id: "activity",
        label: "Activity",
        value: "",
        errors: "",
      },
    ]);

    function store() {
      const data = {};
      inputs.value.forEach((element) => {
        const key = element.id;
        const value = element.value;
        data[key] = value;
      });
      Inertia.post("/dashboard/pharmaceuticalEstablishment", data);
    }

    return {
      store,

      inputs,
    };
  },
  props: ["user_data", "errors"],
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>