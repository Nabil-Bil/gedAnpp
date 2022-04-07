<template>
  <div>
    <DashboardLayoutVue :UserData="user_data">
      <form class="card p-24" @submit.prevent="register" method="POST">
        <h2 class="pb-10 font-bold text-xl">Create New User</h2>
        <div class="formgrid grid">
          <div class="field col-12 md:col-6">
            <label for="firstname" >Firstname</label>
            <InputText
              id="firstname"
              class="w-full"
              v-model="firstName"
              :class="errors['firstName'] ? 'p-invalid' : ''"
            />
            <small v-if="errors['firstName']" class="p-error">{{
              errors["firstName"]
            }}</small>
          </div>
          <div class="field col-12 md:col-6">
            <label for="lastname">Lastname</label>
            <InputText id="lastname" class="w-full" v-model="lastName" :class="errors['lastName'] ? 'p-invalid' : ''"/>
            <small  v-if="errors['lastName']" class="p-error">{{
              errors["lastName"]
            }}</small>
          </div>
          <div class="field col-12 md:col-6">
            <label for="email">Email</label>
            <InputText id="email" class="w-full" v-model="email" :class="errors['email'] ? 'p-invalid' : ''"/>
            <small  v-if="errors['email']" class="p-error">{{
              errors["email"]
            }}</small>
          </div>
          <div class="field col-12 md:col-6">
            <label for="direction">Direction</label>
            <Dropdown
              id="direction"
              v-model="direction"
              :options="directions"
              optionLabel="name"
              optionValue="id"
              placeholder="Select Direction"
              class="w-full"
              :class="errors['direction'] ? 'p-invalid' : ''"
            />
            <small v-if="errors['direction']" class="p-error">{{
              errors["direction"]
            }}</small>
          </div>
          <div class="field col-12 md:col-6">
            <label for="password">Password</label>
            <InputText
              id="password"
              class="w-full"
              v-model="password"
              type="password"
              :class="errors['password'] ? 'p-invalid' : ''"
            />
            <small  v-if="errors['password']" class="p-error">{{
              errors["password"]
            }}</small>
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
              <label for="role">Roles</label>
              <Dropdown
              id='role'
                v-model="role"
                :options="roles"
                optionLabel="key"
                optionValue="value"
                placeholder="Select Role"
                class="w-full"
                :class="errors['role'] ? 'p-invalid' : ''"
              />
              <small  v-if="errors['role']" class="p-error">{{
                errors["role"]
              }}</small>
            </div>
            <Button label="Register" type="submit" />
          </div>
        </div>
      </form>
    </DashboardLayoutVue>
  </div>
</template>

<script>
import { reactive, toRefs, computed } from "@vue/reactivity";
import DashboardLayoutVue from "../Layouts/DashboardLayout.vue";
import { Inertia } from "@inertiajs/inertia";

export default {
  setup() {
    const data = reactive({
      firstName: "",
      lastName: "",
      email: "",
      password: "",
      password_confirmation: "",
      direction: "",
      role: "",
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

    function register() {
      Inertia.post("/dashboard/users", data);
    }
    return {
      register,
      ...toRefs(data),
      roles,
   
    };
  },
  components: {
    DashboardLayoutVue,
  },
  props: {
    user_data: Object,
    directions: Array,
    errors: Object,
  },
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>