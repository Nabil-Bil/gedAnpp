<template>
  <div class="card">
    <Dialog
      style="width: 500px"
      :header="'Add New ' + name"
      v-model:visible="display"
      :modal="true"
    >
      <form
        @submit.prevent="store"
        class="flex flex-col justify-around"
        id="compositionForm"
        method="POST"
      >
        <div class="mb-4 pt-6">
          <span class="p-float-label p-input-icon-left w-full my-4">
            <i class="pi pi-book" />
            <InputText
              id="value"
              type="text"
              name="value"
              v-model="value"
              class="py-2 px-3 shadow-sm mt-1 block w-full"
            />
            <label for="value">Value</label>
          </span>
        </div>
      </form>
      <template v-slot:footer>
        <Button
          label="Add"
          icon="pi pi-plus"
          iconPos="left"
          class="p-button-success"
          type="submit"
          form="compositionForm"
          :disabled="isDisabled"
        />
      </template>
    </Dialog>
    <DataTable
    stripedRows
      editMode="cell"
      @cell-edit-complete="onCellEditComplete"
      class="editable-cells-table"
      :paginator="true"
      :rows="10"
      showGridlines
      :value="data"
      dataKey="id"
      v-model:selection="selectedComposition"
      v-model:filters="filters"
      filterDisplay="menu"
      :globalFilterFields="['value']"
    >
      <template #header>
        <div class="flex justify-between">
          <div>
            <span class="px-2">
              <Button
                type="button"
                icon="pi pi-plus"
                label="Add"
                class="p-button-primary"
                @click="display = true"
                :disabled="!deleteDisabled"
              />
            </span>
            <span class="px-2">
              <Button
                type="button"
                icon="pi pi-trash"
                label="Delete"
                class="p-button-danger"
                @click="destroyComposition"
                :disabled="deleteDisabled"
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
      <template #empty> No {{ name }} found. </template>
      <template #loading> Loading {{ name }} data. Please wait. </template>
      <Column
        selectionMode="multiple"
        style="width: 5%; text-align: center; justify-content: center"
      ></Column>
      <Column
      :sortable="true"
        header="Index"
        style="width: 30%; text-align: center"
        field="index"
      ></Column>
      <Column
        :sortable="true"
        field="value"
        :header="name"
        style="width: 30%; text-align: center"
      >
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" autofocus />
        </template>
      </Column>
      <Column
        :sortable="true"
        field="created_at"
        header="Created At"
        style="width: 30%; text-align: center"
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
    let index = 1;
    props.data.forEach((element) => {
      element["index"] = index;
      index++;
    });
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      value: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
    });
    const display = ref(false);
   
    const selectedComposition = ref([]);
    const value = ref("");
    const deleteDisabled=computed(()=>{
        return selectedComposition.value.length==0;
    })
    const isDisabled=computed(()=>{
        return value.value==""
    })

    const store = () => {
      Inertia.post(props.url, { value: value.value });
        display.value=false;
        value.value=""
    };
    function onCellEditComplete(event) {
      let { data, newValue, field } = event;
      Inertia.put(props.url + "/" + data["id"], { value: newValue });
    }

    function destroyComposition() {
      const selectedIds = [];

      selectedComposition.value.forEach((element) => {
        selectedIds.push(element.id);
      });
      Inertia.post(props.destroyUrl, { ids: [...selectedIds] });

      selectedComposition.value = [];
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

    watch(
      () => [...props.data],
      (newVal) => {
        index = 1;

        props.data.forEach((element) => {
          element["index"] = index;
          index++;
        });
      }
    );
    return {
      filters,
      clearFilter,
      destroyComposition,
      onCellEditComplete,
      selectedComposition,
      display,
      store,
      value,
      isDisabled,
      deleteDisabled
    };
  },
  props: ["name", "destroyUrl", "url", "data", "errors"],
};
</script>

