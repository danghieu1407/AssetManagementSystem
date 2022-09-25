import { userConstants } from "../Constants";
const { GET_USER_EDIT } = userConstants;
const { GET_USER_CREATE } = userConstants;
const initState = {
    userEditInfo: {},
    value:false,
    sort_update_at:'',
};

const userEditReducer = (state = initState, action) => {
    switch (action.type) {
        case GET_USER_EDIT:
            return {
                ...state,
                value: action.payload.value,
                userEditInfo: action.payload.data,
                sort_update_at: action.payload.sort_update_at,
            };
        
        case GET_USER_CREATE:
            return {
                ...state,
                value: action.payload.value,
                userEditInfo: action.payload.data,
                sort_update_at: action.payload.sort_update_at,
            };
        default:
            return state;
    }
};

export default userEditReducer;
