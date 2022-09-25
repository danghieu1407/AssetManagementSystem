
import { userConstants, exceptionConstants } from "../Constants";
import AssignmentService from "../Services/assignment.service"
const {GET_ASSIGNMENT_INFO,GET_ASSIGNMENT_EDIT } = userConstants;
const { SUCCESS } = exceptionConstants;

export const getAssignmentInfo = () => {
    return async function (dispatch) {
        const response = await AssignmentService.getAssignmentInfo()
        const assignmentInfo = response.data
        const code = response.code
        if (code === SUCCESS) {
            dispatch({
                type: GET_ASSIGNMENT_INFO,
                payload: {
                    data: assignmentInfo,
                },
            })
        }
        return response
    }
}

export const getAssignmentEdit = (data) => {
    return async function (dispatch) {
        const response = await AssignmentService.getAssignmentEdit(data.assignmentInfo);
        const assignmentEdit = response.data
        const code = response.code
        if (code === SUCCESS) {
            dispatch({
                type: GET_ASSIGNMENT_EDIT,
                payload: {
                    value: data.displayValue || false,
                    data: assignmentEdit || ''
                },
            })
        }
        return response;
    }
}
