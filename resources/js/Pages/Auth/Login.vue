<template>
  <div>
    <!-- component -->
    <div
      class="
        w-full
        min-h-screen
        flex flex-col
        justify-center
        items-center
        pt-6
        sm:pt-0
        bg-gray-100
      "
    >
      <div class="flex items-center justify-center bg-gray-50 p-36 rounded-xl shadow-xl">
        <img src="../assets/ANPP.svg" class="mr-20" />
        <form @submit.prevent="login" method="post">
          <div class="mb-4">
            <!-- <label class="block mb-1" for="email">Email-Address</label> -->
            <span class="p-float-label p-input-icon-left w-full my-4">
              <i class="pi pi-user" />
              <InputText
                id="email"
                type="text"
                name="email"
                v-model="email"
                class="py-2 px-3 shadow-sm mt-1 block w-full"
                :class="errors.email ? 'p-invalid': ''"
              />
              <label for="email">Email-Address</label>
            </span>
            <small id="email-error" class="p-error">{{errors.email}}</small>
          </div>
          <div class="mb-4">
              <span class="p-float-label p-input-icon-left w-full my-4">
                <i class="pi pi-lock" />
                
                <InputText
                  id="password"
                  type="password"
                  name="password"
                  v-model="password"
                  class="py-2 px-3 shadow-sm mt-1 block w-full"
                  :class="errors.password ? 'p-invalid': ''"
                />
                <label for="password">Password</label>
              </span>
              <small id="password-error" class="p-error">{{errors.password}}</small>
              
          </div>
          
          <div class="mt-6">
            <Button
              class="
                w-full
                inline-flex
                items-center
                justify-center
                px-4
                py-2
                font-semibold
                capitalize
              "
              type="submit"
            >
              sign In
            </Button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref, toRefs } from "@vue/reactivity";

export default {
  setup() {
    const data = reactive({
      email: "",
      password: "",
      remember_me: false,
    });
    const login = () => {
      Inertia.post("/", { ...data });
    };
    return {
      ...toRefs(data),
      login,
    };
  },
  props: {
    errors: Object,
  },
};
</script>
