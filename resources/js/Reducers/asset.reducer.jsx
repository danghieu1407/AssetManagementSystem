import { userConstants } from "../Constants";
const { GET_ASSET_EDIT } = userConstants;
const initState = {
    value:false,
    assetEditInfo: {},
    sort_at:'',
};

const assetEditReducer = (state = initState, action) => {
    switch (action.type) {
        case GET_ASSET_EDIT:
            return {
                ...state,
                value: action.payload.value,
                assetEditInfo: action.payload.data,
                sort_at: action.payload.sort_update_at,
            };
        default:
            return state;
    }
};

export default assetEditReducer;
