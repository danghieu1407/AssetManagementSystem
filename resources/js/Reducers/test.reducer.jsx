import {testConstants} from "../Constants";
const { GET_USER } = testConstants

const initState = {
    userListTest : []
}

const testReducer = (state = initState, action) =>{
    switch (action.type){
        case GET_USER:
            return{
                ...state,
                userListTest: action.payload.data
            }
        default:
            return state
    }
}

export default testReducer;
