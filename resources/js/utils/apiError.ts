import type { AxiosError } from 'axios';

/**
 * Tekst błędu do toasta. Serwer zwraca `message` tylko dla błędów aplikacji — odpowiedzi spoza
 * kontrolera (wygasła sesja, 5xx serwera WWW, brak sieci) mają puste ciało albo HTML, a wtedy
 * `toast.error(response.data.message)` pokazywał pusty czerwony toast.
 */
export const getApiErrorMessage = (error: unknown, fallback: string): string => {
    const axiosError = error as AxiosError<{ message?: string; error?: string }>;
    const response = axiosError?.response;

    console.error('Błąd żądania API', response?.status, response?.data ?? error);

    if (!response) {
        // Błąd sieci ma `request`; wyjątek rzucony w naszym własnym kodzie (nie w axiosie) — nie.
        return axiosError?.request ? 'Brak połączenia z serwerem' : fallback;
    }

    const data = response.data;
    const serverMessage = typeof data === 'object' && data !== null ? (data.message || data.error) : null;
    if (serverMessage) {
        return serverMessage;
    }

    switch (response.status) {
        case 401:
            return 'Zostałeś wylogowany — odśwież stronę i zaloguj się ponownie';
        case 403:
            return 'Nie masz uprawnień do tej akcji';
        case 419:
            return 'Sesja wygasła — odśwież stronę';
        default:
            return `${fallback} (błąd ${response.status})`;
    }
};
