import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Laravel Echo + Pusher (Only initialize if properly configured)
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY || process.env.MIX_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER || process.env.MIX_PUSHER_APP_CLUSTER;

// Check if Pusher is properly configured (not empty or undefined)
if (pusherKey && pusherKey.trim() !== '' && pusherCluster && pusherCluster.trim() !== '') {
    import('pusher-js').then(({ default: Pusher }) => {
        import('laravel-echo').then(({ default: Echo }) => {
            window.Pusher = Pusher;
            
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: pusherKey,
                cluster: pusherCluster,
                forceTLS: true,
                encrypted: true,
            });
            
            console.log('Pusher initialized successfully');
        });
    });
} else {
    console.log('Pusher configuration not found - real-time features disabled');
    // Set empty objects to prevent errors
    window.Pusher = null;
    window.Echo = null;
}
