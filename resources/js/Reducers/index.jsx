import { combineReducers } from 'redux';
import testReducer from "./test.reducer";
import homeReducer from "./home.reducer";
import userReducer from "./user.reducer";
import userEditReducer from "./userEdit.reducer";
import assetEditReducer from "./asset.reducer";
import assignmentEditReducer from "./assignment.reducer";
import assetGetMessageReducer from "./getMessageTopSort.reducer";
const appReducer = combineReducers({
    testReducer, homeReducer, userReducer, userEditReducer, assetEditReducer,assignmentEditReducer, assetGetMessageReducer
})
const rootReducer = (state, action) => {
    return appReducer(state, action)
}
export default rootReducer;
