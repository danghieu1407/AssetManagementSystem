
import { userConstants, exceptionConstants } from "../Constants";
import AssetService from "../Services/asset.service"
const {GET_ASSET_INFO,GET_ASSET_EDIT } = userConstants;
const { SUCCESS } = exceptionConstants;

export const getAssetInfo = () => {
    return async function (dispatch) {
        const response = await AssetService.getAssetInfo()
        const assetInfo = response.data
        const code = response.code
        if (code === SUCCESS) {
            dispatch({
                type: GET_ASSET_INFO,
                payload: {
                    data: assetInfo,
                },
            })
        }
        return response
    }
}

export const getAssetEdit = (data) => {
    return async function (dispatch) {
        const response = await AssetService.getAssetEdit(data.assetId);
        const assetEdit = response.data
        const code = response.code
        if (code === SUCCESS) {
            dispatch({
                type: GET_ASSET_EDIT,
                payload: {
                    value: data.displayValue || false,
                    data: assetEdit || '',
                    sort_update_at: data.sort_at || '',
                },
            })
        }
        return response;
    }
}

