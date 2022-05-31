<template>
    <UserLayoutVue :userData="userData" :errors="errors">
        <template #navbar>
            <Button class="p-button-rounded p-button-link" icon="pi pi-home" @click="home()"></Button>
            <div class="flex justify-self-center pl-[45%]">
                <div class="mx-2">
                    <Button class="p-button-raised p-button-rounded mx-2" icon="pi pi-plus" @click="zoom"></Button>
                </div>
                <div class="mx-2">
                    <Button class="p-button-raised p-button-rounded " icon="pi pi-minus" @click="dezoom"></Button>
                </div>



            </div>
        </template>
        <template #profilePicture>
            <div class="mx-10">
                <Button @click="chatVisibility = true" class="p-button-rounded" icon="pi pi-comments"></Button>
            </div>
        </template>
        <div class="flex flex-col justify-center  items-center my-20 ">
            <div class="border-2 border-gray-900  m-5 w-max h-max" v-for="i in numberOfPages" :key="i">
                <VuePdfEmbed :source="document.path" :disableTextLayer="true" :disableAnnotationLayer="true"
                    :height="height.height" :page="1" @contextmenu.prevent :id="'page-' + i" />
            </div>
        </div>
        <Sidebar v-model:visible="chatVisibility" position="right" class="p-sidebar-md">
            <div class="h-[91vh]">
                <div class="border border-gray-200 rounded-md w-full h-[80vh] overflow-y-scroll break-words  scrollbar">
                    <div class="border-2 border-gray-600 rounded-md p-5 m-2" v-for="commentary of commentaries"
                        :key="commentary.id">
                        <div class="flex justify-between items-center">
                            <div class="flex">
                                <img class="w-10 h-10 mx-2" :src="commentary.user.path_image">
                                <div class="flex flex-col">
                                    <span class="font-bold text-md">{{ commentary.user.first_name }}
                                        {{ commentary.user.last_name }}</span>
                                    <span class="text-sm text-gray-400">{{ commentary.created_at }}</span>
                                </div>
                            </div>

                            <Button class=" p-button-rounded p-button-danger p-button-outlined"
                                v-if="commentary.user_id == userData.id" icon="pi pi-trash"
                                @click="destroy(commentary.id)"></Button>

                        </div>

                        <div class="text-md font-medium my-3">
                            {{ commentary.content }}
                        </div>

                    </div>
                </div>
                <div class="absolute bottom-0 left-0 bg-gray-200 w-full h-24 flex items-center px-5">
                    <span class="p-input-icon-left w-full">
                        <i class="pi pi-comment" />
                        <InputText v-model="comment" class="w-full" placeholder="Enter text here ..."
                            @keypress.enter="sendComment()"></InputText>
                    </span>
                    <i class="pi pi-send cursor-pointer mx-2 rotate-45 text-blue-400" @click="sendComment()" />




                </div>
            </div>


        </Sidebar>

    </UserLayoutVue>

</template>


<script>
import { Inertia } from "@inertiajs/inertia";
import UserLayoutVue from "../Layouts/UserLayout.vue";

import VuePdfEmbed from 'vue-pdf-embed'
import { ref, onMounted } from 'vue'
export default {
    setup(props) {

        const height = ref({
            level: 3,
            height: window.innerHeight
        })
        const chatVisibility = ref(false);
        const comment = ref('');
        onMounted(() => {
            const navbar = document.getElementsByTagName('nav')[0]
            navbar.classList.add('fixed')
            navbar.classList.add('z-50')

        })

        const zoom = () => {
            if (height.value.level < 7) {
                height.value.height += +200
                height.value.level += 1
            }

        }

        const dezoom = () => {
              if (height.value.level > 1) {
                height.value.height += -200
                height.value.level -= 1
            }
        }


        const home = () => {
            Inertia.get('/dashboard');
        }
        const sendComment = () => {
            if (comment.value == '') {
                return
            }
            Inertia.post(`/dashboard/document/${props.document.id}/sendComment`, { comment: comment.value })

            comment.value = ''
        }
        const destroy = (id) => {
            Inertia.delete(`/dashboard/document/${props.document.id}/destroyComment/${id}`, { comment: comment.value })
        }
        return {
            home,
            height,
            chatVisibility,
            comment,
            sendComment,
            destroy,
            zoom,
            dezoom

        }
    },
    components: {
        UserLayoutVue,
        VuePdfEmbed,


    },
    props: ['userData', 'document', 'numberOfPages', 'commentaries', "errors"],

}
</script>

<style scoped>
/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar {
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
}
</style>
