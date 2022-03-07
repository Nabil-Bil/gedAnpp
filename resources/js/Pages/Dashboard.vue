<template>
  <div class="">
    <div class="font-bold text-xl flex flex-row w-full justify-around">
      Nice You are connected to your dashboard
      <form @submit.prevent="logout">
        <button type="submit" class="text-red-500">Logout</button>
      </form>
    </div>
  </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
export default {
  setup() {
      const logout=()=>{
          Inertia.post('logout');
      }
    return {
        logout
    };
  },
};
</script>
