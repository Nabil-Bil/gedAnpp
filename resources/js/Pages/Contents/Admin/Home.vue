<template>
  <div>
    <DashboardLayoutVue :UserData="user_data">
      <div class="flex justify-around mt-36">
        <div>
          <Chart
            type="doughnut"
            :data="chartData"
            class="p-chart"
          ></Chart>
        </div>
        <div>
          <Chart
            type="pie"
            :data="chartData"

            class="p-chart"
          ></Chart>
        </div>
      </div>
      <div class=" flex justify-center items-center"><Chart type="radar" :data="radar_data" :height="600" :width="600"></Chart></div>
      
    </DashboardLayoutVue>
  </div>
</template>

<script>
import { ref } from "@vue/reactivity";
import DashboardLayoutVue from "../../Layouts/DashboardLayout.vue";
export default {
  components: {
    DashboardLayoutVue,
  },
  props: {
    user_data: Object,
    users_number: Array,
  },
  setup(props) {
    const labels=[];
    const data=[];
    props.users_number.forEach(element => {
      labels.push(element.role);
      data.push(element.number);
    });
    const chartData = ref({
      labels: labels,
      datasets: [
        {
          data:data,
          backgroundColor: ["#42A5F5", "#66BB6A", "#FFA726"],
          hoverBackgroundColor: ["#64B5F6", "#81C784", "#FFB74D"],
        },
      ],
    });


    const radar_data=ref({
                labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
                datasets: [
                    {
                        label: 'My First dataset',
                        backgroundColor: 'rgba(179,181,198,0.2)',
                        borderColor: 'rgba(179,181,198,1)',
                        pointBackgroundColor: 'rgba(179,181,198,1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(179,181,198,1)',
                        data: [65, 59, 90, 81, 56, 55, 40]
                    },
                    {
                        label: 'My Second dataset',
                        backgroundColor: 'rgba(255,99,132,0.2)',
                        borderColor: 'rgba(255,99,132,1)',
                        pointBackgroundColor: 'rgba(255,99,132,1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(255,99,132,1)',
                        data: [28, 48, 40, 19, 96, 27, 100]
                    }
                ]
            },)

    return {
      chartData,
      radar_data
    };
  },
};
</script>

