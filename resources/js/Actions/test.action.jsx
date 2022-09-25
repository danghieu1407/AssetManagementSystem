import {exceptionConstants, testConstants} from "../Constants";
import TestServices from "../Services/test.services";

const {SUCCESS} = exceptionConstants
const {GET_USER} = testConstants

export const getUserTest = () => {
    return async function (dispatch) {
        const response = await TestServices.getUserTest()
        const users = response.data
        const code = response.code
        if (code === SUCCESS) {
            dispatch({
                type: GET_USER,
                payload: {
                    data: users,
                },
            })
        }
        return response
    }
}
