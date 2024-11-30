import CartView from '@/views/CartView.vue';
import HomeView from '@/views/HomeView.vue';
import LoginView from '@/views/LoginView.vue';
import { useUserStore } from '@/stores/user.js';
import CheckoutView from '@/views/CheckoutView.vue';
import RegisterView from '@/views/RegisterView.vue';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/login',
            name: 'login',
            component: LoginView,
            meta: {
                middleware: 'guest'
            }
        },
        {
            path: '/register',
            name: 'register',
            component: RegisterView,
            meta: {
                middleware: 'guest'
            }
        },
        {
            path: '/cart',
            name: 'cart',
            component: CartView
        },
        {
            path: '/checkout',
            name: 'checkout',
            component: CheckoutView,
            meta: {
                middleware: 'auth'
            }
        }
    ]
});

router.beforeEach((to, from) => {
    const user = useUserStore();

    if (to.matched.some(item => item.meta.middleware === 'auth')) {
        if (user.isAuthenticated) {
            return true;
        } else {
            return { name: 'login' };
        }
    }

    if (to.matched.some(item => item.meta.middleware === 'guest')) {
        if (user.isAuthenticated) {
            return { name: 'home' };
        } else {
            return true;
        }
    }

    return true;
});

export default router;
