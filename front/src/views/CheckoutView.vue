<script setup>
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';
    import { useCartStore } from '@/stores/cart';

    const router = useRouter();
    const cart = useCartStore();

    const form = ref({
        cardNumber: '',
        cardholderName: '',
        expirationDate: '',
        cvv: ''
    });


    function formatPrice(price) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(price);
    }

    function formatCardNumber(event) {
        let value = event.target.value.replace(/\D/g, '');

        if (value.length > 4) {
            value = value.replace(/(\d{4})(\d{1,4})/, '$1 $2');
        } 

        if (value.length > 9) {
            value = value.replace(/(\d{4})(\d{1,4})(\d{1,4})/, '$1 $2 $3');
        };

        if (value.length > 14) {
            value = value.slice(0, 19);
        };

        form.value.cardNumber = value;
    }

    function formatExpirationDate(event) {
        let value = event.target.value.replace(/\D/g, '');
        
        if (value.length > 2) {
            value = value.replace(/(\d{2})(\d{1,2})/, '$1/$2');
        }
        
        if (value.length > 5) {
            value = value.slice(0, 5);
        }
        
        form.value.expirationDate = value;
    }

    function handleSubmit() {
        alert('Compra finalizada!');

        cart.clearCart();

        router.push('/');
    }
</script>

<template>
    <div class="min-h-dvh">
        <div class="max-w-3xl mx-auto p-6">
            <h2 class="text-2xl font-semibold mb-4">Checkout</h2>

            <div class="bg-gray-100 p-4 rounded-lg shadow-md mb-8">
                <h3 class="text-lg font-medium mb-4">Resumo da compra</h3>

                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Total:</span>

                    <span class="text-xl font-semibold text-gray-800">{{ formatPrice(cart.totalPrice) }}</span>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div class="space-y-2">
                    <label for="cardNumber" class="block text-sm font-medium text-gray-700">Número do cartão</label>

                    <input id="cardNumber" v-model="form.cardNumber" @input="formatCardNumber($event)" type="text" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o número do cartão" required>
                </div>

                <div class="space-y-2">
                    <label for="cardholderName" class="block text-sm font-medium text-gray-700">Nome no cartão</label>

                    <input id="cardholderName" v-model="form.cardholderName" type="text" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o nome no cartão" required>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label for="expirationDate" class="block text-sm font-medium text-gray-700">Data de validade</label>

                        <input id="expirationDate" v-model="form.expirationDate" @input="formatExpirationDate($event)" type="text" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="MM/AA" required>
                    </div>

                    <div class="space-y-2">
                        <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>

                        <input id="cvv" v-model="form.cvv" type="text" maxlength="3" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Código de segurança" required>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 focus:outline-none">
                        Finalizar Compra
                    </button>
                </div>
            </form>

            <div class="mt-4 flex justify-end">
                <RouterLink :to="{ name: 'cart' }" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 focus:outline-none">
                    Carrinho
                </RouterLink>
            </div>
        </div>
    </div>
</template>
