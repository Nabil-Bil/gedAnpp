<template>
    <div>
        <DashboardLayoutVue :UserData="user_data" :errors="errors">
            <template #Items>
                <div class="px-2">
                    <Button :disabled="!isDisabled" label="Add" icon="pi pi-plus" iconPos="left"
                        @click="addNewTechnicalFile"></Button>
                </div>
                <div class="px-2">
                    <form @submit.prevent="destroyTechnicalFiles" method="POST">
                        <Button :disabled="isDisabled" label="Delete" icon="pi pi-trash" iconPos="left"
                            class="p-button-danger px-2" type="submit"></Button>
                    </form>
                </div>
            </template>
            <DataTable :value="technical_files" stripedRows showGridlines :resizableColumns="true" :paginator="true"
                :rows="10" dataKey="code" responsiveLayout="scroll" v-model:selection="selectedTechnicalFiles"
                v-model:filters="filters" filterDisplay="menu" :globalFilterFields="[
                    'code',
                    'product_type',
                    'status',
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
                <template #empty> No Technical File found. </template>
                <template #loading> Loading Technical Files data. Please wait. </template>
                <Column selectionMode="multiple" headerStyle="width: 3em;" ></Column>
                <Column field="code" header="Code" style="width: 20%; text-align: center"  :sortable="true"></Column>
                <Column field="module_number" header="Module Number" style="width: 20%; text-align: center"  :sortable="true"></Column>
                <Column field="product_type" header="Product Type" style="width: 20%; text-align: center"  :sortable="true"></Column>
                <Column field="status" header="Status" style="width: 20%; text-align: center"  :sortable="true"></Column>
                <Column field="created_at" header="Created At" style="width: 20%; text-align: center"  :sortable="true"></Column>
            </DataTable>
        </DashboardLayoutVue>
    </div>
</template>

<script>
import DashboardLayoutVue from '../../Layouts/DashboardLayout.vue';
import { ref, computed } from 'vue';
import { FilterMatchMode, FilterOperator } from "primevue/api";
import { Inertia } from '@inertiajs/inertia';
export default {
    props: ['user_data', 'technical_files',"errors"],
    components: {
        DashboardLayoutVue
    },
    setup() {
        const selectedTechnicalFiles = ref([]);

        const isDisabled = computed({
            get() {
                return selectedTechnicalFiles.value.length == 0
                    ? true
                    : false;
            },
        });

        const filters = ref({
            global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            code: {
                operator: FilterOperator.AND,
                constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
            },
            status: {
                operator: FilterOperator.AND,
                constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
            },
            product_type: {
                operator: FilterOperator.AND,
                constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
            },
        });

        function clearFilter() {
            initFilters();
        }

        function initFilters() {
            filters.value = {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                code: {
                    operator: FilterOperator.AND,
                    constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
                },
                status: {
                    operator: FilterOperator.AND,
                    constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
                },
                product_type: {
                    operator: FilterOperator.AND,
                    constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
                },
            }
        }
        function addNewTechnicalFile() {
            Inertia.get('/dashboard/technicalfile/create')

        }
        function destroyTechnicalFiles() {
                  const selectedIds = [];

      selectedTechnicalFiles.value.forEach((element) => {
        selectedIds.push(element.code);
      });
      Inertia.post("/dashboard/technicalfile/destroy", {
        ids: [...selectedIds],
      });

      selectedTechnicalFiles.value = [];
        }
        return {
            filters,
            selectedTechnicalFiles,
            clearFilter,
            isDisabled,
            destroyTechnicalFiles,
            addNewTechnicalFile
        }


    }
}

</script>

<style>
span.p-column-title {
    width: 100%;
}

div.p-column-header-content {
    justify-content: center;
}
</style>