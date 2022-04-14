<template>
  <div>
    <DashboardLayoutVue :UserData="user_data">
      <template #Items>
        <div class="px-2">
          <Button
            :disabled="!isDisabled"
            label="Add"
            icon="pi pi-plus"
            iconPos="left"
            @click="addNewDirection"
          ></Button>
        </div>
        <div class="px-2">
          <form @submit.prevent="destroyDirections" method="post">
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

        <Dialog
          style="width:500px"
          header="Add New Direction"
          v-model:visible="display"
          :modal="true"
        >
          <form
            @submit.prevent="store"
            class="flex flex-col justify-around"
            id="directionForm"
            method="post"
          >
            <div class="mb-4 pt-6">
              <span class="p-float-label p-input-icon-left w-full my-4">
                <i class="pi pi-book" />
                <InputText
                  id="name"
                  type="text"
                  name="name"
                  v-model="name"
                  class="py-2 px-3 shadow-sm mt-1 block w-full"
                  :class="errors['name'] ? 'p-invalid' : ''"
                  
                />
                
                
                <label for="name">Name</label>
              </span>
              <small  v-if="errors['name']" class="p-error">{{
                errors['name']
              }}</small>
              
            </div>
            <div class="mb-4">
              <span class="p-float-label p-input-icon-left w-full my-4">
                <i class="pi pi-building" />
                <InputText
                  id="service"
                  type="text"
                  name="service"
                  v-model="service"
                  class="py-2 px-3 shadow-sm mt-1 block w-full"
                  :class="errors['service'] ? 'p-invalid' : ''"
                />
                <label for="service">Service</label>
              </span>
               <small  v-if="errors['service']" class="p-error">{{
                errors['service']
              }}</small>
            </div>
          </form>
          <template v-slot:footer>
            <Button
              label="Add"
              icon="pi pi-plus"
              iconPos="left"
              class="p-button-success"
              type="submit"
              form="directionForm"
              
            />
          </template>
        </Dialog>
      </template>
      <div class="card">
        <DataTable
          :paginator="true"
          :rows="10"
          showGridlines
          :resizableColumns="true"
          :value="allDirections"
          editMode="row"
          dataKey="id"
          v-model:editingRows="editingRows"
          @row-edit-save="onRowEditSave"
          responsiveLayout="scroll"
          v-model:selection="selectedDirections"
          v-model:filters="filters"
          filterDisplay="menu"
          :globalFilterFields="['name', 'service']"
        >
          <template #header>
            <div class="flex justify-between">
              <Button
                type="button"
                icon="pi pi-filter-slash"
                label="Clear"
                class="p-button-outlined"
                @click="clearFilter()"
              />
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText
                  v-model="filters['global'].value"
                  placeholder="Keyword Search"
                />
              </span>
            </div>
          </template>
          <template #empty> No Direction found. </template>
          <template #loading> Loading Directions data. Please wait. </template>

          <Column
            selectionMode="multiple"
            style="width: 5%; text-align: center; justify-content: center"
          ></Column>
          <Column
            :sortable="true"
            field="name"
            header="Name"
            style="width: 30%; text-align: center"
          >
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" autofocus />
            </template>
          </Column>
          <Column
            :sortable="true"
            field="service"
            header="Service"
            style="width: 30%; text-align: center"
          >
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column
            :sortable="true"
            field="created_at"
            header="Created At"
            style="width: 30%; text-align: center"
          >
            <template #editor="{ data, field }">
              <p>{{ data[field] }}</p>
            </template>
          </Column>
          <Column :rowEditor="true" style="width: 5%; text-align: center">
          </Column>
        </DataTable>
      </div>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { computed, reactive, ref, toRefs, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { FilterMatchMode, FilterOperator } from "primevue/api";

export default {
  components: {
    DashboardLayoutVue,
  },
  props: ["user_data", "directions","errors"],
  setup(props) {
    const editingRows = ref([]);
    const allDirections = ref(props.directions);
    const display = ref(false);
    const newDirection = reactive({
      name: "",
      service: "",
    });
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      name: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      service: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
    });
    const selectedDirections = ref([]);
    const isDisabled = computed({
      get() {
        return selectedDirections.value.length == 0 ? true : false;
      },
    });



    function onRowEditSave(event) {
      let { newData, index } = event;

      allDirections.value[index] = newData;
      Inertia.put(
        "/dashboard/directions/" + allDirections.value[index].id,
        newData
      );
    }
    function addNewDirection() {
      display.value = true;
    }

    watch(
      () => [...props.directions],
      (newVal) => {
        allDirections.value = newVal;
      }
    );
    function store() {
      if(props.errors==''){
        display.value = false;
        return
      }
      Inertia.post("/dashboard/directions", newDirection);
      newDirection.name = "";
      newDirection.service = "";
        display.value = true;


    }

    const destroyDirections = () => {
      const selectedIds = [];

      selectedDirections.value.forEach((element) => {
        selectedIds.push(element.id);
      });
      Inertia.post("/dashboard/directions/destroy", { ids: [...selectedIds] });

      selectedDirections.value = [];
    };

    function clearFilter() {
      initFilters();
    }

    function initFilters() {
      filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        name: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        service: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
      };
    }

    return {
      editingRows,
      allDirections,
      onRowEditSave,
      addNewDirection,
      display,
      ...toRefs(newDirection),
      store,
      selectedDirections,
      destroyDirections,
      isDisabled,
      filters,
      clearFilter,
    };
  },
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
