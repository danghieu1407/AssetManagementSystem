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
                        <h4 className="flex-grow-1"> Detailed Returning Information</h4>
                        <FaRegWindowClose onClick={() => setModal(false)} style={{ cursor: 'pointer' }} />
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div className="d-flex">
                        <p className="w-25">Asset Code</p>
                        <p className="w-75">{assignment.assignment && assignment.assignment.asset && assignment.assignment.asset.asset_code}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Asset Name</p>
                        <p className="w-75">{assignment.assignment && assignment.assignment.asset && assignment.assignment.asset.name}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Requested by</p>
                        <p className="w-75">{assignment.requested_by && assignment.requested_by.username}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Assigned date</p>
                        <p className="w-75">{assignment.assignment && assignment.assignment.assigned_date}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Accepted by</p>
                        <p className="w-75">{assignment.accepted_by && assignment.accepted_by.username}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">Returned Date</p>
                        <p className="w-75">{assignment.return_date}</p>
                    </div>
                    <div className="d-flex">
                        <p className="w-25">State</p>
                        <p className="w-75">{assignment.returning_state}</p>
                    </div>
                </Modal.Body>
            </Modal>
        </>
    );
}