import React, { useState, useEffect } from 'react';
import { Button, Modal } from "react-bootstrap";
import './style.scss';
import axios from 'axios';

export default function DeleteAssignment(props) {
    const [show, setShow] = useState(Boolean(props.show));
    const [canDelete, setCanDelete] = useState(true);

    const handleDeleteAssignment = async (e) => {
        try {
            const token = localStorage.getItem('token')
            const headers = {headers: {Authorization: `Bearer ${token}`}};
            await axios.delete(`/api/assignment/${props.id}`, headers);
            setShow(false)
            window.location.reload();
        } catch (e) {
            const error = new Error("Something went wrong");
            throw error;
        }
    }

    useEffect(async () => {
        if (props.show === true) {
            setShow(true);
            const token = localStorage.getItem('token')
            const headers = {headers: {Authorization: `Bearer ${token}`}};
            await axios.get(`/api/can-delete-assignment/${props.id}`, headers).then(res => {
                setCanDelete(true)
            }).catch(err => {
                setCanDelete(false)
            })
        }
    }, [props])

    const handleClose = () => {
        setShow(false)
    };

    return (
        <>
            <Modal
                show={show}
                backdrop="static"
                keyboard={false}
            >
                <Modal.Header>
                    <Modal.Title id='modalText'>{canDelete? 'Are you sure?':'Error!'}</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div id='modalText'>
                        <p>
                            {
                                canDelete ? 'Do you want to delete this assignment?' : 'Somethings went wrong! You can not delete this assignment.'
                            }
                        </p>
                        {canDelete ? <>
                                <Button
                                    onClick={handleDeleteAssignment}
                                    id="disableUserButton"
                                    variant="light">
                                    Delete
                                </Button>
                                <b> </b>
                                <Button
                                    onClick={handleClose}
                                    id="cancelDisableUserButton"
                                    variant="light">
                                    Cancel
                                </Button>
                            </>
                            : <Button
                                onClick={handleClose}
                                id="disableUserButton"
                                variant="light">
                                OK
                            </Button>
                        }

                    </div>
                </Modal.Body>
            </Modal>
        </>
    );
}
