<script  setup>
    import axios from '@/axios.js';
    import { toast } from 'vue3-toastify';
    import { useRouter } from 'vue-router';
    import { RouterLink } from 'vue-router';
    import { useUserStore } from '@/stores/user.js';
    import { useLoading } from 'vue-loading-overlay';
    import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
    import { ShoppingCartIcon, UserIcon, ArrowRightStartOnRectangleIcon } from '@heroicons/vue/24/outline';

    const router = useRouter();
    const user = useUserStore();
    const loading = useLoading();

    function logout() {
        if (user.isAuthenticated) {
            toast.clearAll();

            const loader = loading.show({
                width: 100,
                height: 100,
                opacity: 0.5,
                color: '#FFFFFF',
                backgroundColor: '#000000'
            });

            axios.delete('/logout', { headers: { 'Authorization': `Bearer ${user.accessToken}` } })
                .then(() => {
                    user.setState();

                    loader.hide();

                    router.push({ name: 'login' });
                }).catch((error) => {
                    loader.hide();

                    if (error.response?.status == 500) {
                        toast('Erro interno, entre em contato com o administrador para obter mais informações.', {
                            type: 'error',
                            closeOnClick: false,
                            transition: 'slide'
                        });
                    }
                });
        }
    }
</script>

<template>
    <header class="h-36 bg-blue-500 flex flex-col justify-between p-5">
        <div class="h-16 w-full flex justify-between items-center">
            <h1 class="text-2xl text-white">PD Construção</h1>

            <div class="flex justify-center items-center gap-3">
                <RouterLink :to="{ name: 'cart' }" class="focus:outline-none">
                    <ShoppingCartIcon class="w-6 h-6 stroke-white" />
                </RouterLink>

                <RouterLink v-if="user.isAuthenticated != true" :to="{ name: 'login' }" class="focus:outline-none">
                    <UserIcon class="w-6 h-6 stroke-white" />
                </RouterLink>

                <Menu v-else as="div" class="relative inline-block text-left">
                    <div>
                        <MenuButton class="flex justify-center items-center">
                            <UserIcon class="w-6 h-6 stroke-white" />
                        </MenuButton>
                    </div>

                    <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
                        <MenuItems class="absolute right-0 mt-2 w-32 origin-top-right rounded bg-blue-400 text-white focus:outline-none">
                            <div class="p-1">
                                <MenuItem>
                                    <button @click="logout();" class="flex items-center w-full rounded p-2 text-sm">
                                        <ArrowRightStartOnRectangleIcon class="mr-2 mb-0.5 h-5 w-5 stroke-current" aria-hidden="true" />

                                        Sair
                                    </button>
                                </MenuItem>
                            </div>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>
        </div>

        <input type="text" class="w-full rounded py-2 px-2.5 outline-none max-w-xl" placeholder="Pesquisar">
    </header>
</template>
