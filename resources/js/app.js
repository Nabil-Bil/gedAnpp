require('./bootstrap');

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
//primevue imports
import Primevue from 'primevue/config'

import "primevue/resources/themes/saga-blue/theme.css"      //theme
import "primevue/resources/primevue.min.css"                 //core css
import "primeicons/primeicons.css"

//primevue componentes

import Sidebar from 'primevue/sidebar';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import CheckBox from 'primevue/checkbox'
import Chart from "primevue/chart"


createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });
    app.use(plugin)
      .use(Primevue)
      .component('Sidebar', Sidebar)
      .component('Button', Button)
      .component('InputText', InputText)
      .component('CheckBox', CheckBox)
      .component('Chart', Chart).mount(el)
  },
})