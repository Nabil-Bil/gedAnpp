<!-- <template>
  <div>
    <DashboardLayoutVue :UserData="user_data">
      <template #Items>
        <div class="px-2">
          <Button :disabled="!isDisabled" label="Add" icon="pi pi-plus" iconPos="left" @click="addNewDevice"></Button>
        </div>
        <div class="px-2">
          <form @submit.prevent="destroyDevices" method="post">
            <Button :disabled="isDisabled" label="Delete" icon="pi pi-trash" iconPos="left" class="p-button-danger px-2"
              type="submit"></Button>
          </form>
        </div>
      </template>
      <div class="card">
        <DataTable stripedRows showGridlines :resizableColumns="true" :paginator="true" :rows="10" :value="devices"
          editMode="row" dataKey="code" v-model:editingRows="editingRows" @row-edit-save="onRowEditSave"
          responsiveLayout="scroll" v-model:selection="selectedDevices" v-model:filters="filters" filterDisplay="menu"
          :globalFilterFields="[
            'code',
            'name',
            'type',
            'mobile',
            'de_holder',
            'designation',
            'classification',
            'characteristic',
            'pharmaceutical_establishment',
          ]">
          <template #header>
            <div class="flex justify-between">
              <Button type="button" icon="pi pi-filter-slash" label="Clear" class="p-button-outlined"
                @click="clearFilter()" />
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
              </span>
            </div>
          </template>
          <template #empty> No Device found. </template>
          <template #loading> Loading Device data. Please wait. </template>
          <Column selectionMode="multiple" style="width: 5%; text-align: center; justify-content: center"></Column>
          <Column header="Code" style="width: 10%; text-align: center" field="code" :sortable="true">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column header="Name" style="width: 10%; text-align: center" field="name" :sortable="true">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column header="Type" style="width: 10%; text-align: center" field="type" :sortable="true">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column header="DE holder" style="width: 10%; text-align: center" field="de_holder" :sortable="true">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column header="Designation" style="width: 10%; text-align: center" field="designation" :sortable="true">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column header="Classification" style="width: 10%; text-align: center" field="classification"
            :sortable="true">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column header="Characteristic" style="width: 10%; text-align: center" field="characteristic"
            :sortable="true">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column header="Pharmaceutical Establishment" style="width: 10%; text-align: center"
            field="pharmaceutical_establishment" :sortable="true">
            <template #editor="{ data, field }">
              <Dropdown v-model="data[field]" :options="pharmaceutical_establishments" optionLabel="name"
                placeholder="Select New Pharmaceutical Establishment" :filter="true"
                filterPlaceholder="Find Pharmaceutical Establishment" />
            </template>
          </Column>
                <Column header="Status" style="width: 10%; text-align: center" field="status" :sortable="true">

        <template #editor="{ data, field }">

          <Checkbox v-model="data[field]" :binary="true" trueValue="Essential" falseValue="Not Essential"></Checkbox>
        </template>
      </Column>
          <Column :sortable="true" field="created_at" header="Created At" style="width: 10%; text-align: center">
          </Column>
          <Column :rowEditor="true" style="width: 1%; min-width: 8rem" bodyStyle="text-align:center">
          </Column>
        </DataTable>
      </div>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import { ref, computed, watch } from "vue";
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { FilterMatchMode, FilterOperator } from "primevue/api";

export default {
  components: {
    DashboardLayoutVue,
  },
  setup(props) {
    const selectedDevices = ref([]);
    const editingRows = ref([]);

    const isDisabled = computed({
      get() {
        return selectedDevices.value.length == 0 ? true : false;
      },
    });

    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      code: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      name: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      type: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      de_holder: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      designation: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      pharmaceutical_establishment: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      characteristic: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      duration: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      classification: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
    });

    function onRowEditSave(event) {
      let { newData, index } = event;

      let oldCode = props.devices[index].code;
      props.devices[index] = {
        code: newData.code,
        name: newData.name,
        type: newData.type,
        de_holder: newData.de_holder,
        designation: newData.designation,
        classification: newData.classification,
        characteristic: newData.characteristic,
        pharmaceutical_establishment_id: newData.pharmaceutical_establishment.id
          ? newData.pharmaceutical_establishment.id
          : newData.pharmaceutical_establishment_id,
        pharmaceutical_establishment: newData.pharmaceutical_establishment.name
          ? newData.pharmaceutical_establishment.name
          : newData.pharmaceutical_establishment,
          status:newData.status,
        created_at: newData.created_at,
      };

      Inertia.put("/dashboard/device/" + oldCode, props.devices[index]);
    }

    function destroyDevices() {
      const selectedCodes = [];

      selectedDevices.value.forEach((element) => {
        selectedCodes.push(element.code);
      });
      Inertia.post("/dashboard/device/destroy", { codes: [...selectedCodes] });

      selectedDevices.value = [];
    }
    function addNewDevice() {
      Inertia.get("/dashboard/device/create");
    }

    function clearFilter() {
      initFilters();
    }

    function initFilters() {
      filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        code: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        name: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        type: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        de_holder: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        designation: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        pharmaceutical_establishment: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        characteristic: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        classification: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
      };
    }
    return {
      selectedDevices,
      editingRows,
      onRowEditSave,
      isDisabled,
      addNewDevice,
      destroyDevices,
      filters,
      clearFilter,
    };
  },
  props: ["user_data", "devices", "pharmaceutical_establishments"],
};
</script>

<style>
span.p-column-title {
  width: 100%;
}

div.p-column-header-content {
  justify-content: center;
}
</style>

 -->

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
