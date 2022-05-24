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
import Dropdown from 'primevue/dropdown';
import DataTable from "primevue/datatable"
import Column from "primevue/column"
import Dialog from 'primevue/dialog';
import Splitter from 'primevue/splitter';
import SplitterPanel from 'primevue/splitterpanel';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import MultiSelect from 'primevue/multiselect'
import Checkbox from 'primevue/checkbox';
import TextArea from 'primevue/textarea'
import ProgressBar from 'primevue/progressbar';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';



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
      .component('Chart', Chart)
      .component('Dropdown',Dropdown)
      .component('DataTable',DataTable)
      .component('Column',Column)
      .component('Dialog',Dialog)
      .component('Splitter',Splitter)
      .component('SplitterPanel',SplitterPanel)
      .component('TabView',TabView)
      .component('TabPanel',TabPanel)
      .component('MultiSelect',MultiSelect)
      .component('Checkbox',Checkbox)
      .component('TextArea',TextArea)
      .component('ProgressBar',ProgressBar)
      .component('Accordion',Accordion)
      .component('AccordionTab',AccordionTab)

      .mount(el)
  },
})