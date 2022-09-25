import {Button, Col, Container, Form, InputGroup, Modal, Row} from "react-bootstrap";
import React,{useEffect, useState} from "react";
import "./style.scss"
import {FaEye , FaEyeSlash} from "react-icons/fa";

import axios from "axios";
import {useDispatch, useSelector} from "react-redux";

export default function ChangePassword(props){
    const dispatch = useDispatch()
    const data = useSelector(state => state.userReducer.userInfo);
    const [show, setShow] = useState(Boolean(props.show))
    const [filledPassword, setFilledPassword] = useState(false)
    const [oldPassword, setOldPassword] = useState({
        password: '',
        showPassword: false,
        validate: ''
    });
    const [newPassword, setNewPassword] = useState({
        password: '',
        showPassword: false,
        validate: ''
    });

    useEffect(()=>{
        if(show===true){
            setOldPassword({
                password: '',
                showPassword: false,
                validate: ''
            })
            setNewPassword({
                password: '',
                showPassword: false,
                validate: ''
            })
        }
    }, [show])

    //Handle save button
    const handleSave = () =>{
        setNewPassword({
            ...newPassword,
            validate: '',
        })
        setOldPassword({
            ...oldPassword,
            validate: '',
        })
        if(oldPassword.password === newPassword.password){
            setNewPassword({
                ...newPassword,
                validate: 'The new password must not be the same as the old password',
            })
            return 0
        }
        else{
            axios.post('api/profile', {
                oldPassword: oldPassword.password,
                newPassword: newPassword.password,
            }, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            })
                .then(function (response) {
                    setOldPassword({
                        ...oldPassword,
                        validate: response.data.message,
                    })
                })
                .catch(function (error) {
                    if(error.response.data.message === 'Password is incorrect')
                        setOldPassword({
                            ...oldPassword,
                            validate: error.response.data.message,
                        })
                    else setNewPassword({
                        ...newPassword,
                        validate: error.response.data.message,
                    })
                });
        }
    }
    //Handle close pop-up
    const handleClose = () => setShow(false);
    //Handle show/hide password
    const handleOldPasswordButton = () =>{
        setOldPassword({
            ...oldPassword,
            showPassword: !oldPassword.showPassword,
        })
    }
    //Handle show/hide password
    const handleNewPasswordButton = () =>{
        setNewPassword({
            ...newPassword,
            showPassword: !newPassword.showPassword,
        })
    }
    //Handle onChange old password input
    const handleOldPasswordInput = (e) => setOldPassword({
        ...oldPassword,
        password: e.target.value
    })
    //Handle onChange new password input
    const handleNewPasswordInput = (e) => setNewPassword({
        ...newPassword,
        password: e.target.value
    })

    useEffect(()=>{
        if(oldPassword.password !== '' && newPassword.password !== '') setFilledPassword(true)
        else setFilledPassword(false)
    }, [oldPassword.password, newPassword.password])

    useEffect(()=>{
        if(props.show) setShow(Boolean(true))
    }, [props])

    return(
        <>
            <Modal
                show={show}
                backdrop="static"
                keyboard={false}
            >
                <Modal.Header >
                    <Modal.Title id="pwChangePasswordHeader">Change password</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {
                        oldPassword.validate==='Your password has been changed successfully'
                            ?
                            <Container>
                                <Row>
                                    <p id='successAlert'>{oldPassword.validate}</p>
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
                            : <Container>
                                <Row>
                                    <Col md={4}>
                                        <p className='vertical-center'>
                                            Old password
                                        </p>
                                    </Col>
                                    <Col md={8}>
                                        <InputGroup>
                                            <Form.Control id="pwOldPasswordInput" type={oldPassword.showPassword ? 'text': 'password'} onChange={handleOldPasswordInput}/>
                                            <Button id="pwOldPasswordButton" onClick={handleOldPasswordButton}>
                                                {oldPassword.showPassword
                                                    ? <FaEyeSlash/>
                                                    : <FaEye/>}
                                            </Button>
                                        </InputGroup>
                                    </Col>
                                </Row>
                                {
                                    oldPassword.validate === ''
                                        ? <></>
                                        : <Row>
                                            <Col md={{span: 8, offset: 4}}>
                                                <p id='pwOldPasswordError'>{oldPassword.validate}</p>
                                            </Col>
                                        </Row>
                                }
                                <Row>
                                    <Col md={4}>
                                        <p className='vertical-center'>
                                            New password
                                        </p>
                                    </Col>
                                    <Col md={8}>
                                        <InputGroup>
                                            <Form.Control id="pwNewPasswordInput" type={newPassword.showPassword ? 'text': 'password'} onChange={handleNewPasswordInput}/>
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
                                                <p id='pwOldPasswordError'>{newPassword.validate}</p>
                                            </Col>
                                        </Row>

                                }
                                <Row>
                                    <Col md={{span: 6, offset: 6}} align={'right'}>
                                        <Button onClick={handleSave}
                                                id="pwSaveButton"
                                                variant="light"
                                                disabled={!filledPassword}>
                                            Save
                                        </Button>
                                        <b>  </b>
                                        <Button onClick={handleClose}
                                                id="pwCancelButton"
                                                variant="light">
                                            Cancel
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
