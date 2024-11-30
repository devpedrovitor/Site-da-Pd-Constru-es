import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            'name': null,
            'email': null,
            'accessToken': null,
            'isAuthenticated': false
        };
    },
    actions: {
        setState(data = { 'name': null, 'email': null, 'accessToken': null, 'isAuthenticated': false }) {
            this.name = data.name;
            
            this.email = data.email;
            
            this.accessToken = data.accessToken;
            
            this.isAuthenticated = data.isAuthenticated;
        }
    },
    persist: true
});
