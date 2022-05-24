<template>
    <UserLayoutVue :userData="userData">
        <template #navbar>
            <Button class="p-button-rounded p-button-link" icon="pi pi-arrow-left" @click="back()"></Button>
        </template>
        <template v-if="technicalFiles.length > 0">
            <Accordion>
                <template v-for="tf of tfs" :key="tf.code">
                    <AccordionTab>
                        <template #header>
                            <div class="flex justify-between w-full">
                                <h2>
                                    {{ tf.code }}
                                </h2>
                                <Dropdown @click.stop v-model="tfs[0].status" :modelValue="tfs[0].status" v-if="userData.role=='directeur'"></Dropdown>
                                <span v-else>Status : {{ tf.status }}</span>
                            </div>
                        </template>
                        <template v-if="tf.documents.length > 0">
                                                <div class="flex w-full justify-between py-2 border bg-gray-100 px-2 my-2 cursor-pointer hover:bg-gray-200 items-center" @click="viewDocument(document.id)"
                            v-for="document of tf.documents" :key="document.id">
                            <h3 class="text-xl font-bold">{{ document.name }}</h3>
                            <div class="text-lg flex flex-col items-center">

                                <span>Module : {{ document.module_number }}</span>
                                <div class="w-full h-[1px] bg-slate-400"></div>
                                <span> Created At : {{ document.created_at }}</span>
                            </div>
                        </div>
                        </template>
                        <div v-else class="text-center font-bold text-xl">
                            No Document for you
                        </div>
                    </AccordionTab>
                </template>
            </Accordion>
        </template>
        <div v-else class="flex justify-center items-center text-7xl font-bold h-[80vh]">
            No Technical File Found
        </div>


    </UserLayoutVue>
</template>


<script>
import { Inertia } from "@inertiajs/inertia";
import UserLayoutVue from "../Layouts/UserLayout.vue";
import { ref } from "vue";

export default {
    components: {
        UserLayoutVue,
    },
    setup(props) {
        const tfs = ref(props.technicalFiles);
        const back = () => {
            Inertia.get('/dashboard/');
        }
        const viewDocument =(id)=>{

            Inertia.get(`/dashboard/document/${id}`);

        }
        return {
            tfs,
            back,
            viewDocument
        }
    },
    props: ['userData', "technicalFiles"]
}
</script>

