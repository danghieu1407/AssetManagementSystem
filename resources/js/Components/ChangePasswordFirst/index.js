
import {Button, Col, Container, Form, InputGroup, Modal, Row} from "react-bootstrap";
import React, {useEffect, useState} from "react";
import "./style.scss"
import {FaEye , FaEyeSlash} from "react-icons/fa";

import axios from "axios";

export default function ChangePasswordFirst(props) {
    const [show, setShow] = useState(Boolean(props.show))
    const [filledPassword, setFilledPassword] = useState(false)
    const [newPassword, setNewPassword] = useState({
        password: '',
        showPassword: false,
        validate: ''
    });

    useEffect(() => {
        if (show === true) {
            setNewPassword({
                password: '',
                showPassword: false,
                validate: ''
            })
        }
    }, [show])

    //Handle save button
    const handleSave = () => {
        setNewPassword({
            ...newPassword,
            validate: '',
        })
        axios.post('api/profile', {
            newPassword: newPassword.password,
            oldPassword: '123456',
        }, {
            headers: {Authorization: `Bearer ${localStorage.getItem('token')}`}
        })
            .then(function (response) {
                setNewPassword({
                    ...newPassword,
                    validate: response.data.message,
                })
            })
            .catch(function (error) {
                setNewPassword({
                    ...newPassword,
                    validate: error.response.data.message,
                })
            })
    }
    //Handle close pop-up
    const handleClose = () => setShow(false);
    //Handle show/hide password
    const handleNewPasswordButton = () => {
        setNewPassword({
            ...newPassword,
            showPassword: !newPassword.showPassword,
        })
    }
    //Handle onChange new password input
    const handleNewPasswordInput = (e) => {
        setNewPassword({
            ...newPassword,
            password: e.target.value
        })
        if (newPassword.password !== '') setFilledPassword(true)
    }

    useEffect(() => {
        if (props.show) setShow(Boolean(true))
    }, [props])

    return (
        <>
            <Modal
                show={show}
                backdrop="static"
                keyboard={false}
            >
                <Modal.Header>
                    <Modal.Title id="pwChangePasswordHeader">Change password</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {
                        newPassword.validate === 'Your password has been changed successfully'
                            ?
                            <Container id='pwChangePasswordFirstContainer'>
                                <Row>
                                    <p id='successAlert'>{newPassword.validate}</p>
                                </Row>
                                <Row>
                                    <Col align='right'>
                                        <Button onClick={handleClose}
                                                id="pwCancelButton"
                                                variant="light">
                                            Close
                                        </Button>
                                    </Col>
                                </Row>
                            </Container>
                            : <Container id='pwChangePasswordFirstContainer'>
                                <Row>
                                    <p style={{marginBottom: "-1px"}}>This is the first time you logged in.</p>
                                    <p style={{marginBottom: "-1px"}}>You have to change your password to continue.</p>

                                </Row>
                                <Row className="Row-input">
                                    <Col md={4}>
                                        <p className='vertical-center'>
                                            New password
                                        </p>
                                    </Col>
                                    <Col md={8}>

                                        <InputGroup>
                                            <Form.Control id="pwNewPasswordInput"
                                                          type={newPassword.showPassword ? 'text' : 'password'}
                                                          onChange={handleNewPasswordInput}/>
                                            <Button id="pwNewPasswordButton" onClick={handleNewPasswordButton}>
                                                {newPassword.showPassword
                                                    ? <FaEyeSlash/>
                                                    : <FaEye/>}
                                            </Button>
                                        </InputGroup>
                                    </Col>
                                </Row>
                                {
                                    newPassword.validate === ''
                                        ? <></>
                                        : <Row>
                                            <Col md={{span: 8, offset: 4}}>
                                                <p id='pwNewPasswordError'>{newPassword.validate}</p>
                                            </Col>
                                        </Row>

                                }
                                <Row>
                                    <Col md={9}/>
                                    <Col md={3} align='right'>
                                        <Button onClick={handleSave}
                                                id="pwSaveButton"
                                                variant="light"
                                                disabled={!filledPassword}>
                                            Save
                                        </Button>
                                    </Col>
                                </Row>
                            </Container>
                    }
                </Modal.Body>
            </Modal>
        </>
    )
}
