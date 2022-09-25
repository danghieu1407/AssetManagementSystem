import React from "react";
import { Modal, Table } from "react-bootstrap";
import { FaRegWindowClose } from "react-icons/fa"
import moment from "moment";
import { assign } from "lodash";
export default function AssignmentDetailModal({
    modal, assignment,
    setModal
}) {
    return (
        <>
            <Modal
                show={modal}
                size="lg"
                onHide={() => setModal(false)}
                aria-labelledby="contained-modal-title-vcenter"
                centered
            >
                <Modal.Header className="w-100">
                    <Modal.Title id="contained-modal-title-vcenter" className="d-flex justify-content-betweeen align-items-center w-100 flex-grow-1">
                        <h4 className="flex-grow-1"> Detailed Assignment Information</h4>
                        <FaRegWindowClose onClick={() => setModal(false)} style={{ cursor: 'pointer' }} />
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div className="d-flex">
                        <p className="w-25">Asset Code</p>
                        <p className="w-75">{assignment.asset && assignment.asset.asset_code}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Asset Name</p>
                        <p className="w-75">{assignment.asset && assignment.asset.name}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Specification</p>
                        <p className="w-75">{assignment.asset && assignment.asset.specification}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Assigned to</p>
                        <p className="w-75">{assignment.staff && assignment.staff.username}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Assigned by</p>
                        <p className="w-75">{assignment.assigned_by && assignment.assigned_by.username}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Assigned Date</p>
                        <p className="w-75">{assignment.assigned_date}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">State</p>
                        <p className="w-75">{assignment.state && assignment.state.name}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Note</p>
                        <p className="w-75">{assignment.note}</p>
                    </div>
                </Modal.Body>
            </Modal>
        </>
    );
}