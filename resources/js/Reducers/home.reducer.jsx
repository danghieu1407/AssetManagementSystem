import {homeConstants} from "../Constants";
const { UPDATE_TITLE_HEADER } = homeConstants

const initState = {
    headerNameList : {
        name: ''
    }

}

const homeReducer = (state = initState, action) =>{
    switch (action.type){
        case UPDATE_TITLE_HEADER:
            return{
                ...state,
                headerNameList: {
                    ...state,
                    headerNameList: action.payload.data
                }
            }
        default:
            return state
    }
}

export default homeReducer;
