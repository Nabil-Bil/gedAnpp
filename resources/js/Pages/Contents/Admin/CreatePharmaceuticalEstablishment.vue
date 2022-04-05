<template>
  <DashboardLayoutVue :UserData="user_data">
    <form class="card p-24" @submit.prevent="store" method="POST">
      <h2 class="pb-10 font-bold text-xl">
        Create New Pharmaceutical Establishment
      </h2>
      <div class="formgrid grid">
        <div class="field col-12 md:col-6" v-for="input of inputs" :key="input.id">
          <label :for="input.id">{{input.label}}</label>
          <InputText :id="input.id" class="w-full" v-model="input.value" />
        </div>

        <Button label="Register" type="submit" :disabled="isDisabled" />
      </div>
    </form>
  </DashboardLayoutVue>
</template>

<script>
import { ref,computed } from "@vue/reactivity";
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
import { Inertia } from '@inertiajs/inertia';
export default {
  components: {
    DashboardLayoutVue,
  },
  setup() {
    const inputs = ref([
      {
        id: "name",
        label: "Name",
        value:'',
      },
       {
        id: "email",
        label: "Email",
        value:'',

      },
       {
        id: "fixed",
        label: "Fix Number",
        value:'',

      },
       {
        id: "mobile",
        label: "Mobile",
        value:'',
      },
      {
        id: "fax",
        label: "Fax",
        value:'',
      },
       {
        id: "address",
        label: "Address",
        value:'',

      },
       {
        id: "nature",
        label: "Nature",
        value:'',

      },
       {
        id: "agreement",
        label: "Agreement",
        value:'',

      },
       {
        id: "status",
        label: "Status",
        value:'',

      },
       {
        id: "manager_name",
        label: "Manager Name",
        value:'',

      },
       {
        id: "tech_manager_name",
        label: "Technical Manager Name",
        value:'',

      },
       {
        id: "activity",
        label: "Activity",
        value:'',

      },
    ]);
    const isDisabled = computed(() => {
      var dis=false;
      inputs.value.forEach(element => {
        if(element.value==''){
          dis=true
        }
        
      });
      return dis
      
    });

    function store() {
        const data={}
      inputs.value.forEach(element => {
          const key=element.id;
          const value=element.value;
          data[key]=value
      });
        Inertia.post('/dashboard/pharmaceuticalEstablishment',data)

    }

    return {
      isDisabled,
      store,

      inputs
    };
  },
  props: ["user_data",'errors'],
};
</script>

<style scoped lang="css" src="primeflex/primeflex.css">
</style>