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

    const data = ref({
        name: '',
        date_of_birth: '',
        cpf: '',
        phone: '',
        email: '',
        password: '',
        address: {
            street: '',
            number: '',
            neighborhood: '',
            city: '',
            state: '',
            zip_code: ''
        }
    });

    function formatCPF(value) {
        value = value.replace(/\D/g, '');

        if (value.length > 11) {
            value = value.slice(0, 11);
        }

        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }

        return value;
    };

    function formatPhone(value) {
        value = value.replace(/\D/g, '');

        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
    
        return value;
    };

    function formatCEP(value) {
        value = value.replace(/\D/g, '');

        if (value.length > 8) {
            value = value.slice(0, 8);
        }

        if (value.length == 8) {
            getAddress();
        }

        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
        }

        return value;
    }

    function getAddress() {
        axios.get(`https://viacep.com.br/ws/${data.value.address.zip_code}/json`)
            .then(response => {
                if (response.data.erro) {
                    return;
                } else {
                    data.value.address.street = response.data.logradouro;

                    data.value.address.neighborhood = response.data.bairro;

                    data.value.address.city = response.data.localidade;

                    data.value.address.state = response.data.estado;
                }
            })
            .catch(error => {
                console.error(error);
            });
    }

    function register(event) {
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

        for (let key in data.value) {
            if (data.value.hasOwnProperty(key)) {
                if (typeof data.value[key] === 'string') {
                    data.value[key] = data.value[key].trim();
                } else if (typeof data.value[key] === 'object' && data.value[key] !== null) {
                    for (let subKey in data.value[key]) {
                        if (data.value[key].hasOwnProperty(subKey)) {
                            if (typeof data.value[key][subKey] === 'string') {
                                data.value[key][subKey] = data.value[key][subKey].trim();
                            }
                        }
                    }
                }
            }
        }

        const dataCopy = JSON.parse(JSON.stringify(data.value));

        dataCopy['cpf'] = dataCopy['cpf'].replace(/\D/g, '');
        dataCopy['phone'] = dataCopy['phone'].replace(/\D/g, '');
        dataCopy['address']['zip_code'] = dataCopy['address']['zip_code'].replace(/\D/g, '');

        axios.post('/register', dataCopy)
            .then(response => {
                user.setState({ 'name': response.data.name, 'email': response.data.email, 'accessToken': response.data.access_token, 'isAuthenticated': true });

                loader.hide();

                router.push({ name: 'home' });
            }).catch((error) => {
                loader.hide();

                if (error.response?.status == 422) {
                    if (error.response?.data?.errors) {
                        Object.values(error.response.data.errors).forEach(errorMessages => {
                            errorMessages.forEach(message => {
                                toast(message, {
                                    type: 'warning',
                                    closeOnClick: false,
                                    transition: 'slide'
                                });
                            });
                        });
                    }
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
</script>

<template>
    <div class="min-h-dvh flex flex-col justify-center items-center p-5 py-10">
        <form @submit.prevent="register($event);" class="w-full max-w-md flex flex-col justify-center items-center border rounded py-10 mb-3">
            <input v-model="data.name" type="text" placeholder="Nome" class="border rounded p-3 w-80 outline-none text-sm mb-3" required>

            <input v-model="data.date_of_birth" type="date" class="border rounded p-3 w-80 outline-none text-sm mb-3" required>

            <input v-model="data.cpf" @input="data.cpf = formatCPF(data.cpf);" maxlength="14" type="text" placeholder="CPF" class="border rounded p-3 w-80 outline-none text-sm mb-3" required>

            <input v-model="data.phone" @input="data.phone = formatPhone(data.phone);" maxlength="15" type="text" placeholder="Telefone" class="border rounded p-3 w-80 outline-none text-sm mb-3" required>

            <input v-model="data.email" type="email" placeholder="Email" class="border rounded p-3 w-80 outline-none text-sm mb-3" required>

            <input v-model="data.password" type="password" placeholder="Senha" class="border rounded p-3 w-80 outline-none text-sm mb-6" required>

            <input v-model="data.address.zip_code" @input="data.address.zip_code = formatCEP(data.address.zip_code);" type="text" placeholder="CEP" class="border rounded p-3 w-80 outline-none text-sm mb-6" required>

            <input v-model="data.address.street" type="text" placeholder="Rua" class="border rounded p-3 w-80 outline-none text-sm mb-6" required>

            <input v-model="data.address.number" type="text" placeholder="Número" class="border rounded p-3 w-80 outline-none text-sm mb-6">

            <input v-model="data.address.neighborhood" type="text" placeholder="Bairro" class="border rounded p-3 w-80 outline-none text-sm mb-6" required>

            <input v-model="data.address.city" type="text" placeholder="Cidade" class="border rounded p-3 w-80 outline-none text-sm mb-6" required>

            <select v-model="data.address.state" class="border rounded p-3 w-80 outline-none text-sm mb-6" required>
                <option value="">Estado</option>
                <option value="Acre">Acre</option>
                <option value="Alagoas">Alagoas</option>
                <option value="Amapá">Amapá</option>
                <option value="Amazonas">Amazonas</option>
                <option value="Bahia">Bahia</option>
                <option value="Ceará">Ceará</option>
                <option value="Distrito Federal">Distrito Federal</option>
                <option value="Espírito Santo">Espírito Santo</option>
                <option value="Goiás">Goiás</option>
                <option value="Maranhão">Maranhão</option>
                <option value="Mato Grosso">Mato Grosso</option>
                <option value="Mato Grosso do Sul">Mato Grosso do Sul</option>
                <option value="Minas Gerais">Minas Gerais</option>
                <option value="Pará">Pará</option>
                <option value="Paraíba">Paraíba</option>
                <option value="Paraná">Paraná</option>
                <option value="Pernambuco">Pernambuco</option>
                <option value="Piauí">Piauí</option>
                <option value="Rio de Janeiro">Rio de Janeiro</option>
                <option value="Rio Grande do Norte">Rio Grande do Norte</option>
                <option value="Rio Grande do Sul">Rio Grande do Sul</option>
                <option value="Rondônia">Rondônia</option>
                <option value="Roraima">Roraima</option>
                <option value="Santa Catarina">Santa Catarina</option>
                <option value="São Paulo">São Paulo</option>
                <option value="Sergipe">Sergipe</option>
                <option value="Tocantins">Tocantins</option>
            </select>
            
            <button class="bg-blue-500 w-80 py-1.5 rounded text-white">CADASTRAR</button>
        </form>

        <RouterLink :to="{ name: 'login' }" class="focus:outline-none underline hover:text-blue-500">
            Login
        </RouterLink>
    </div>
</template>
