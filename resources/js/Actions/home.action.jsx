import {exceptionConstants, homeConstants} from "../Constants";
import HomeService from "../Services/home.service";

const {SUCCESS} = exceptionConstants
const {UPDATE_TITLE_HEADER} = homeConstants

// export const getTitleHeader = () => {
//     return async function (dispatch) {
//         const response = await HomeService.getTitleHeader()
//         const headerName = response.data
//         const code = response.code
//         if (code === SUCCESS) {
//             dispatch({
//                 type: GET_TITLE_HEADER,
//                 payload: {
//                     data: headerName,
//                 },
//             })
//         }
//         return response
//     }
// }
export const updateTitleHeader = (data) => {
    return async function (dispatch) {
        dispatch({
            type: UPDATE_TITLE_HEADER,
            payload: {
                data: data,
            },
        })
    }
}
