<template>
  <DashboardLayoutVue :UserData="user_data" :errors="errors">
    <div class="flex flex-col justify-around my-5 px-10 ">
      <div class="
       px-10 p-2 card rounded-lg w-full bg-white shadow-xl">
        <h2 class="font-semibold text-xl">The technical files created over the whole year</h2>

        <Chart type="line" :options="fileDataOptions" :data='fileData' :height="300" :width="width" />
      </div>
      <div class="flex my-4 flex-wrap justify-between">
        <div class="
            px-10 p-5  card rounded-lg w-max bg-white shadow-xl">
          <h2 class="font-semibold text-lg">
           The number of users in each role

          </h2>


          <Chart type="doughnut" :data='usersData' :height="300" :options="{ responsive: false }"
            :width="width / 3.5" />
        </div>
        <div class="
            px-10 p-5  card rounded-lg w-max bg-white  shadow-xl">
          <Chart type="line" :options="fileDataOptions" :data='productData' :height="300" :width="width/1.5" />
        </div>
        
       
      </div>
      <div class="
            px-10 p-5  card rounded-lg w-full bg-white  shadow-xl">
          <h2 class="font-semibold text-lg">
            The number of medications and devices recorded over the whole year
          </h2>
          <Chart type="bar" :data='productData' :height="300" :options="{ responsive: false }" :width="width " />
        </div>
       
    </div>
  </DashboardLayoutVue>
</template>

<script>
import { ref } from "@vue/reactivity";
import { onMounted, onUnmounted } from "vue";
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
export default {
  components: {
    DashboardLayoutVue,
  },
  props: {
    user_data: Object,
    files: Array,
    errors: Object,
    users_number: Array,
    medications: Array,
    devices: Array,
  },
  setup(props) {
    const width = ref(window.innerWidth - 200);
    const fileData = ref(
      {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
          {
            label: 'Technical Files',
            data: props.files,
            fill: true,
            borderColor: '#42A5F5',
            tension: .4
          },

        ]
      }
    );
    let users_number = []
    props.users_number.map((value) => {
      users_number.push(value.number)
    })
    const usersData = ref({
      labels: ['Administrateur', 'Directeur', 'Evaluateur'],
      datasets: [
        {
          data: users_number,
          backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
          hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"]
        }
      ]
    })
    const productData = ({
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [
        {
          label: 'Medication',
          data: props.medications,
          backgroundColor: '#42A5F5',
        },
        {
          label: 'Device',
          backgroundColor: '#FFA726',
          data: props.devices,

        },
      ],

    })

    onMounted(() => {
      window.document.body.classList.add('bg-gray-100')
    })

    onUnmounted(() => {
      window.document.body.classList.remove('bg-gray-100')

    })

    const fileDataOptions = ref(
      {
        responsive: false,
        plugins: {
          legend: {
            labels: {
              color: '#495057'
            }
          },

        },
        scales: {
          x: {
            ticks: {
              color: '#495057'
            },
            grid: {
              color: '#ebedef'
            }
          },
          y: {
            ticks: {
              color: '#495057'
            },
            grid: {
              color: '#ebedef'
            }
          }
        }
      }
    );


    return {
      fileData,
      width,
      fileDataOptions,
      usersData,
      productData

    };
  },
};
</script>

