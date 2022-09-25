/* eslint-disable react/prop-types */
/* eslint-disable react/no-unescaped-entities */

import {Button, Col, Container, Modal, Row} from "react-bootstrap";
import React, {useState} from "react";
import ReturningService from "../../Services/returning.service";
import { useDispatch} from "react-redux";

// import "./style.scss"

export default function CompleteReturningRequest(props) {
    const [showModal, setShowModal] = useState(false);
    const [modalHeader, setModalHeader] = useState("");
    const [modalBody, setModalBody] = useState("");

    //Handle save button
    const handleUpdateReturningInfo = async () => {
        props.closeModal()
        const data = {
            returningId: props.returningId,
            state : 1
        }

        const response = await ReturningService.updateReturningInfo(data);
        const message =
            response.data == undefined
                ? response.message
                : response.data.message;
        const code = response.code;
        handleShowMessage(code, message);
    }

    const dispatch = useDispatch();
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
                    dispatch({
                        type: 'GET_MESSAGE',
                        payload: {
                            sort_at: 'sortByEditReturning',
                        },
                    })
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
                backdrop="static"
                onHide={() => props.closeModal()}
                keyboard={false}
            >
                <Modal.Header>
                    <Modal.Title id="pwChangePasswordHeader">Are you sure?</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {
                        <Container id='pwChangePasswordFirstContainer' style={{width: "max-content"}}>
                                <Row>
                                    <p style={{marginBottom: "-1px"}}>Do you want to mark this returning request as 'Completed'?</p>
                                </Row>
                                <Row className = "text-start">
                                    <Col>
                                        <Button onClick={handleUpdateReturningInfo}
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
