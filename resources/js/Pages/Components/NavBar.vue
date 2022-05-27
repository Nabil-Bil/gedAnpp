<template>
    <nav class="
          flex
          justify-between
          py-3
          px-6
          bg-gray-50
          border-b
          space-x-5
          items-center
          w-full
        ">
        <slot></slot>
        <div></div>
        <div class="flex items-center">
            <slot name="profilePicture"></slot>
            <div class="profile-picture rounded-full cursor-pointer " @click="rightSideBarVisibility = true" :style="{
                backgroundImage: 'url(' + UserData.path_image + ')',
            }">
            </div>
        </div>

        <Sidebar v-model:visible="rightSideBarVisibility" position="right" class="p-sidebar-lg">
            <div class="flex flex-col justify-between h-full p-4">
                <div class="bg-gray-50 p-10 shadow-2xl my-5">

                    <div class="py-2">
                        <h2 class="text-xl font-bold ">Email : {{ UserData.email }}</h2>
                    </div>
                    <div class="py-2">
                        <h2 class="text-xl font-bold ">Direction Name : {{ UserData.direction }}</h2>
                    </div>

                </div>
                <div class="bg-gray-50 p-10 shadow-2xl my-5">
                    <div class="h-48 flex flex-col justify-around">
                        <span class="font-semibold">Photo</span>
                        <div v-if="profileData.profilePictureFile != null" class="relative w-max">
                            <div class="bg-cover w-24 h-24 rounded-full " :style="{
                                backgroundImage: 'url(' + profileData.profilePictureFile + ')',
                            }">
                            </div>

                            <div class="font-bold text-xl cursor-pointer absolute top-[-10px] right-[-10px]"
                                @click="removeFile">X</div>
                        </div>

                        <div v-else class="w-24 h-24 bg-cover rounded-full" :style="{
                            backgroundImage: 'url(' + UserData.path_image + ')',
                        }">
                        </div>


                        <div class="flex w-full justify-between">
                            <div></div>
                            <Button @click="profilePicture.click()" class="w-max">Select New Photo</Button>
                            <input type="file" class="hidden" ref="profilePicture" @change="changeProfilePicture"
                                accept="image/png, image/gif, image/jpeg">
                        </div>

                    </div>

                    <div class="py-2">
                        <label class="font-semibold" for="firstName">First Name</label>
                        <InputText placeholder="First Name..." id="firstName" v-model="profileData.firstName"
                            class="w-full" :class="errors.firstName ? 'p-invalid' : ''"></InputText>
                            <small id="firstName" class="p-error" v-if="errors.firstName">{{ errors.firstName }}</small>
                    </div>
                    <div class="py-2">
                        <label class="font-semibold" for="lastName">Last Name</label>
                        <InputText placeholder="Last Name..." id="lastName" v-model="profileData.lastName"
                            class="w-full" :class="errors.lastName ? 'p-invalid' : ''">
                        </InputText>
                        <small id="lastName" class="p-error" v-if="errors.lastName">{{ errors.lastName }}</small>
                    </div>

                    <Button label="Save" class="pi-button button-success" @click="editProdileData"></Button>
                </div>
                <div class="bg-gray-50 p-10 shadow-2xl my-5">

                    <div class="py-2">
                        <label class="font-semibold" for="currentPassword">Current Password</label>
                        <InputText type="text" id="currentPassword" v-model="password.current" class="w-full"
                            :class="errors.current ? 'p-invalid' : ''">
                        </InputText>
                        <small id="currentPassword" class="p-error" v-if="errors.current">{{ errors.current }}</small>
                    </div>
                    <div class="py-2">
                        <label class="font-semibold" for="newPassword">New Password</label>
                        <InputText type="password" id="newPassword" v-model="password.new" class="w-full"
                            :class="errors.new ? 'p-invalid' : ''">
                        </InputText>
                        <small id="newPassword" class="p-error" v-if="errors.new">{{ errors.new }}</small>
                    </div>
                    <div class="py-2">
                        <label class="font-semibold" for="passwordConfirmation">Confirm Password</label>
                        <InputText type="password" id="passwordConfirmation" v-model="password.new_confirmation"
                            class="w-full">
                        </InputText>
                    </div>
                    <Button label="Save" @click="editPassword" class="pi-button button-success"></Button>


                </div>
                <div class="py-2">
                    <Button label="Logout" class="p-button-text" @click="logout" />
                </div>
            </div>
        </Sidebar>
    </nav>
</template>


<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
export default {
    setup(props) {

        const profilePicture = ref();
        const profileData = ref({
            firstName: props.UserData.first_name,
            lastName: props.UserData.last_name,
            profilePictureFile: null
        });


        const password = ref({
            current: null,
            new: null,
            new_confirmation: null
        });
        const editProdileData = () => {
            Inertia.put('/dashboard/user/profile', { ...profileData.value })
            profileData.value.profilePictureFile = null

        }
        const editPassword = () => {
            Inertia.put('/dashboard/user/editpassword', { ...password.value });

            password.value.current = null;
            password.value.new = null;
            password.value.new_confirmation = null;

        }
        const changeProfilePicture = (e) => {
            let file = e.target.files;
            let reader = new FileReader
            reader.onload = e => {
                profileData.value.profilePictureFile = e.target.result
            }
            reader.readAsDataURL(file[0])

        }
        const rightSideBarVisibility = ref(false);
        function logout() {
            Inertia.post("/logout");
        }

        const removeFile = () => {
            profileData.value.profilePictureFile = null
            profilePicture.value.value = null

        }
        return {
            rightSideBarVisibility,
            logout,
            profilePicture,
            password,
            editPassword,
            profileData,
            changeProfilePicture,
            removeFile,
            editProdileData
        }

    },
    props: ['UserData', 'errors']
}
</script>

<style>
.profile-picture {
    width: 50px;
    height: 50px;
    background-size: cover;
}

.p-sidebar-content {
    height: 100%;

}
</style>