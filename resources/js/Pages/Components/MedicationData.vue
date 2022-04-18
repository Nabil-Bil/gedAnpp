<template>
  <div class="card">
    <DataTable
      stripedRows
      editMode="row"
      @cell-edit-complete="onRowEditSave"
      class="editable-cells-table"
      :paginator="true"
      :rows="10"
      showGridlines
      :value="medications"
      dataKey="code"
      v-model:selection="selectedMedication"
      v-model:filters="filters"
      filterDisplay="menu"
      v-model:editingRows="editingRows"
      @row-edit-save="onRowEditSave"
    >
      <template #header>
        <div class="flex justify-between">
          <div>
            <span class="px-2">
              <Button
                type="button"
                icon="pi pi-plus"
                label="Add"
                class="p-button-success"
                @click="addNewMedication"
                :disabled="!isDisabled"
              />
            </span>
            <span class="px-2">
              <Button
                type="button"
                icon="pi pi-trash"
                label="Delete"
                class="p-button-danger"
                @click="destroyMedication"
                :disabled="isDisabled"
              />
            </span>
            <span class="px-2">
              <Button
                type="button"
                icon="pi pi-filter-slash"
                label="Clear"
                class="p-button-outlined"
                @click="clearFilter()"
              />
            </span>
          </div>

          <span class="p-input-icon-left">
            <i class="pi pi-search" />
            <InputText
              v-model="filters['global'].value"
              placeholder="Keyword Search"
            />
          </span>
        </div>
      </template>
      <template #empty> No Medication found. </template>
      <template #loading> Loading Medication data. Please wait. </template>
      <Column
        selectionMode="multiple"
        style="width: 5%; text-align: center; justify-content: center"
      ></Column>
      <Column
        header="Code"
        style="width: 10%; text-align: center"
        field="code"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" />
        </template>
      </Column>
      <Column
        header="Name"
        style="width: 10%; text-align: center"
        field="name"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" />
        </template>
      </Column>
      <Column
        header="Type"
        style="width: 10%; text-align: center"
        field="type"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" />
        </template>
      </Column>
      <Column
        header="DE holder"
        style="width: 10%; text-align: center"
        field="de_holder"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" />
        </template>
      </Column>
      <Column
        header="Conditioning"
        style="width: 10%; text-align: center"
        field="conditioning"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" />
        </template>
      </Column>
      <Column
        header="Pharmaceutical Establishment"
        style="width: 10%; text-align: center"
        field="pharmaceutical_establishment"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <Dropdown
            v-model="data[field]"
            :options="pharmaceutical_establishments"
            optionLabel="name"
            placeholder="Select New Pharmaceutical Establishments"
            :filter="true"
            filterPlaceholder="Find Pharmaceutical Establishment"
            class="w-full"
          />
        </template>
      </Column>
      <Column
        header="Form"
        style="width: 10%; text-align: center"
        field="form"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <Dropdown
            v-model="data[field]"
            :options="forms"
            optionLabel="value"
            placeholder="Select New Form"
            :filter="true"
            filterPlaceholder="Find Form"
            class="w-full"
          />
        </template>
      </Column>
      <Column
        header="Dosage"
        style="width: 10%; text-align: center"
        field="dosage"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <Dropdown
            v-model="data[field]"
            :options="dosages"
            optionLabel="value"
            placeholder="Select New Dosage"
            :filter="true"
            filterPlaceholder="Find Dosage"
            class="w-full"
          />
        </template>
      </Column>
      <Column
        header="Presentation"
        style="width: 10%; text-align: center"
        field="presentation"
        :sortable="true"
      >
        <template #editor="{ data, field }">
          <Dropdown
            v-model="data[field]"
            :options="presentations"
            optionLabel="value"
            placeholder="Select New Presentation"
            :filter="true"
            filterPlaceholder="Find Presentation"
            class="w-full"
          />
        </template>
      </Column>
      <Column
        :sortable="true"
        field="created_at"
        header="Created At"
        style="width: 10%; text-align: center"
      >
      </Column>
      <Column
        :rowEditor="true"
        style="width: 1%; min-width: 8rem"
        bodyStyle="text-align:center"
      >
      </Column>
    </DataTable>
  </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import { FilterMatchMode, FilterOperator } from "primevue/api";
import { ref } from "@vue/reactivity";
import { computed, watch } from "@vue/runtime-core";

export default {
  setup(props) {
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      value: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
    });

    const editingRows = ref();
    const selectedMedication = ref([]);
    const isDisabled = computed(() => {
      return selectedMedication.value.length == 0;
    });

    function addNewMedication() {
      Inertia.get("/dashboard/medication/create");
    }
    function onRowEditSave(event) {
      let { newData, index } = event;
      console.log(props.medications[index]);
      
        let oldCode=props.medications[index].code;
      props.medications[index] = {
        code: newData.code,
        name: newData.name,
        type: newData.type,
        conditioning: newData.conditioning,
        de_holder: newData.de_holder,
        pharmaceutical_establishment_id: newData.pharmaceutical_establishment.id
          ? newData.pharmaceutical_establishment.id
          : newData.pharmaceutical_establishment_id,
        pharmaceutical_establishment: newData.pharmaceutical_establishment.name
          ? newData.pharmaceutical_establishment.name
          : newData.pharmaceutical_establishment,
        form_id: newData.form.id ? newData.form.id : newData.form_id,
        form: newData.form.value ? newData.form.value : newData.form,
        dosage_id: newData.dosage.id ? newData.dosage.id : newData.dosage_id,
        dosage: newData.dosage.value ? newData.dosage.value : newData.dosage,
        presentation_id: newData.presentation.id
          ? newData.presentation.id
          : newData.presentation_id,
        presentation: newData.presentation.value
          ? newData.presentation.value
          : newData.presentation,
          created_at:newData.created_at
      };

      


        Inertia.put("/dashboard/medication/"+ oldCode, props.medications[index]);
    }

    function destroyMedication() {
      const selectedCodes = [];

      selectedMedication.value.forEach((element) => {
        selectedCodes.push(element.code);
      });
      Inertia.post("/dashboard/medication/destroy", {
        codes: [...selectedCodes],
      });

      selectedMedication.value = [];
    }
    function clearFilter() {
      filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        value: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
      };
    }

    return {
      filters,
      clearFilter,
      destroyMedication,
      onRowEditSave,
      editingRows,
      selectedMedication,

      isDisabled,
      addNewMedication,
    };
  },
  props: [
    "forms",
    "dosages",
    "presentations",
    "medications",
    "pharmaceutical_establishments",
    "errors",
  ],
};
</script>

