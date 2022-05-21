<template>
  <DashboardLayoutVue :UserData="user_data">
    <form class="card p-24 upload-data" @submit.prevent="store" id="form">
      <h2 class="pb-10 font-bold text-xl">Create New Technical File</h2>
      <div class="formgrid grid">
        <div class="field col-12 md:col-6">
          <label for="code">Code</label>
          <InputText id="code" class="w-full" v-model="globalInputs.code" :class="errors.code?'p-invalid':''"/>
          <small id="code" class="p-error" v-if="errors.code">{{ errors.code }}</small>
        </div>
        <div class="field col-12 md:col-6">
          <label for="product_type">Product Type</label>
          <Dropdown id="product_type" class="w-full" v-model="globalInputs.product_type" :options="product_types"
            optionLabel="label" optionValue="value" @change="onTypeChange()" />
        </div>
        <template v-if="globalInputs.product_type != null" class="field col-12 md:col-6">
          <template v-if="globalInputs.product_type == 'medication'">
            <div class="field col-12 md:col-6">
              <label for="medication_status">Status</label>
              <Dropdown id="medication_status" class="w-full " v-model="globalInputs.status" placeholder="Select Status"
                :options="medicationStatus" :class="errors.status?'p-invalid':''"/>
              <small id="medication_status" class="p-error" v-if="errors.status">{{ errors.status }}</small>
            </div>
            <div class="field col-12 md:col-6">
              <label for="medication">Medication</label>
              <Dropdown id="medication" class="w-full" v-model="medicationData.medication" :options="medications"
                @change="onMedicationChanges()" :filter="true" optionLabel="name" placeholder="Select a Medication"
                filterPlaceholder="Find a Medication" :class="errors.medication_name?'p-invalid':''"/>
              <small id="medication" class="p-error" v-if="errors.medication_name">{{ errors.medication_name }}</small>
            </div>

            <template v-if="medicationData.medication != null">
              <div class="field col-12 md:col-6">
                <label for="presentation">Presentation</label>
                <Dropdown id="presentation" class="w-full " v-model="medicationData.presentation"
                  :options="medicationData.medication.presentations" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select a Presentation" filterPlaceholder="Find a Presentation" :class="errors.presentation?'p-invalid':''"/>
                <small id="presentation" class="p-error" v-if="errors.presentation">{{ errors.presentation }}</small>
              </div>
              <div class="field col-12 md:col-6">
                <label for="form">Form</label>
                <Dropdown id="form" class="w-full" v-model="medicationData.form"
                  :options="medicationData.medication.forms" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select a Form" filterPlaceholder="Find a Form" :class="errors.form?'p-invalid':''"/>
                <small id="form" class="p-error" v-if="errors.form">{{ errors.form }}</small>
              </div>
              <div class="field col-12 md:col-6">
                <label for="dosage">Dosage</label>
                <Dropdown id="dosage" class="w-full" v-model="medicationData.dosage"
                  :options="medicationData.medication.dosages" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select a Dosage" filterPlaceholder="Find a Dosage" :class="errors.dosage?'p-invalid':''"/>
                <small id="dosage" class="p-error" v-if="errors.dosage">{{ errors.dosage }}</small>
              </div>
              <div class="field col-12 md:col-6">
                <label for="dci">Actif Ingredient</label>
                <Dropdown id="dci" class="w-full " v-model="medicationData.dci"
                  :options="medicationData.medication.dcis" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select an Actif Ingredient" filterPlaceholder="Find an Actif Ingredient" :class="errors.dcis?'p-invalid':''"/>
                <small id="dci" class="p-error" v-if="errors.dcis">{{ errors.dcis }}</small>
              </div>
            </template>
          </template>
          <template v-if="globalInputs.product_type == 'device'">
            <div class="field col-12 md:col-6">
              <label for="device_status">Status</label>
              <Dropdown id="device_status" class="w-full" v-model="globalInputs.status" :options="deviceStatus"
                placeholder="Select Status" :class="errors.status?'p-invalid':''"/>
              <small id="device_status" class="p-error" v-if="errors.status">{{ errors.status }}</small>


            </div>
            <div class="field col-12 md:col-6">
              <label for="device">Device</label>
              <Dropdown id="device" class="w-full" v-model="deviceData.device" :options="devices" :filter="true"
                optionLabel="name" placeholder="Select a Device" filterPlaceholder="Find a Device"
                @change="onDeviceChange()" :class="errors.device_name?'p-invalid':''"/>
              <small id="device" class="p-error" v-if="errors.device_name">{{ errors.device_name }}</small>

            </div>
            <template v-if="deviceData.device != null">
              <div class="field col-12 md:col-6">
                <label for="designation">Designation</label>
                <Dropdown id="designation" class="w-full" v-model="deviceData.designation"
                  :options="deviceData.device.designations" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select a Designation" filterPlaceholder="Find a Designation" :class="errors.designation?'p-invalid':''"/>
                <small id="designation" class="p-error" v-if="errors.designation">{{ errors.designation }}</small>

              </div>
              <div class="field col-12 md:col-6">
                <label for="classification">Classification</label>
                <Dropdown id="classification" class="w-full" v-model="deviceData.classification"
                  :options="deviceData.device.classifications" :filter="true" optionLabel="value" optionValue="id"
                  placeholder="Select a Classification" filterPlaceholder="Find a Classification" :class="errors.classification?'p-invalid':''"/>
                <small id="classification" class="p-error" v-if="errors.classification">{{ errors.classification
                }}</small>

              </div>
            </template>
          </template>
          <template v-if="deviceData.device != null || medicationData.medication != null">
            <div class="field col-12">
              <div class="px-4">
                <p for="multiple-upload " class="p-error" v-if="errors.files"><span class="font-bold text-2xl">.</span>{{errors.files}}</p>
                <p for="multiple-upload " class="p-error" v-if="errors['files.0.module']"><span class="font-bold text-2xl">.</span>{{errors['files.0.module']}}</p>
              </div>

              <div class="flex  w-full flex-col">
                <div
                  class="bg-gray-100 flex justify-between w-full h-min border-t  border-x border-gray-400 rounded-t-md py-3  px-5" :class="errors.files || errors['files.0.module']?'border-red-400':''">
                  <label for="multiple-upload">
                    <input type="file" multiple accept="application/pdf" max="5" id="multiple-upload" name="files[]"
                      ref="filesInput" @input="onChange" class="hidden" />
                    <Button @click="select" label="Choose" icon="pi pi-plus" />
                  </label>
                  <Button @click="clear" label="Cancel" icon="pi pi-times" />
                </div>
                <div class="border-gray-400 border-b rounded-b-md border-x border-t" :class="errors.files || errors['files.0.module']?'border-red-400':''">
                  <template class="flex flex-col w-full border-y border-gray-400  rounded-b-md border-x " :class="errors.files || errors['files.0.module']?'border-red-400':''"
                    v-if="globalInputs.files != null" style="min-height:300px">
                    <div v-for="file in globalInputs.files" :key="file.id"
                      class="w-full flex border border-gray-300 justify-between px-4 py-6 items-center">
                      <div class="flex items-center">
                        <img src="../../assets/pdf.svg" alt="pdf icon" width="75">
                        <p class="font-bold text-xl px-4">{{ file.value.name }}</p>
                      </div>
                      <div class="flex">
                        <Dropdown v-model='file.module' :options="[1, 2, 3, 4, 5]" class="w-28 mx-4 text-center" />
                        <Button icon="pi pi-times" class="h-min mx-3" @click="removeFile(file.id)"></Button>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
            <Button class="field col-12 py-2" label="Create" icon="pi pi-plus" type="submit" />
          </template>
        </template>
      </div>
    </form>
  </DashboardLayoutVue>
</template>

<script>
import { ref } from "@vue/reactivity";
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { medicationStatus, deviceStatus } from "../../helpers/services"
export default {
  components: {
    DashboardLayoutVue,
  },
  setup(props) {

    
    const getInitialMedicationData = () => {
      return {
        medication: null,
        dci: null,
        presentation: null,
        form: null,
        dosage: null,
      }
    }

    const getInitialDeviceData = () => {
      return {
        device: null,
        designation: null,
        classification: null,
      }
    }
    const medicationData = ref(getInitialMedicationData())
    const deviceData = ref(getInitialDeviceData())
    const globalInputs = ref({
      code: "",
      status: "",
      product_type: null,
      files: null
    })


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
      let data;
      if (globalInputs.value.product_type == 'medication') {
        data = {
          ...globalInputs.value,
          files: globalInputs.value.files == null ? null : [...globalInputs.value.files],
          ...medicationData.value,
          medication: medicationData.value.medication['name']
        }
      } else if (globalInputs.value.product_type == 'device') {
        data = {
          ...globalInputs.value,
          ...deviceData.value,
          files: globalInputs.value.files == null ? null : [...globalInputs.value.files],
          device: deviceData.value.device['name']
        }
      } else {
        return;
      }
      Inertia.post('/dashboard/technicalfile', { ...data }, { forceFormData: true })
    }

    const onMedicationChanges = () => {
      const newMedication = medicationData.value.medication;
      medicationData.value = getInitialMedicationData()
      medicationData.value.medication = newMedication
    }

    const onTypeChange = () => {
      medicationData.value = getInitialMedicationData()
      deviceData.value = getInitialDeviceData();
      globalInputs.value.status=""
    }

    const onDeviceChange = () => {
      const newDevice = deviceData.value.device;
      deviceData.value = getInitialDeviceData()
      deviceData.value.device = newDevice
    }

    const filesInput = ref(null)
    const error = ref(false);

    function select() {
      filesInput.value.click()
    }

    function onChange(event) {
      const selectedFiles = event.target.files
      const f = []
      for (let i = 0; i < selectedFiles.length; i++) {
        f.push({
          id: i,
          value: selectedFiles.item(i),
          module: 1
        })
      }
      if (f == []) {
        globalInputs.value.files = null
      } else {
        globalInputs.value.files = f;
      }
      filesInput.value.value = null

    }



    function removeFile(id) {
      globalInputs.value.files = globalInputs.value.files.filter((value) => value.id != id);

    }

    function clear() {
      globalInputs.value.files = null
    }
    return {
      store,
      medicationData,
      product_types,
      onMedicationChanges,
      onTypeChange,
      deviceData,
      globalInputs,
      onDeviceChange,
      removeFile,
      clear,
      onChange,
      select,
      error,
      filesInput,
      medicationStatus,
      deviceStatus,
    };
  },

  props: ["user_data", "errors", "devices", "medications"],
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>


