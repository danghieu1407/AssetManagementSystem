import axios from 'axios'
// eslint-disable-next-line no-undef
const API_URL = process.env.MIX_API_URL

export default axios.create({
    baseURL: API_URL,
    headers: {
        'Content-type': 'application/json',
    },
})
