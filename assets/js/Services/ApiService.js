import {create} from "apisauce";

const api = create({baseURL: '', headers: {}});

export default {
    task: {
        list: async () => {
            return api.get('/api/task');
        },
        distribute: async () => {
            return api.get('/api/approach1');
        },
        distribute2: async () => {
            return api.get('/api/approach2');
        },
    }
}