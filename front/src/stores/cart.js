import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => {
        return {
            items: [],
            totalPrice: 0,
            itemCount: 0
        };
    },
    actions: {
        addItem(item) {
            const existingItemIndex = this.items.findIndex(i => i.id === item.id);
            
            if (existingItemIndex !== -1) {
                this.items[existingItemIndex].quantity += item.quantity;
            } else {
                this.items.push(item);
            }
            
            this.updateCart();
        },
        removeItem(itemId) {
            this.items = this.items.filter(item => item.id !== itemId);

            this.updateCart();
        },
        updateCart() {
            this.totalPrice = this.items.reduce((total, item) => total + (item.price * item.quantity), 0);

            this.itemCount = this.items.reduce((count, item) => count + item.quantity, 0);
        },
        clearCart() {
            this.items = [];

            this.totalPrice = 0;

            this.itemCount = 0;
        }
    },
    persist: true
});
