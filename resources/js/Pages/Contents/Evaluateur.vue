<template >
  <div class="flex justify-center " >
    <div class="border-2 border-gray-900 m-5 flex items-center flex-col">
      <div>
        <Button v-if="!isPrevButtonHidden" label="previous" class="mx-2" @click="prev"></Button>
        <Button v-if="!isNextButtonHidden" label="next" class="mx-2" @click="next"></Button>
        <Button  label="zoom" class="mx-2" @click="zoom"></Button>
        <Dropdown v-model="currentPage" :options="allPages"></Dropdown>
      </div>

      <VuePdfEmbed :source="path" :disableTextLayer="true" :disableAnnotationLayer="true" :width="width" 
        :page="currentPage" @contextmenu.prevent />
    </div>
  </div>
</template>

<script>
import VuePdfEmbed from 'vue-pdf-embed'
import { ref, computed } from 'vue'
export default {
  components: {
    VuePdfEmbed
  },
  setup(props) {
    const width=ref( window.innerWidth/3)
    
    const allPages=[]
    for(let i=1;i<=props.numberOfPages;i++){
      allPages.push(i)
    }
    const currentPage = ref(1);

    const isPrevButtonHidden = computed(() => {
      return currentPage.value == 1;
    })

    const isNextButtonHidden = computed(() => {
      return currentPage.value == props.numberOfPages
    })

    function next() {
      currentPage.value++;
    }

    function prev() {
       currentPage.value--;
    }
    function zoom(){
      console.log(width.value)
      width.value-=100
      console.log(width.value)

    }
    return {
      next,
      prev,
      currentPage,
      isPrevButtonHidden,
      isNextButtonHidden,
      allPages,
      zoom,
      width

    }
  },
  props: ['path', 'numberOfPages']
}
</script>

<style>
</style>