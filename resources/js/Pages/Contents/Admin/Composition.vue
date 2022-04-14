<template>
  <div>
    <DashboardLayoutVue :UserData="user_data">
      <template #Items>
        <div class="px-10">
          <Button @click="active = 0" class="p-button-text" label="Forms" />
          <Button @click="active = 1" class="p-button-text" label="Dosages" />
          <Button
            @click="active = 2"
            class="p-button-text"
            label="Presentations"
          />
        </div>
      </template>
      <TabView :activeIndex="active">
        <TabPanel header="Forms">
          <div class="card">
            <DataTable
              editMode="cell"
              @cell-edit-complete="onCellEditFormComplete"
              class="editable-cells-table"
              :paginator="true"
              :rows="10"
              showGridlines
              :value="forms"
              dataKey="id"
              v-model:selection="selectedForms"
              v-model:filters="filters"
              filterDisplay="menu"
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
                      />
                    </span>
                    <span class="px-2">
                      <Button
                        type="button"
                        icon="pi pi-trash"
                        label="Delete"
                        class="p-button-danger"
                        @click="destroyForms"
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
              <template #empty> No Form found. </template>
              <template #loading> Loading Form data. Please wait. </template>
              <Column
                selectionMode="multiple"
                style="width: 5%; text-align: center; justify-content: center"
              ></Column>
              <Column
                header="Index"
                style="width: 30%; text-align: center"
                field="index"
              ></Column>
              <Column
                :sortable="true"
                field="value"
                header="Form"
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
        </TabPanel>
        <TabPanel header="Dosages">
          <div class="card">
            <DataTable
              @cell-edit-complete="onCellEditDosageComplete"
              class="editable-cells-table"
              :paginator="true"
              :rows="10"
              showGridlines
              :value="dosages"
              editMode="cell"
              dataKey="id"
              v-model:selection="selectedDosages"
              v-model:filters="filters"
              filterDisplay="menu"
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
                      />
                    </span>
                    <span class="px-2">
                      <Button
                        type="button"
                        icon="pi pi-trash"
                        label="Delete"
                        class="p-button-danger"
                        @click="destroyDosages"
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
              <template #empty> No Dosage found. </template>
              <template #loading> Loading Dosage data. Please wait. </template>

              <Column
                selectionMode="multiple"
                style="width: 5%; text-align: center; justify-content: center"
              ></Column>
              <Column
                header="Index"
                style="width: 30%; text-align: center"
                field="index"
              ></Column>
              <Column
                :sortable="true"
                field="value"
                header="Dosage"
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
        </TabPanel>
        <TabPanel header="Presentations">
          <div class="card">
            <DataTable
              @cell-edit-complete="onCellEditPresentationComplete"
              class="editable-cells-table"
              :paginator="true"
              :rows="10"
              showGridlines
              :value="presentations"
              editMode="cell"
              dataKey="id"
              v-model:selection="selectedPresentations"
              v-model:filters="filters"
              filterDisplay="menu"
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
                      />
                    </span>
                    <span class="px-2">
                      <Button
                        type="button"
                        icon="pi pi-trash"
                        label="Delete"
                        class="p-button-danger"
                        @click="destroyPresentation"
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
              <template #empty> No Presentation found. </template>
              <template #loading>
                Loading Presentation data. Please wait.
              </template>
              <Column
                selectionMode="multiple"
                style="width: 5%; text-align: center; justify-content: center"
              ></Column>
              <Column
                header="Index"
                style="width: 30%; text-align: center"
                field="index"
              ></Column>
              <Column
                :sortable="true"
                field="value"
                header="Presentation"
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
        </TabPanel>
      </TabView>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { FilterMatchMode, FilterOperator } from "primevue/api";
import { ref } from "@vue/reactivity";
import { watch } from "@vue/runtime-core";

export default {
  components: {
    DashboardLayoutVue,
  },
  props: ["user_data", "forms", "dosages", "presentations"],
  setup(props) {
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      value: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
    });
    const active = ref(0);
    const editingRows = ref();
    const selectedForms = ref();
    const selectedDosages = ref();
    const selectedPresentations = ref();
    var indexPresentation = 1;
    var indexDosage = 1;
    var indexForm = 1;

    props.presentations.forEach((element) => {
      element["index"] = indexPresentation;
      indexPresentation++;
    });
    props.dosages.forEach((element) => {
      element["index"] = indexDosage;
      indexDosage++;
    });
    props.forms.forEach((element) => {
      element["index"] = indexForm;
      indexForm++;
    });

    function onCellEditPresentationComplete(event) {
      let { data, newValue, field } = event;
      Inertia.put("/dashboard/presentation/" + data["id"], { value: newValue });
    }
    function onCellEditDosageComplete(event) {
      let { data, newValue, field } = event;
      Inertia.put("/dashboard/dosage/" + data["id"], { value: newValue });
    }
    function onCellEditFormComplete(event) {
      let { data, newValue, field } = event;
      Inertia.put("/dashboard/form/" + data["id"], { value: newValue });
    }
    function destroyForms() {
      const selectedIds = [];

      selectedForms.value.forEach((element) => {
        selectedIds.push(element.id);
      });
      Inertia.post("/dashboard/form/destroy", { ids: [...selectedIds] });

      selectedForms.value = [];
    }
    function destroyDosages() {
      const selectedIds = [];

      selectedDosages.value.forEach((element) => {
        selectedIds.push(element.id);
      });
      Inertia.post("/dashboard/dosage/destroy", { ids: [...selectedIds] });

      selectedDosages.value = [];
    }
    function destroyPresentation() {
      const selectedIds = [];

      selectedPresentations.value.forEach((element) => {
        selectedIds.push(element.id);
      });
      Inertia.post("/dashboard/presentation/destroy", { ids: [...selectedIds] });

      selectedPresentations.value = [];
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
      () => [...props.presentations],
      (newVal) => {
        indexPresentation = 1;

        props.presentations.forEach((element) => {
          element["index"] = indexPresentation;
          indexPresentation++;
        });
      }
    );
    watch(
      () => [...props.presentations],
      (newVal) => {
        indexDosage = 1;

        props.dosages.forEach((element) => {
          element["index"] = indexDosage;
          indexDosage++;
        });
      }
    );

    watch(
      () => [...props.presentations],
      (newVal) => {
        indexForm = 1;

        props.forms.forEach((element) => {
          element["index"] = indexForm;
          indexForm++;
        });
      }
    );
    return {
      editingRows,
      selectedForms,
      active,
      onCellEditPresentationComplete,
      onCellEditDosageComplete,
      onCellEditFormComplete,
      filters,
      selectedDosages,
      selectedPresentations,
      destroyForms,
      destroyDosages,
      destroyPresentation,
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
.p-tabview-nav {
  display: none;
}
.p-tabview .p-tabview-panels {
  padding-left: 0px;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-right: 0px;
}
</style>
