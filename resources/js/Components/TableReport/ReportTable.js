/* eslint-disable react/prop-types */
import React from "react";
import Table from "react-bootstrap/Table";
import { getAssignmentEdit } from "../../Actions/assignment.action";
import assetEditReducer from "../../Reducers/asset.reducer";
import AssignmentService from "../../Services/assignment.service";
import Swal from "sweetalert2";
import { useHistory } from "react-router-dom";
import {
    FaAngleDown,
    FaAngleUp,
    FaPencilAlt,
    FaRegTimesCircle,
    FaUndo
} from "react-icons/fa";
import moment from "moment";
import { Redirect , Navigate} from "react-router-dom";

export default function ReportTable({
    data, Nodata, tableHeader,
    // eslint-disable-next-line no-unused-vars
    handleSort, handleOpenEditForm,
    handleGetAssignmentById, 
}) {



    return (
        <Table id="table-assignment" responsive="md">
            <thead>
                <tr>
                    {data.length > 0
                        ? tableHeader.map((item, index) => {
                            return (
                                <th
                                    key={index}
                                    onClick={() => {
                                        if (item.name !== "Username") {
                                            handleSort(item.name, item.isSortASC);
                                        }
                                    }}
                                >
                                    {item.name}&nbsp;
                                    {item.isSortASC && <FaAngleDown />}
                                    {item.isSortDESC && <FaAngleUp />}
                                </th>
                            );
                        })
                        :''}
                </tr>
            </thead>
            <tbody>
                {data.length > 0 ? (
                    data.length > 0 &&
                    data.map((item) => (
                        <tr key={item.id} onClick={() => handleGetAssignmentById(item.id)}>
                            <td>{item.category_name}</td>
                            <td>{item.total}</td>
                            <td >{item.count_assinged}</td>
                            <td>{item.count_available}</td>
                            <td>{item.count_not_available}</td>
                            <td>{item.count_waiting_for_recycling}</td>
                            <td>{item.count_recycled}</td>
                        </tr>
                    ))
                ) : (
                    <img id="img-nodata" style={{marginLeft : 370}} src={Nodata}></img>
                )}
            </tbody>
        </Table>
    );
}
