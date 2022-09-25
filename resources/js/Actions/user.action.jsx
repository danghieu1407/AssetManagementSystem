
import { userConstants, exceptionConstants } from "../Constants";
import UserService from "../Services/user.service"
const { GET_USER_EDIT,GET_USER_CREATE,GET_USER_INFO } = userConstants;
const { SUCCESS, CREATED } = exceptionConstants;

// export const getUserInfo = (data) => {
//     return async function (dispatch) {
//     // console.log(0, data);
//         dispatch({
//             type: GET_USER_INFO,
//             payload: {
//                 data: data,
//             },
//         });
//     };
// };

export const getUserInfo = () => {
    return async function (dispatch) {
        const response = await UserService.getUserInfo()
        const userInfo = response.data
        const code = response.code
        if (code === SUCCESS) {
            dispatch({
                type: GET_USER_INFO,
                payload: {
                    data: userInfo,
                },
            })
        }
        return response
    }
}

export const getUserEdit = (displayValue = false,userId,sort_update_at='') => {
    return async function (dispatch) {
        const response = await UserService.getUserEdit(userId)
        const userEdit = response.data
        const code = response.code
        if (code === SUCCESS) {
            dispatch({
                type: GET_USER_EDIT,
                payload: {
                    value: displayValue,
                    data: userEdit,
                    sort_update_at: sort_update_at,
                },
            })
        }
        return response
    }
}

export const getUserCreate = (sort_update_at='',status) => {
    return async function (dispatch) {
        if (status === CREATED) {
            dispatch({
                type: GET_USER_CREATE,
                payload: {
                    value: false,
                    data: '',
                    sort_update_at: sort_update_at,
                },
            })
        }
    }
}


