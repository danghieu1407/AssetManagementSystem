import React, { useState, useEffect } from 'react';
import { Button, Modal } from "react-bootstrap";
import './style.scss';
import axios from 'axios';
import {FaRegWindowClose} from "react-icons/fa";

export default function DisableUser(props) {
    return (
        <>
            {(props.type === 'delete') ?
                <Modal
                    show={props.show}
                    backdrop="static"
                    keyboard={false}
                >
                    <Modal.Header>
                        <Modal.Title id='modalText'>Are you sure?</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div id='modalText'>
                            <p>
                                Do you want to disable this user?
                            </p>
                            <Button
                                onClick={props.handleDisableUserDialog}
                                id="disableUserButton"
                                variant="light">
                                Disable
                            </Button>
                            <b>  </b>
                            <Button
                                onClick={props.handleClose}
                                id="cancelDisableUserButton"
                                variant="light">
                                Cancel
                            </Button>
                        </div>
                    </Modal.Body>
                </Modal>
                : (props.type === 'warning') ?
                    <Modal
                        show={props.show}
                        backdrop="static"
                        keyboard={false}
                    >
                        <Modal.Header>
                            <Modal.Title id='modalText'>Can not disable user</Modal.Title>
                            <FaRegWindowClose onClick={props.handleClose} style={{ cursor: 'pointer' }} />
                        </Modal.Header>
                        <Modal.Body id='modalText'>
                            <div>
                                <p>
                                    There are valid assignments belonging to this user. Please close all assignments before
                                    disabling user.
                                </p>
                            </div>
                        </Modal.Body>
                    </Modal>
                    : ''
            }
        </>
    );
}
