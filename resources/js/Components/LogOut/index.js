import React, { useState, useEffect } from 'react';
import {Button, Col, Container, Modal, Row} from "react-bootstrap";
import './style.scss';
import axios from 'axios';

export default function LogOut(props) {

    const [show, setShow] = useState(props.show)

    useEffect(() => {
        if(props.show) setShow(Boolean(true))
    },[props.show])



    const handleLogout = async (e) => {
        try {
            const token = localStorage.getItem('token')
            const headers = { headers: { Authorization: `Bearer ${token}` } };
            axios.get('api/logout', headers)
                .then(resp => {
                    localStorage.clear()
                    window.location.reload()
                })
        } catch (e) {
            const error = new Error("Something went wrong");
            throw error;
        }
    }
    const handleClose = () => {setShow(false)};
    return (
        <>
            <Modal
                show={show}
                backdrop="static"
                keyboard={false}
                id="logoutModal"
            >
                <Modal.Header>
                    <Modal.Title id="pwChangePasswordHeader">Are you sure?</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Container>
                        <Row>
                            <Col id='logoutText'>Do you want to log out?</Col>
                        </Row>
                        <Row>
                            <Col md={{span: 12, offset: 0}} id='logoutButtonGroup'>
                                <Button onClick={handleLogout} id="pwSaveButton" variant="light">Log out</Button>
                                <b>  </b>
                                <Button onClick={handleClose} id="pwCancelButton" variant="light">Cancel</Button>
                            </Col>
                        </Row>
                    </Container>
                </Modal.Body>
            </Modal>
        </>
    );
}
