/* eslint-disable react/prop-types */
import React, { useState, useEffect } from 'react';
import { Button, Modal } from "react-bootstrap";
import './style.scss';
import axios from 'axios';
import {Link} from "react-router-dom";
import {FaRegWindowClose} from "react-icons/fa";
import {useDispatch} from "react-redux";
import {getAssetEdit} from "../../Actions/asset.action";

export default function DeleteAsset(props) {
    const [haveAssignment, setHaveAssignment] = useState(false);
    const [show, setShow] = useState(Boolean(props.show));
    const dispatch = useDispatch();

    async function handleOpenEditAssetForm() {
        const data = {
            assetId: props.id,
            displayValue: true,
            sort_at:''
        }
        await dispatch(getAssetEdit(data));
    }

    useEffect(async () => {
        axios.get(`/api/asset/${props.id}/can-delete`).then(res => {
            setHaveAssignment(res.data.valid);
            if (props.show) setShow(Boolean(true));
        }).catch(err => {
            setHaveAssignment(err.response.data.valid);
            if (props.show) setShow(Boolean(true));
        })
    }, [props.show])

    const handleDisableUser = async () => {
        try {
            const token = localStorage.getItem('token')
            const headers = {headers: {Authorization: `Bearer ${token}`}};
            await axios.delete(`/api/asset/${props.id}`, headers);
            setShow(false)
            setShow(false);
            window.location.reload();
        } catch (e) {
            const error = new Error("Something went wrong");
            throw error;
        }
    }

    const handleClose = () => {
        setShow(false)
    };

    return (
        <>
            {(!haveAssignment) ?
                <Modal
                    show={show}
                    backdrop="static"
                    keyboard={false}
                >
                    <Modal.Header>
                        <Modal.Title id='modalText'>Are you sure?</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div id='modalText'>
                            <p>
                                Do you want to delete this asset?
                            </p>
                            <Button
                                onClick={handleDisableUser}
                                id="disableUserButton"
                                variant="light">
                                Delete
                            </Button>
                            <b>  </b>
                            <Button
                                onClick={handleClose}
                                id="cancelDisableUserButton"
                                variant="light">
                                Cancel
                            </Button>
                        </div>
                    </Modal.Body>
                </Modal>
                :
                <Modal
                    show={show}
                    backdrop="static"
                    keyboard={false}
                >
                    <Modal.Header>
                        <Modal.Title id='modalText'>Can not delete asset</Modal.Title>
                        <FaRegWindowClose onClick={handleClose} style={{ cursor: 'pointer' }} />
                    </Modal.Header>
                    <Modal.Body>
                        <div id='modalText'>
                            <p>
                                Cannot delete this asset because it belongs to one or more historical assignments.
                            </p>
                            <p>
                                If this asset is not able to be use anymore, please update its state in <Link onClick={handleOpenEditAssetForm}>Edit Asset page</Link>.
                            </p>
                        </div>
                    </Modal.Body>
                </Modal>
            }
        </>
    );
}
