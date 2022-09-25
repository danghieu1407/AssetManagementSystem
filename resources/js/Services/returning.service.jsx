import Repository from "./repository";

class ReturningService {
    async getReturningById(id) {
        const endpoint = `/returning/${id}`;
        const response = await Repository.get(endpoint);
        return response;
    }

    async getReturningEdit(id) {
        const endpoint = `/returning/${id}/edit`;
        const response = await Repository.get(endpoint);
        return response;
    }

    async updateReturningInfo(data) {
        const endpoint = `/returning/${data.returningId}?state=${data.state}`;
        const response = await Repository.put(endpoint);
        return response;
    }

    async deleteReturningInfo(id) {
        const endpoint = `/returning/${id}`;
        const response = await Repository.delete(endpoint);
        return response;
    }
}
export default new ReturningService();
