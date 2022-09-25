import React from "react";
import { Modal, Table } from "react-bootstrap";
import { FaRegWindowClose } from "react-icons/fa";
import moment from "moment";
export default function AssetDetailModal({ modal, user, setModal }) {
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
          <Modal.Title
            id="contained-modal-title-vcenter"
            className="d-flex justify-content-betweeen align-items-center w-100 flex-grow-1"
          >
            <h4 className="flex-grow-1"> Detailed Asset Information</h4>
            <FaRegWindowClose
              onClick={() => setModal(false)}
              style={{ cursor: "pointer" }}
            />
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <div className="d-flex">
            <p className="w-25">Asset Code</p>
            <p className="w-75">{user.asset_code}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Asset Name</p>
            <p className="w-75">{user.name}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Category</p>
            <p className="w-75">{user.category && user.category.name}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Installed Date</p>
            <p className="w-75">{user.installed_date}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">State</p>
            <p className="w-75">{user.state && user.state.name}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Location</p>
            <p className="w-75">{user.location}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Specification</p>
            <p className="w-75">{user.specification}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">History</p>
          </div>
          <div>
            <div className="table-custom table-responsive">
              {user.returning ? (
                <Table bordered hover id="asset-history">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Assigned to</th>
                      <th>Assigned by</th>
                      <th>Returned date</th>
                    </tr>
                  </thead>
                  <tbody>
                    {user.returning.map((element, idx) => {
                      return (
                        <tr key={idx}>
                          <td>
                            {element.assignment &&
                              element.assignment.assigned_date}
                          </td>
                          <td>
                            {element.assignment &&
                              element.assignment.staff &&
                              element.assignment.staff.username}
                          </td>
                          <td>
                            {element.assignment &&
                              element.assignment.assigned_by &&
                              element.assignment.assigned_by.username}
                          </td>
                          <td>{element.return_date}</td>
                        </tr>
                      );
                    })}
                  </tbody>
                </Table>
              ) : (
                <span>No Data</span>
              )}
            </div>
          </div>
        </Modal.Body>
      </Modal>
    </>
  );
}
