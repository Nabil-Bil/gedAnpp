<template>
    <div class="flex min-h-screen ">
        <div class="flex-1">
            <NavBarVue :UserData="userData">
                <slot name="navbar"></slot>
                <template #profilePicture>
                    <slot name="profilePicture"></slot>
                </template>

            </NavBarVue>
            <main>
                <slot></slot>
            </main>
        </div>

    </div>

</template>


<script>
import NavBarVue from '../Components/NavBar.vue'

export default {
    components: { NavBarVue },
    setup() {

    },
    props: ['userData']
}
</script>