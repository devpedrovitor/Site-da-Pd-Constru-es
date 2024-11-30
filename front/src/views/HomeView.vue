<script setup>
    import axios from '@/axios.js';
    import { onMounted, ref } from 'vue';
    import { toast } from 'vue3-toastify';
    import { useRouter } from 'vue-router';
    import { useCartStore } from '@/stores/cart.js';
    import AppHeader from '@/partials/AppHeader.vue';

    const router = useRouter();
    const cart = useCartStore();

    const products = ref([]);
    const categories = ref([]);

    function filterByCategory(categoryId) {
        const oldCategory = categories.value.find(category => category.active == true);

        if (oldCategory) {
            oldCategory.active = false;
        }

        const newCategory = categories.value.find(category => category.id == categoryId);

        if (newCategory) {
            newCategory.active = true;
        }

        if (oldCategory?.id == newCategory?.id) {
            newCategory.active = false;

            axios.get('/products')
                .then(response => {
                    products.value = response.data.data
                });

            return;
        }

        axios.get('/products', { params: { category_id: categoryId }})
            .then(response => {
                products.value = response.data.data
            });
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(price);
    }

    onMounted(() => {
        axios.get('/categories')
            .then(response => {
                categories.value = response.data.data.map(category => {
                    return {
                        ...category,
                        active: false
                    }
                });
            });

        axios.get('/products')
            .then(response => {
                products.value = response.data.data
            });
    });
</script>

<template>
    <div class="min-h-dvh w-dvw relative">
        <AppHeader />

        <main class="p-5">
            <ul class="flex justify-center items-center gap-2 flex-wrap sm:justify-start sm:gap-4 mb-10">
                <li v-for="(category, index) in categories" :key="index" @click="filterByCategory(category.id);" :class="['p-2 border rounded', category.active ? 'bg-blue-500 text-white' : '']">{{ category.name }}</li>
            </ul>

            <ul class="flex justify-center items-center gap-2 flex-wrap sm:justify-start sm:gap-4">
                <li v-for="(product, index) in products" :key="index" class="h-96 w-44 border rounded flex flex-col">
                    <img :src="product.image" :alt="product.name" class="object-cover w-full flex-1">

                    <div class="flex-1 px-3 py-2">
                        <h1 class="font-medium mb-2">{{ product.name }}</h1>

                        <p class="text-sm mb-1">Preço: {{ formatPrice(product.price) }}</p>

                        <p class="text-sm mb-1">Quantidade: {{ product.quantity }}</p>

                        <p class="text-sm">Avaliações: {{ product.evaluations }}</p>
                    </div>

                    <div class="flex flex-col justify-center gap-2 px-2 pb-2">
                        <button @click="cart.addItem({id: product.id, name: product.name, price: product.price, image: product.image, quantity: 1}); toast('Produto adicionado ao carrinho com sucesso.', { type: 'success', closeOnClick: false, transition: 'slide' });" type="button" class="text-sm bg-blue-400 p-2 rounded text-white">Adicionar ao carrinho</button>

                        <button @click="cart.addItem({id: product.id, name: product.name, price: product.price, image: product.image, quantity: 1}); router.push('/checkout'); " type="button" class="text-sm bg-green-500 p-2 rounded text-white">Comprar</button>
                    </div>
                </li>
            </ul>
        </main>
    </div>
</template>
