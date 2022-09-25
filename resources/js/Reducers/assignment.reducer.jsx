import { userConstants } from "../Constants";
const { GET_ASSIGNMENT_EDIT } = userConstants;
const initState = {
    value:false,
    assignmentEditInfo: {},
    sort_at:'',
};

const assignmentEditReducer = (state = initState, action) => {
    switch (action.type) {
        case GET_ASSIGNMENT_EDIT:
            return {
                ...state,
                value: action.payload.value,
                assignmentEditInfo: action.payload.data
            };
        default:
            return state;
    }
};

export default assignmentEditReducer;
