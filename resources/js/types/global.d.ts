import Echo from 'laravel-echo';
import Swal from 'sweetalert2';

declare global {
    interface Window {
        Echo: Echo;
        customSwal: typeof Swal,
    }

    const customSwal: typeof Swal
}

export {};
