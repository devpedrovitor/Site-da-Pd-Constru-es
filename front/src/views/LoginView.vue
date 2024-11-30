<script setup>
    import { ref } from 'vue';
    import axios from '@/axios.js';
    import { toast } from 'vue3-toastify';
    import { useRouter } from 'vue-router';
    import { RouterLink } from 'vue-router';
    import { useUserStore } from '@/stores/user.js';
    import { useLoading } from 'vue-loading-overlay';

    const router = useRouter();
    const user = useUserStore();
    const loading = useLoading();

    const email = ref('');
    const password = ref('');

    function authenticate(event) {
        if (email.value.trim() && password.value.trim()) {
            toast.clearAll();

            const loader = loading.show({
                width: 100,
                height: 100,
                opacity: 0.5,
                color: '#FFFFFF',
                backgroundColor: '#000000'
            });

            const inputs = event.target.querySelectorAll('input');

            inputs.forEach(input => {
                input.blur();
            });

            const data = {
                'email': email.value.trim(),
                'password': password.value.trim()
            }

            axios.post('/login', data)
                .then(response => {
                    user.setState({ 'name': response.data.name, 'email': response.data.email, 'accessToken': response.data.access_token, 'isAuthenticated': true });

                    loader.hide();

                    router.push({ name: 'home' });
                }).catch((error) => {
                    loader.hide();

                    if (error.response?.status == 422 || error.response?.status == 401) {
                        toast('O email ou a senha informados estão incorretos. Por favor, verifique suas credenciais e tente novamente.', {
                            type: 'warning',
                            closeOnClick: false,
                            transition: 'slide'
                        });
                    }

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
    <div class="h-dvh flex flex-col justify-center items-center p-5">
        <form @submit.prevent="authenticate($event);" class="w-full max-w-md flex flex-col justify-center items-center border rounded py-10 mb-3">
            <input v-model="email" type="email" placeholder="Email" class="border rounded p-3 w-80 outline-none text-sm mb-3" required>

            <input v-model="password" type="password" placeholder="Senha" class="border rounded p-3 w-80 outline-none text-sm mb-6" required>
            
            <button class="bg-blue-500 w-80 py-1.5 rounded text-white">LOGIN</button>
        </form>

        <RouterLink :to="{ name: 'register' }" class="focus:outline-none underline hover:text-blue-500">
            Cadastre-se
        </RouterLink>
    </div>
</template>
