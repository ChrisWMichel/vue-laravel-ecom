import axios from "axios";

export const api = axios.create({
    baseURL: (() => {
        // Use the current domain for API calls in all environments
        return `${window.location.origin}/api`;
    })(),
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

//export const BASE_URL = import.meta.env.VITE_API_URL || window.API_URL || "/api";
export const BASE_URL = api.defaults.baseURL;

// export const headersConfig = (token, contentType) => {
//     const config = {
//         headers: {
//             Authorization: `Bearer ${token}`,
//             "Content-type": contentType || "application/json",
//         },
//     };
//     return config;
// };

//create a unique ref for each product



export const makeUniqueId = (length) => {
    let result = "";
    const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
        result += characters.charAt(
            Math.floor(Math.random() * charactersLength)
        );
        counter += 1;
    }
    return result;
};

