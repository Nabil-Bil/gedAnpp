<template>
    <UserLayoutVue :userData="userData">
        <template #navbar v-if="userData.role == 'directeur'">
            <Button class="p-button-rounded border p-button-link " icon="pi pi-user"
                @click="evaluateurs()"></Button>

        </template>
        <form class="card p-24 upload-data " @submit.prevent="search" id="form">
            <h2 class="pb-10 font-bold text-xl">Search Technical Files</h2>
            <div class="formgrid grid">
                <div class="field col-12 md:col-6">
                    <label for="code">Code</label>
                    <InputText id="code" class="w-full" v-model="globalInputs.code" />
                </div>
                <div class="field col-12 md:col-6">
                    <label for="pharmaceutical_establishment">Pharmceutical Establishment</label>
                    <Dropdown id="pharmaceutical_establishment" class="w-full"
                        v-model="globalInputs.pharmaceutical_establishment_id" :options="pharmaceuticalEstablishments"
                        optionLabel="name" optionValue="id" :filter="true"
                        placeholder="Select Pharmceutical Establishment" />

                </div>
                <div class="field col-12 md:col-6">
                    <label for="product_type">Product Type</label>
                    <Dropdown id="product_type" class="w-full" v-model="globalInputs.product_type"
                        :options="product_types" optionLabel="label" optionValue="value" @change="onTypeChange()" />
                </div>
                <template v-if="globalInputs.product_type != null" class="field col-12 md:col-6">
                    <template v-if="globalInputs.product_type == 'medication'">
                        <div class="field col-12 md:col-6">
                            <label for="medication_status">Status</label>
                            <Dropdown id="medication_status" class="w-full " v-model="globalInputs.status"
                                placeholder="Select Status" :options="medicationStatus" />
                        </div>
                        <div class="field col-12 md:col-6">
                            <label for="medication">Medication</label>
                            <Dropdown id="medication" class="w-full" v-model="medicationData.name"
                                :options="medications" :filter="true" optionLabel="name"
                                placeholder="Select a Medication" filterPlaceholder="Find a Medication"
                                optionValue="name" />
                        </div>
                        <div class="field col-12 md:col-6">
                            <label for="presentation">Presentation</label>
                            <Dropdown id="presentation" class="w-full " v-model="medicationData.presentation_id"
                                :options="presentations" :filter="true" optionLabel="value" optionValue="id"
                                placeholder="Select a Presentation" filterPlaceholder="Find a Presentation" />

                        </div>
                        <div class="field col-12 md:col-6">
                            <label for="form">Form</label>
                            <Dropdown id="form" class="w-full" v-model="medicationData.form_id" :options="forms"
                                :filter="true" optionLabel="value" optionValue="id" placeholder="Select a Form"
                                filterPlaceholder="Find a Form" />
                        </div>
                        <div class="field col-12 md:col-6">
                            <label for="dosage">Dosage</label>
                            <Dropdown id="dosage" class="w-full" v-model="medicationData.dosage_id" :options="dosages"
                                :filter="true" optionLabel="value" optionValue="id" placeholder="Select a Dosage"
                                filterPlaceholder="Find a Dosage" />
                        </div>
                        <div class="field col-12 md:col-6">
                            <label for="dci">Actif Ingredient</label>
                            <Dropdown id="dci" class="w-full " v-model="medicationData.dci_id" :options="dcis"
                                :filter="true" optionLabel="value" optionValue="id"
                                placeholder="Select an Actif Ingredient" filterPlaceholder="Find an Actif Ingredient" />
                        </div>

                    </template>
                    <template v-if="globalInputs.product_type == 'device'">
                        <div class="field col-12 md:col-6">
                            <label for="device_status">Status</label>
                            <Dropdown id="device_status" class="w-full" v-model="globalInputs.status"
                                :options="deviceStatus" placeholder="Select Status" />
                        </div>
                        <div class="field col-12 md:col-6">
                            <label for="device">Device</label>
                            <Dropdown id="device" class="w-full" v-model="deviceData.name" :options="devices"
                                :filter="true" optionLabel="name" optionValue="name" placeholder="Select a Device"
                                filterPlaceholder="Find a Device" />


                        </div>

                        <div class="field col-12 md:col-6">
                            <label for="designation">Designation</label>
                            <Dropdown id="designation" class="w-full" v-model="deviceData.designation_id"
                                :options="designations" :filter="true" optionLabel="value" optionValue="id"
                                placeholder="Select a Designation" filterPlaceholder="Find a Designation" />


                        </div>
                        <div class="field col-12 md:col-6">
                            <label for="classification">Classification</label>
                            <Dropdown id="classification" class="w-full" v-model="deviceData.classification_id"
                                :options="classifications" :filter="true" optionLabel="value" optionValue="id"
                                placeholder="Select a Classification" filterPlaceholder="Find a Classification" />


                        </div>

                    </template>
                    <Button class="field col-12 py-2" label="Search" icon="pi pi-search" type="submit" />

                </template>
            </div>
        </form>
    </UserLayoutVue>
</template>

<script>
import { ref } from "@vue/reactivity";
import UserLayoutVue from "../Layouts/UserLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { medicationStatus, deviceStatus } from "../helpers/services"
export default {
    components: {
        UserLayoutVue,
    },
    setup(props) {


        const getInitialMedicationData = () => {
            return {
                name: null,
                dci_id: null,
                presentation_id: null,
                form_id: null,
                dosage_id: null,
            }
        }

        const getInitialDeviceData = () => {
            return {
                name: null,
                designation_id: null,
                classification_id: null,

            }
        }
        const medicationData = ref(getInitialMedicationData())
        const deviceData = ref(getInitialDeviceData())
        const globalInputs = ref({
            code: "",
            status: "",
            product_type: null,
            pharmaceutical_establishment_id: null,
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

        function search() {
            let data;
            if (globalInputs.value.product_type == 'medication') {
                data = {
                    product_type: globalInputs.value.product_type,
                    technicalFileData: {
                        code: globalInputs.value.code,
                        status: globalInputs.value.status,
                    },
                    medicationData: { ...medicationData.value, pharmaceutical_establishment_id: globalInputs.value.pharmaceutical_establishment_id }
                }
            } else if (globalInputs.value.product_type == 'device') {
                data = {
                    product_type: globalInputs.value.product_type,
                    technicalFileData: {
                        code: globalInputs.value.code,
                        status: globalInputs.value.status,
                    },
                    deviceData: { ...deviceData.value, pharmaceutical_establishment_id: globalInputs.value.pharmaceutical_establishment_id }
                }
            } else {
                return;
            }
            Inertia.get('/dashboard/technicalfiles', { ...data })
        }



        const onTypeChange = () => {
            medicationData.value = getInitialMedicationData()
            deviceData.value = getInitialDeviceData();
            globalInputs.value.status = ""
        }

        const evaluateurs = () => {
            Inertia.get('/dashboard/evaluateurs');
        }


        return {
            search,
            medicationData,
            product_types,
            onTypeChange,
            deviceData,
            globalInputs,
            medicationStatus,
            deviceStatus,
            evaluateurs
        };
    },

    props: ["userData", "devices", "medications", "pharmaceuticalEstablishments", 'classifications',
        'designations',
        'dosages',
        'forms',
        'presentations',
        'dcis'],
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>


