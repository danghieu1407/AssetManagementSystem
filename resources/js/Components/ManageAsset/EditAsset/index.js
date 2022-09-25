import React, { useState } from "react";
import "./style.css";
import "./toast.css";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import { Row, Container, Col } from "reactstrap";
import { useSelector, useDispatch } from "react-redux";
import AssetService from "../../../Services/asset.service";
import { getAssetEdit } from "../../../Actions/asset.action";
import assetEditReducer from "../../../Reducers/asset.reducer";
import { Modal } from "react-bootstrap";

export default function EditAssetForm() {
    const [showModal, setShowModal] = useState(false);
    const [modalHeader, setModalHeader] = useState("");
    const [modalBody, setModalBody] = useState("");
    const [disableSave, setDisableSave] = useState(true);

    const assetEditInfo = useSelector(
        (state) => state.assetEditReducer.assetEditInfo
    );

    const stateAsset = {
        0: "Not available",
        1: "Available",
        2: "Assigned",
        "-1": "Waiting for recycling",
        "-2": "Recycled",
    };

    const [selectedRadio, setSelectedRadio] = useState(
        stateAsset[String(assetEditInfo.state)]
    );

    const dispatch = useDispatch();

    function handleCloseEditForm(e) {
        e.preventDefault();
        const data = {
            assetId: assetEditInfo.id,
            displayValue: false,
            sort_at: "",
        };
        dispatch(getAssetEdit(data));
    }

    async function handleUpdateAssetInfo(e) {
        e.preventDefault();
        const data = {
            assetId: assetEditInfo.id,
            name: e.target.form[0].value,
            specification: e.target.form[2].value,
            installed_date: e.target.form[3].value,
            state: Number(
                Object.keys(stateAsset).find(
                    (key) => stateAsset[key] === selectedRadio
                )
            ),
        };
        const response = await AssetService.updateAssetInfo(data);

        const message =
            response.data == undefined
                ? response.message
                : response.data.message;
        const code = response.code;
        handleShowMessage(code, message, assetEditInfo.id);
    }

    function handleShowMessage(code, message, assetId) {
        console.log(code, message, assetId);
        setShowModal(true);
        if (code === 200) {
            setModalHeader("Success");
            setModalBody(message);
            setTimeout(() => {
                const data = {
                    assetId: assetId,
                    displayValue: false,
                    sort_at: "sortByEditAsset",
                };
                dispatch(getAssetEdit(data));
            }, 1500);
        } else {
            setModalHeader("Failed!");
            setModalBody(message);
        }
        setTimeout(() => {
            setShowModal(false);
        }, 1500);
    }

    const handleEditForm = (e) => {
        console.log(e.target.form[0].value, e.target.form[2].value, e.target.form[3].value);
        if(e.target.form[0].value === '' || e.target.form[2].value === '' || e.target.form[3].value === ''){
            setDisableSave(true);
        }
        else setDisableSave(false);
    }

    return (
        <>
            <Container id="containerFormEdit">
                <Row className="mb-3">
                    <Col md={4} className="editUser fs-4 mx-3">
                        Edit Asset
                    </Col>
                    <Col md={8}></Col>
                </Row>
                <Row>
                    <Form
                        className="fs-5"
                        onChange={(e) => handleEditForm(e)}
                    >
                        <Form.Group className="mb-3" controlId="NameForm">
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">
                                        Name
                                    </Form.Label>
                                </Col>
                                <Col md={8}>
                                    <Form.Control
                                        type="input"
                                        defaultValue={assetEditInfo.name || ""}
                                        className="fs-5"
                                    />
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group
                            className="mb-3"
                            controlId="CategoryFormSelect"
                        >
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">
                                        Category
                                    </Form.Label>
                                </Col>
                                <Col md={8}>
                                    <select
                                        id='assetCategoryDisable'
                                        className="form-select fs-5"
                                        defaultValue={assetEditInfo.category}
                                        disabled
                                    >
                                        <option value={assetEditInfo.category}>
                                            {assetEditInfo.category}
                                        </option>
                                    </select>
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group
                            className="mb-3"
                            controlId="SpecificationForm"
                        >
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">
                                        Specification
                                    </Form.Label>
                                </Col>
                                <Col md={8}>
                                    <Form.Control
                                        as="textarea"
                                        rows={3}
                                        defaultValue={
                                            assetEditInfo.specification || ""
                                        }
                                        className="fs-5"
                                    />
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group
                            className="mb-3"
                            controlId="InstalledDateForm"
                        >
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">
                                        Installed Date
                                    </Form.Label>
                                </Col>
                                <Col md={8}>
                                    <Form.Control
                                        type="date"
                                        defaultValue={
                                            assetEditInfo.installed_date || ""
                                        }
                                        placeholder="Due Join Date"
                                        className="fs-5"
                                    ></Form.Control>
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group
                            className="mb-3"
                            controlId="StateForm">
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">
                                        State
                                    </Form.Label>
                                </Col>
                                <Col md={8}>
                                    {[
                                        "Available",
                                        "Not available",
                                        "Waiting for recycling",
                                        "Recycled",
                                    ].map((labelName) => (
                                        <div key={labelName} className="mb-3">
                                            <Form.Check inline>
                                                <Form.Check.Input
                                                    type="radio"
                                                    id={labelName}
                                                    className="fs-5"
                                                    checked={
                                                        selectedRadio ===
                                                        labelName
                                                    }
                                                    name="groupStateAsset"
                                                    isInvalid={
                                                        selectedRadio ==
                                                        labelName
                                                    }
                                                    onChange={() => {
                                                        setSelectedRadio(
                                                            labelName
                                                        );
                                                    }}
                                                />
                                                <Form.Check.Label
                                                    style={{ color: "black" }}
                                                >
                                                    {labelName}
                                                </Form.Check.Label>
                                            </Form.Check>
                                        </div>
                                    ))}
                                </Col>
                            </Row>
                        </Form.Group>
                        <Row className="text-end">
                            <Col>
                                <Button
                                    id="pwSaveButton"
                                    variant="light"
                                    onClick={handleUpdateAssetInfo}
                                    disabled={disableSave}
                                >
                                    Save
                                </Button>
                                <b> </b>
                                <Button
                                    id="pwCancelButton"
                                    variant="light"
                                    onClick={handleCloseEditForm}
                                >
                                    Cancel
                                </Button>
                            </Col>
                        </Row>
                    </Form>
                </Row>
            </Container>
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
    );
}
