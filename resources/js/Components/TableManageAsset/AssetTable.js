import React from "react";
import Table from "react-bootstrap/Table";
import { getAssetEdit } from "../../Actions/asset.action";
import { useDispatch, useSelector } from "react-redux";
import assetEditReducer from "../../Reducers/asset.reducer";
import Swal from "sweetalert2";
import {
    FaAngleDown,
    FaAngleUp,
    FaPencilAlt,
    FaRegTimesCircle,
} from "react-icons/fa";

export default function AssetTable({
    data, Nodata, tableHeader,
    handleSort, handleOpenEditForm,
    handleGetUserById, handleDeleteAsset,
    setPage
}) {

    const dispatch = useDispatch();
    async function handleOpenEditAssetForm(e, assetId = "") {
        e.stopPropagation();
        const data = {
            assetId: assetId,
            displayValue: true,
            sort_at: ''
        }
        const response = await dispatch(getAssetEdit(data));
        handleShowMessage(response);
    }

    function handleShowMessage(response) {
        const message = response.data == undefined ? response.message : response.data.message;
        const code = response.code;
        switch (code) {
            case 200:
                {
                    //
                }
                break;
            case 422:
                {
                    Swal.fire({
                        position: "center",
                        icon: "info",
                        title: message,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
                break;
        }

    }

    if(data.length === 0) setPage(1)
    return (
        <Table responsive="md" id= "table-container">
            <thead>
                <tr>
                    {data.length > 0
                        ? tableHeader.map((item, index) => {
                            return (
                                <th
                                    key={index}
                                    onClick={() => {
                                        handleSort(item.name, item.isSortASC);
                                    }}
                                >
                                    {item.name}&nbsp;
                                    {item.isSortASC && <FaAngleDown />}
                                    {item.isSortDESC && <FaAngleUp />}
                                </th>
                            );
                        })
                        : ''}
                </tr>
            </thead>
            <tbody>
                {data.length > 0 ? (
                    data.length > 0 &&
                    data.map((item) => (
                        <tr key={item.id} onClick={() => handleGetUserById(item.id)}>
                            <td>{item.asset_code}</td>
                            <td>{item.name}</td>
                            <td>{item.category.name}</td>
                            <td>{item.state.name}</td>
                            <td className="td-without_border">
                                {item.state.code !== 2 ?
                                    <>
                                        <FaPencilAlt
                                            onClick={(e) => handleOpenEditAssetForm(e, item.id)} aria-disabled={item.state.code !== 2} id='editUserButton'
                                        />{" "}
                                        {"  "}
                                        <FaRegTimesCircle id="deleteIcon" className="delete-icon" aria-disabled={item.state.code !== 2}
                                            onClick={(e) => handleDeleteAsset(e, item.id)} type="button" />
                                    </>
                                    :
                                    <>
                                        <FaPencilAlt color='gray' />{" "}
                                        {"  "}
                                        <FaRegTimesCircle color='gray' />
                                    </>

                                }
                            </td>
                        </tr>
                    ))
                ) : (
                    <img id="img-nodata" style={{marginLeft : 370}} src={Nodata}></img>
                )}
            </tbody>
        </Table>
    );
}
