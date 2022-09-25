import Repository from "./repository";

class UserService {
    async getUserInfo() {
        const endpoint = "/user-information";
        const response = await Repository.get(endpoint);
        return response;
    }

    async getUserEdit(id) {
        const endpoint = `/user/${id}/edit`;
        const response = await Repository.get(endpoint);
        return response;
    }

    async updateUserInfo(data) {
        const endpoint = `/user/${data.userId}?date_of_birth=${data.date_of_birth}&gender=${data.gender}&joined_date=${data.joined_date}&admin=${data.type}`;
        const response = await Repository.put(endpoint);
        return response;
    }

    async getUserList() {
        const endpoint = `/manageUser?sortByStaffCode=asc&sortByFullName=asc&sortByType=asc&no-paginate&search=`;
        const response = await Repository.get(endpoint);
        return response;
    }

    async getUserById(userId) {
        const endpoint = `/user/${userId}`;
        const response = await Repository.get(endpoint);
        return response;
    }
}
export default new UserService();
