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
          <Column field="role"  header="Role" style="width: 20%">
            <template #editor="{ data, field }">
              <Dropdown
                v-model="data[field]"
                :options="roles"
                optionLabel="key"
                optionValue="value"
                class="w-full"
              />
            </template>
          </Column>
          <Column field="direction_name" header="Direction" style="width: 20%">
            <template #editor="{ data, field }">
              <Dropdown
                v-model="data[field]"
                :options="directions"
                optionLabel="name"
                optionValue="name"
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
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { Inertia } from "@inertiajs/inertia";
export default {
  components: {
    DashboardLayoutVue,
  },
  setup(props) {
    const selectedUsers = ref([]);
    const editingRows = ref([]);
    const allUsers = ref([]);

    props.users.forEach(element => {
      allUsers.value.push(element)
    });

    const isDisabled = computed({
      get() {
        return selectedUsers.value.length == 0 ? true : false;
      },
    });

    const roles = [
      {
        key: "Admin",
        value: "administrateur",
      },
      {
        key: "Directeur",
        value: "directeur",
      },
      {
        key: "Evaluateur",
        value: "evaluateur",
      },
    ];

    function onRowEditSave(event) {
      let { newData, index } = event;
      allUsers.value[index]={
        direction_id : newData.direction_name.id,
        direction_name : newData.direction_name.name,
        email:newData.email,
        first_name: newData.first_name,
        last_name : newData.last_name,
        role: newData.role,
        id:newData.id
      }

      Inertia.put('/dashboard/users/'+ allUsers.value[index].id, allUsers.value[index]);
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
          allUsers.value.push(element);
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
      roles
    };
  },
  props: ["user_data", "users", "directions"],
};
</script>

