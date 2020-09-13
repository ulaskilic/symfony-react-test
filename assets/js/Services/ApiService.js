import {create} from "apisauce";

const api = create({baseURL: '', headers: {}});

export default {
    task: {
        list: async () => {
            return api.get('/api/task');
        },
        distribute: async () => {
            return api.get('/api/debug');
        }
    }
}