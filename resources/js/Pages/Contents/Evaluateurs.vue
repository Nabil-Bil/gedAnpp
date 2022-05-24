<template>
    <UserLayoutVue :userData="userData">
    <template #navbar>
        <Button class="p-button-rounded border p-button-link " icon="pi pi-arrow-left" @click="back()"></Button>
    </template>
    <div class="card">
        <DataTable
          stripedRows
          showGridlines
          :resizableColumns="true"
          :paginator="true"
          :rows="10"
          :value="users"
          dataKey="id"
          responsiveLayout="scroll"
          v-model:filters="filters"
          filterDisplay="menu"
          :globalFilterFields="[
            'first_name',
            'last_name',
            'email',
            'role',
          ]"
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
          <template #empty> No User found. </template>
          <template #loading> Loading Users data. Please wait. </template>


          <Column
            :sortable="true"
            field="first_name"
            header="First name"
            style="width: 20%; text-align: center"
          >

          </Column>
          <Column
            :sortable="true"
            field="last_name"
            header="Last name"
            style="width: 15%; text-align: center"
          >

          </Column>
          <Column
            :sortable="true"
            field="email"
            header="Email"
            style="width: 15%; text-align: center"
          >

          </Column>
          <Column
            :sortable="true"
            field="role"
            header="Role"
            style="width: 15%; text-align: center"
          >
           
          </Column>
          <Column
            field="created_at"
            header="Created At"
            style="width: 15%; text-align: center"
            :sortable="true"
          ></Column>
        </DataTable>
      </div>

    </UserLayoutVue>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import UserLayoutVue from "../Layouts/UserLayout.vue";
import { ref } from "vue";
import { FilterMatchMode, FilterOperator } from "primevue/api";

export default {
    components: {
        UserLayoutVue
    },

    setup() {
        const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      first_name: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      last_name: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      email: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      role: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
      direction: {
        operator: FilterOperator.AND,
        constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
      },
    });
        const back=()=>{
            Inertia.get('/dashboard')
        }
        function clearFilter() {
      initFilters();
    }

    function initFilters() {
      filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        first_name: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        last_name: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        email: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        role: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
        direction: {
          operator: FilterOperator.AND,
          constraints: [
            { value: null, matchMode: FilterMatchMode.STARTS_WITH },
          ],
        },
      };
    }
        return {
            back,
            filters,
            clearFilter
        }
    }, props: ['userData','users']
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