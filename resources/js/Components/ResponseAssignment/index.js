import React, { useState, useEffect } from 'react';
import { Button, Modal } from "react-bootstrap";
import './style.scss';
import axios from 'axios';

export default function ResponseAssignment(props) {
    const [response, setResponse] = useState({res: props.res, id: props.id, show: props.show, error: false});

    const handleResponse = async () => {
        try {
            console.log('response', response);
            const headers = {headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }};
            await axios.post(`/api/response-assignment/${response.id}`, {response: response.res ? 1 : 0}, headers);
            setResponse({...response, show: false});
            window.location.reload();
        } catch (e) {
            setResponse({...response, error: true});
        }
    }

    useEffect(async () => {
        if (props.show === true) {
            setResponse({...response, show: true, res: props.res, id: props.id, error: false});
        }
    }, [props])

    const handleClose = () => {
        setResponse({...response, show:false})
    };

    return (
        <>
            <Modal
                show={response.show}
                backdrop="static"
                keyboard={false}
            >
                <Modal.Header>
                    <Modal.Title id='modalText'>{!response.error? 'Are you sure?':'Error!'}</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div id='modalText'>
                        <p>
                            {
                                !response.error ?
                                    'Do you want to ' + (response.res? 'accept': 'decline') + ' this assignment?' : 'Somethings went wrong! You can not response to this assignment.'
                            }
                        </p>
                        {!response.error ? <>
                                <Button
                                    onClick={handleResponse}
                                    id="disableUserButton"
                                    variant="light">
                                    {response.res? 'Accept': 'Decline'}
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
