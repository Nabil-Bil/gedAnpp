<template>
  <DashboardLayoutVue :UserData="user_data">
    <form class="card p-24" @submit.prevent="store" method="POST">
      <h2 class="pb-10 font-bold text-xl">Create New Technical File</h2>
      <div class="formgrid grid">
        <div class="field col-12 md:col-6">
          <label for="code">Code</label>
          <InputText id="code" class="w-full" v-model="code" />
        </div>
        <div class="field col-12 md:col-6">
          <label for="status">Status</label>
          <InputText id="status" class="w-full" v-model="status" />
        </div>
        <div class="field col-12 md:col-6">
          <label for="product_type">Product Type</label>
          <Dropdown id="product_type" class="w-full" v-model="product_type" :options="product_types" optionLabel="label"
            optionValue="value" @change="onTypeChange()" />
        </div>
        <template v-if="product_type != null" class="field col-12 md:col-6">
          <template v-if="product_type == 'medication'">
            <div class="field col-12 md:col-6">
              <label for="medication">Medication</label>
              <Dropdown id="medication" class="w-full mt-2" v-model="medicationData.medication" :options="medications"
                @change="onMedicationChanges()" :filter="true" optionLabel="name" placeholder="Select a Medication"
                filterPlaceholder="Find a Medication" />
            </div>

            <template v-if="medicationData.medication != null">
              <div class="field col-12 md:col-6">
                <label for="presentation">Presentation</label>
                <Dropdown id="presentation" class="w-full mt-2" v-model="medicationData.presentation"
                  :options="medicationData.medication.presentations" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select a Presentation" filterPlaceholder="Find a Presentation" />
              </div>
              <div class="field col-12 md:col-6">
                <label for="form">Form</label>
                <Dropdown id="form" class="w-full mt-2" v-model="medicationData.form"
                  :options="medicationData.medication.forms" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select a Form" filterPlaceholder="Find a Form" />
              </div>
              <div class="field col-12 md:col-6">
                <label for="dosage">Dosage</label>
                <Dropdown id="dosage" class="w-full mt-2" v-model="medicationData.dosage"
                  :options="medicationData.medication.dosages" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select a Dosage" filterPlaceholder="Find a Dosage" />
              </div>
              <div class="field col-12 md:col-6">
                <label for="dci">Actif Ingredient</label>
                <Dropdown id="dci" class="w-full mt-2" v-model="medicationData.dci"
                  :options="medicationData.medication.dcis" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select an Actif Ingredient" filterPlaceholder="Find an Actif Ingredient" />
              </div>
            </template>
          </template>
          <div v-if="product_type == 'device'">
            <label for="device">Device</label>
            <Dropdown id="device" class="w-full mt-2" v-model="device" :options="devices" :filter="true"
              optionLabel="name" optionValue="name" placeholder="Select a Device" filterPlaceholder="Find a Device" />
          </div>
        </template>


      </div>
    </form>
  </DashboardLayoutVue>
</template>

<script>
import { ref, reactive, toRefs } from "@vue/reactivity";
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { Inertia } from "@inertiajs/inertia";
export default {
  components: {
    DashboardLayoutVue,
  },
  setup(props) {
    const getInitialMedicationData = () => {
      return {
        medication: null,
        dci: '',
        presentation: '',
        form: '',
        dosage: '',
      }
    }
    const inputs = reactive({
      code: "",
      status: "",
      product_type: null,
      medicationData: getInitialMedicationData(),
      device: ''
    }
    );
    const product_types = [
      {
        label: 'None',
        value: null,
      },
      {
        label: 'Medication',
        value: 'medication',

      },
      {
        label: 'Device',
        value: 'device',

      }
    ]

    function store() {
    }

    const onMedicationChanges = () => {
      const newMedication = inputs.medicationData.medication;
      inputs.medicationData = getInitialMedicationData()
      inputs.medicationData.medication = newMedication
    }

    const onTypeChange = () => {
      inputs.medicationData = getInitialMedicationData()
      inputs.device = '';

    }

    return {
      store,
      ...toRefs(inputs),
      product_types,
      onMedicationChanges,
      onTypeChange

    };
  },
  props: ["user_data", "errors", "devices", "medications"],
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>


