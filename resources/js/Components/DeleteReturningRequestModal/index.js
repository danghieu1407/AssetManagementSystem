/* eslint-disable react/prop-types */
/* eslint-disable react/no-unescaped-entities */

import {Button, Col, Container, Modal, Row} from "react-bootstrap";
import React, {useState} from "react";
import ReturningService from "../../Services/returning.service";
// import "./style.scss"

export default function DeleteReturningRequest(props) {
    const [showModal, setShowModal] = useState(false);
    const [modalHeader, setModalHeader] = useState("");
    const [modalBody, setModalBody] = useState("");


    //Handle save button
    const handleDeleteReturningInfo = async () => {
        props.closeModal()

        const response = await ReturningService.deleteReturningInfo(props.returningId);
        const message =
            response.data == undefined
                ? response.message
                : response.data.message;
        const code = response.code;
        handleShowMessage(code, message);
    }

    function handleShowMessage(code, message) {
        setShowModal(true);
        switch (code) {
            case 200:
                {
                    setModalHeader("Success");
                    setModalBody(message);
                    setTimeout(() => {
                        setShowModal(false);
                    }, 2000);
                    window.location.reload();
                }
                break;

            case 422:
            case 400:
            case 403:
            case 404:
            case 500:
                setModalHeader("Failed!");
                setModalBody(message);
                setTimeout(() => {
                    setShowModal(false);
                }, 2000);
                break;
        }
    }


    return (
        <>
            <Modal
                show={props.show}
                onHide={() => props.closeModal()}
                backdrop="static"
                keyboard={false}
            >
                <Modal.Header>
                    <Modal.Title id="pwChangePasswordHeader">Are you sure?</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {
                        <Container id='pwChangePasswordFirstContainer' style={{width: "max-content"}}>
                                <Row>
                                    <p style={{marginBottom: "-1px"}}>Do you want to cancel this returning request?</p>
                                </Row>
                                <Row className = "text-start">
                                    <Col>
                                        <Button onClick={handleDeleteReturningInfo}
                                                id="pwSaveButton"
                                                variant="primary">
                                            Yes
                                        </Button>
                                        <b>&emsp;</b>
                                        <Button onClick={() => props.closeModal()}
                                                id="pwCancelButton"
                                                variant="light">
                                            No
                                        </Button>
                                    </Col>
                                </Row>
                            </Container>
                    }
                </Modal.Body>
            </Modal>
            <Modal show={showModal} backdrop="static" keyboard={false}>
                <Modal.Header>
                    <Modal.Title id="pwChangePasswordHeader">
                        {modalHeader}
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Container id="pwChangePasswordFirstContainer">
                        <Row>
                            <p id="successAlert">{modalBody}</p>
                        </Row>
                    </Container>
                </Modal.Body>
            </Modal>
        </>
    )
}
