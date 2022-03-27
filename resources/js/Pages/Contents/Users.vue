<template>
  <div>
    <DashboardLayoutVue :UserData="user_data">
      <template #Items>
        <div class="flex">
          <div class="px-2">
            <Button
              :disabled="!isDisabled"
              label="Add"
              icon="pi pi-plus"
              iconPos="left"
              @click="addNewUser"
            ></Button>
          </div>
          <div class="px-2">
            <form @submit.prevent="destroyUsers" method="post">
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
        </div>
      </template>
      <div class="card">
        <DataTable
          :value="allUsers"
          editMode="row"
          dataKey="id"
          v-model:editingRows="editingRows"
          @row-edit-save="onRowEditSave"
          responsiveLayout="scroll"
          v-model:selection="selectedUsers"
        >
          <Column selectionMode="multiple" headerStyle="width: 3em"></Column>
          <Column field="first_name" header="First name" style="width: 20%">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" autofocus />
            </template>
          </Column>
          <Column field="last_name" header="Last name" style="width: 20%">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" autofocus />
            </template>
          </Column>
          <Column field="email" header="Email" style="width: 20%">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column field="role" header="Role" style="width: 20%">
            <template #editor="{ data, field }">
              <InputText v-model="data[field]" />
            </template>
          </Column>
          <Column field="direction_name" header="Direction" style="width: 20%">
            <template #editor="{ data, field }">
              <Dropdown
                v-model="data[field]"
                :options="directions"
                optionLabel="name"
                placeholder="Select New Direction"
                class="w-full"
              />
            </template>
          </Column>
          <Column
            :rowEditor="true"
            style="width: 10%; min-width: 8rem"
            bodyStyle="text-align:center"
          >
          </Column>
          <Column
            style="width: 10%; min-width: 8rem"
            bodyStyle="text-align:center"
          >
          </Column>
        </DataTable>
      </div>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import { ref, computed, watch } from "vue";
import DashboardLayoutVue from "../Layouts/DashboardLayout.vue";
import { Inertia } from "@inertiajs/inertia";
export default {
  components: {
    DashboardLayoutVue,
  },
  setup(props) {
    const selectedUsers = ref([]);
    const editingRows = ref([]);
    const allUsers = ref([]);

    props.users.forEach((element) => {
      allUsers.value.push({
        id: element.id,
        first_name: element.first_name,
        last_name: element.last_name,
        role: element.role,
        email: element.email,
        direction_id: element.direction.id,
        direction_name: element.direction.name,
      });
    });
    const isDisabled = computed({
      get() {
        return selectedUsers.value.length == 0 ? true : false;
      },
    });

    function onRowEditSave(event) {
      let { newData, index } = event;
      allUsers.value[index] = newData;
      allUsers.value[index].direction_id = newData.direction_name.id;
      allUsers.value[index].direction_name = newData.direction_name.name;
    }

    function destroyUsers() {
      const selectedIds = [];

      selectedUsers.value.forEach((element) => {
        selectedIds.push(element.id);
      });
      Inertia.post("/dashboard/users/destroy", { ids: [...selectedIds] });

      selectedUsers.value = [];
    }
    function addNewUser() {
      Inertia.get("/dashboard/register");
    }

    watch(
      () => [...props.users],
      (newVal) => {
        allUsers.value = [];
        props.users.forEach((element) => {
          allUsers.value.push({
            id: element.id,
            first_name: element.first_name,
            last_name: element.last_name,
            role: element.role,
            email: element.email,
            direction_id: element.direction.id,
            direction_name: element.direction.name,
          });
        });
      }
    );
    return {
      selectedUsers,
      editingRows,
      onRowEditSave,
      allUsers,
      isDisabled,
      addNewUser,
      destroyUsers,
    };
  },
  props: ["user_data", "users", "directions"],
};
</script>

