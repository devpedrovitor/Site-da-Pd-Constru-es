<script setup>
    import { RouterLink } from 'vue-router';
    import { useCartStore } from '@/stores/cart.js';

    const cart = useCartStore();

    function formatPrice(price) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(price);
    }

    function increaseQuantity(itemId) {
        const item = cart.items.find(i => i.id === itemId);

        if (item) {
            item.quantity += 1;

            cart.updateCart();
        }
    }

    function decreaseQuantity(itemId) {
        const item = cart.items.find(i => i.id === itemId);

        if (item && item.quantity > 1) {
            item.quantity -= 1;

            cart.updateCart();
        }
    }

    function removeItem(itemId) {
        cart.removeItem(itemId);
    }
</script>

<template>
    <div class="min-h-dvh">
        <div class="max-w-3xl mx-auto p-5 flex flex-col min-h-dvh">
            <h1 class="text-2xl font-semibold mb-4">Carrinho de Compras</h1>

            <div v-if="cart.items.length === 0" class="text-center text-gray-500 flex justify-center items-center flex-1">
                <h2 class="text-lg">Seu carrinho está vazio.</h2>
            </div>

            <div v-else class="flex-1">
                <ul class="space-y-4">
                    <li v-for="item in cart.items" :key="item.id" class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-md">
                        <div class="flex items-center space-x-4">
                            <img :src="item.image" :alt="item.name" class="w-16 h-16 object-cover rounded-md">

                            <div>
                                <p class="font-medium text-gray-800">{{ item.name }}</p>

                                <p class="text-sm text-gray-500">Preço: {{ formatPrice(item.price) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <button @click="decreaseQuantity(item.id)" :disabled="item.quantity <= 1" class="bg-gray-300 text-gray-600 px-3 py-1 rounded-md hover:bg-gray-400 focus:outline-none disabled:opacity-50">-</button>
                            
                                <p class="text-gray-600">{{ item.quantity }}</p>

                                <button @click="increaseQuantity(item.id)" class="bg-gray-300 text-gray-600 px-3 py-1 rounded-md hover:bg-gray-400 focus:outline-none">+</button>
                            </div>

                            <button @click="removeItem(item.id)" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none">Remover</button>
                        </div>
                    </li>
                </ul>

                <div class="mt-8 flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
                    <p class="font-semibold text-xl">Total</p>

                    <p class="text-xl font-semibold text-gray-800">{{ formatPrice(cart.totalPrice) }}</p>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <RouterLink :to="{ name: 'checkout' }" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none">
                        Finalizar compra
                    </RouterLink>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <RouterLink :to="{ name: 'home' }" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 focus:outline-none">
                    Voltar
                </RouterLink>
            </div>
        </div>
    </div>
</template>
