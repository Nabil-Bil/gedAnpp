<template >
  <div class="pharmaClass">
    <DashboardLayoutVue :UserData="user_data">
      <template #Items>
        <div class="px-2">
          <Button
            :disabled="!isDisabled"
            label="Add"
            icon="pi pi-plus"
            iconPos="left"
            @click="addNewPharmaceuticalEstablishement"
          ></Button>
        </div>
        <div class="px-2">
          <form
            @submit.prevent="destroyPharmaceuticalEstablishements"
            method="POST"
          >
            <Button
              :disabled="isDisabled"
              label="Delete"
              icon="pi pi-trash"
              iconPos="left"
              class="p-button-danger px-2"
              type="submit"
            ></Button>
          </form>
        </div>
      </template>
      <div class="card">
        <DataTable
          editMode="row"
          v-model:editingRows="editingRows"
          @row-edit-save="onRowEditSave"
          stripedRows
          showGridlines
          :resizableColumns="true"
          :paginator="true"
          :rows="10"
          :value="allPharmaceuticalEstablishment"
          dataKey="id"
          responsiveLayout="scroll"
          v-model:selection="selectedPharmaceuticalEstablishments"
        >
          <Column selectionMode="multiple" headerStyle="width: 3em"></Column>
          <Column
            v-for="col of columns"
            :key="col.field"
            :field="col.field"
            :header="col.header"
            style="width: 8%; text-align: center"
          >
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" autofocus />
            </template>
          </Column>

          <Column :rowEditor="true" style="width:5%; " bodyStyle="text-align:center"></Column>
        </DataTable>
      </div>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { computed, ref,watch } from "vue";
import { Inertia } from '@inertiajs/inertia';

export default {
  components: { DashboardLayoutVue },
  setup(props) {
    const columns = ref([
      { field: "name", header: "Name" },
      { field: "email", header: "Email" },
      { field: "fixed", header: "Fix Number" },
      { field: "mobile", header: "Mobile" },
      { field: "address", header: "Address" },
      { field: "nature", header: "nature" },
      { field: "fax", header: "Fax" },
      { field: "agreement", header: "Agreement" },
      { field: "status", header: "Status" },
      { field: "manager_name", header: "Manager Name" },
      { field: "tech_manager_name", header: "Technical Manager Name" },
      { field: "activity", header: "Activity" },
    ]);

    const selectedPharmaceuticalEstablishments = ref([]);
    const allPharmaceuticalEstablishment=ref(props.PharmaceuticalEstablishments);

    const isDisabled = computed({
      get() {
        return selectedPharmaceuticalEstablishments.value.length == 0
          ? true
          : false;
      },
    });
    const editingRows = ref([]);

    const addNewPharmaceuticalEstablishement = () => {
      Inertia.get('/dashboard/pharmaceuticalEstablishment/create/');
    };

    function destroyPharmaceuticalEstablishements(){
      const selectedIds = [];

      selectedPharmaceuticalEstablishments.value.forEach((element) => {
        selectedIds.push(element.id);
      });
      Inertia.post("/dashboard/pharmaceuticalEstablishment/destroy", { ids: [...selectedIds] });

      selectedPharmaceuticalEstablishments.value = [];
    };

    function onRowEditSave(event) {
      let { newData, index } = event;

      allPharmaceuticalEstablishment.value[index] = newData;
      Inertia.put(
        "/dashboard/pharmaceuticalEstablishment/" + allPharmaceuticalEstablishment.value[index].id,
        newData
      );
    }
        watch(
      () => [...props.PharmaceuticalEstablishments],
      (newVal) => {
        allPharmaceuticalEstablishment.value = [];
        props.PharmaceuticalEstablishments.forEach((element) => {
          allPharmaceuticalEstablishment.value.push(element);
        });
      }
    );
    return {
      addNewPharmaceuticalEstablishement,
      destroyPharmaceuticalEstablishements,
      isDisabled,
      columns,
      selectedPharmaceuticalEstablishments,
      editingRows,
      onRowEditSave,
      allPharmaceuticalEstablishment
    };
  },
  props: ["user_data", "PharmaceuticalEstablishments"],
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

