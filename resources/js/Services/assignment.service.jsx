import Repository from "./repository";

class AssignmentService {
    async getAssignmentById(id) {
        const endpoint = `/assignment/${id}`;
        const response = await Repository.get(endpoint);
        return response;
    }

    async getAssignmentEdit(id) {
        const endpoint = `/assignment/${id}/edit`;
        const response = await Repository.get(endpoint);
        return response;
    }

    async updateAssignmentInfo(data) {
        const endpoint = `/assignment/${data.assignmentInfo}?staff_id=${data.staffId}&asset_id=${data.assetId}&assigned_date=${data.assigned_date}&note=${data.note}`;
        const response = await Repository.put(endpoint);
        return response;
    }

    async viewAssignmentById(id) {
        const endpoint = `/view-assignment/${id}`;
        const response = await Repository.get(endpoint);
        return response;
    }
}
export default new AssignmentService();
