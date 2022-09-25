import { userConstants } from "../Constants";
const { GET_USER_INFO, GET_USER_EDIT } = userConstants;

const initState = {
    userInfo: {},
    value:false,
    userEditInfo:{},
};

const userReducer = (state = initState, action) => {
    switch (action.type) {
        case GET_USER_INFO:
            return {
                ...state,
                userInfo: action.payload.data,
            };
        case GET_USER_EDIT:
            return {
                ...state,
                value: action.payload.value,
                userEditInfo: action.payload.data,
            };
        default:
            return state;
    }
};

export default userReducer;




