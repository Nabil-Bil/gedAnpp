<template>
  <div>
    <DashboardLayoutVue :UserData="user_data">
      <form class="card p-24" @submit.prevent="register" method="POST">
        <div class="formgrid grid">
          <div class="field col-12 md:col-6">
            <label for="firstname">Firstname</label>
            <InputText id="firstname" class="w-full" v-model="firstName" />
          </div>
          <div class="field col-12 md:col-6">
            <label for="lastname">Lastname</label>
            <InputText id="lastname" class="w-full" v-model="lastName" />
          </div>
          <div class="field col-12 md:col-6">
            <label for="email">Email</label>
            <InputText id="email" class="w-full" v-model="email" />
          </div>
          <div class="field col-12 md:col-6">
            <label for="lastname">Direction</label>
            <Dropdown
              v-model="direction"
              :options="directions"
              optionLabel="name"
              optionValue="id"
              placeholder="Select Direction"
              class="w-full"
            />
          </div>
          <div class="field col-12 md:col-6">
            <label for="password">Password</label>
            <InputText id="password" class="w-full" v-model="password" type="password" />
          </div>
          <div class="field col-12 md:col-6">
            <label for="passwordConfirmation">Confirm Password</label>
            <InputText
              id="passwordConfirmation"
              class="w-full"
              v-model="password_confirmation"
              type="password"
            />
          </div>
          <div class="flex w-full justify-between items-center">
            <div class="field col-12 md:col-6">
              <label for="lastname">Roles</label>
              <Dropdown
                v-model="role"
                :options="roles"
                optionLabel="key"
                optionValue="value"
                placeholder="Select Role"
                class="w-full"
              />
            </div>
            <Button label="Register" type="submit" :disabled="isDisabled"/>
          </div>
        </div>
      </form>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import { reactive, toRefs, computed } from "@vue/reactivity";
import DashboardLayoutVue from "../Layouts/DashboardLayout.vue";
import { Inertia } from '@inertiajs/inertia';

export default {
  setup() {
    const data = reactive({
      firstName: "",
      lastName: "",
      email: "",
      password: "",
      password_confirmation : "",
      direction: "",
      role: "",
    });
    const isDisabled = computed(() => {
      if (
        data.firstName == "" ||
        data.lastName == "" ||
        data.email == "" ||
        data.password == "" ||
        data.password_confirmation  == "" ||
        data.direction == "" ||
        data.role == ""
      ) {
        return true
      }
      return false
    });
    const roles = [
      {
        key: "Admin",
        value: "super admin",
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
    function register() {
      Inertia.post("/dashboard/users", data);
    }
    return {
      register,
      ...toRefs(data),
      roles,
      isDisabled
    };
  },
  components: {
    DashboardLayoutVue,
  },
  props: {
    user_data: Object,
    directions: Array,
  },
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>