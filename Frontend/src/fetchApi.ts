
import { BACKEND_URL } from "./config.ts";

export default async function fetchApi (route: string, options?: RequestInit) {
    let token = localStorage.getItem('token');
    return fetch(BACKEND_URL + route, {
        ...options,
        headers: {
            "Accept": "application/json",
            "Authorization": `Bearer ${token}`,
            ...options?.headers
        }
    })
        .then(async (res) => {
            if (res.status === 401) {
                localStorage.removeItem('token');
                window.location.href = '/login';
            }
            const json = await res.json();
            if (!res.ok) {
                throw new Error(json.message || 'HTTP ' + res.status);
            }
            return json;
        })
}