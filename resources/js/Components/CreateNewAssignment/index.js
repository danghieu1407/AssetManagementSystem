import React from "react";
import { Container, Row, Col, Button, Form, InputGroup } from "react-bootstrap";
import axios from "../../Services/base.service";
import "./style.css";
import { useHistory } from "react-router-dom";
import { useDispatch } from "react-redux";
import PickUser from "./PickUser"
import PickAsset from "./PickAsset";
import {
    FaSearch,
} from "react-icons/fa";
const CreateNewAssignment = () => {
    let history = useHistory();
    const [user, setUser] = React.useState({ name: '', id: '' });
    const [asset, setAsset] = React.useState({ name: '', id: '' });
    const [assignedDate, setAssignedDate] = React.useState(new Date().toISOString().slice(0, 10));
    const [note, setNote] = React.useState("");
    const [mess, setMess] = React.useState("");
    const [enabled, setEnabled] = React.useState(true);


    const [showUser, setShowUser] = React.useState(false);
    const [showAsset, setShowAsset] = React.useState(false);
    const toggleShowUser = () => setShowUser(p => !p);
    const toggleShowAsset = () => setShowAsset(p => !p);

    const [assignedDateError, setAssignedDateError] = React.useState({
        error: false,
        message: "",
    });

    React.useEffect(() => {
        setEnabled(true);
        if (
            user.id !== "" &&
            asset.id !== "" &&
            assignedDate !== ""

        )
            setEnabled(false);
    }, [user, asset, assignedDate]);

    const handleUser = () => {
        toggleShowUser()
    }
    const handleAsset = () => {
        toggleShowAsset();
    }

    const assignedDateCheck = (date) => {
        const date2 = new Date();
        date2.setDate(date2.getDate() - 1);
        if (new Date(date) < date2) {
            setAssignedDateError({
                error: true,
                message: "Only current or future date. Please select a different date",
            });
            setAssignedDate("");
        } else {
            setAssignedDate(date);
            setAssignedDateError({ error: false, message: "" });
        }
    };


    const dispatch = useDispatch();
    const handleSubmit = async (e) => {
        e.preventDefault();
        const data = {
            staff_id: user.id,
            asset_id: asset.id,
            assigned_date: assignedDate,
            note: note,
        };
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        await axios
            .post("/assignment", data, headers)
            .then(function (response) {

                dispatch({
                    type: 'GET_MESSAGE',
                    payload: {
                        sort_at: 'sortByEditAssignment' || ''
                    },
                })
                history.push("/manage-assignment");
            })
            .catch(function () {
                setMess("Something went wrong!");
            });
        // console.log(data)
    };

    return (
        <>
            <Container id="containerFormCreate">
                <h4>
                    <b>Create New Assignment</b>
                </h4>
                <br></br>
                <Form onSubmit={(e) => handleSubmit(e)}>
                    <Form.Group controlId="formBasicText">
                        <Row>
                            <Col>
                                <Form.Label>User</Form.Label>
                            </Col>
                            <Col md={8}>
                                <div className="d-flex search-create" onClick={() => { handleUser() }}>
                                    <Form.Control
                                        readOnly
                                        required
                                        type="text"
                                        value={user.name}

                                    /><InputGroup.Text
                                    >
                                        <FaSearch />
                                    </InputGroup.Text>
                                </div>
                            </Col>
                        </Row>
                    </Form.Group>
                    <br></br>
                    <Form.Group controlId="formBasicText">
                        <Row>
                            <Col>
                                <Form.Label>Asset</Form.Label>
                            </Col>
                            <Col md={8}>
                                <div className="d-flex search-create" onClick={() => { handleAsset() }}>
                                    <Form.Control
                                        readOnly
                                        required
                                        type="text"
                                        value={asset.name}
                                        className="rounded-left"
                                    />
                                    <InputGroup.Text className="rounded-right"
                                    >
                                        <FaSearch />
                                    </InputGroup.Text>
                                </div>
                            </Col>
                        </Row>
                    </Form.Group>

                    <br></br>
                    <Form.Group controlId="formBasicDate">
                        <Row>
                            <Col>
                                <Form.Label>Assigned Date</Form.Label>
                            </Col>
                            <Col md={8}>

                                <Form.Control
                                    value={assignedDate}
                                    required
                                    type="date"
                                    min={new Date().toISOString().slice(0, 10)}
                                    onChange={(e) => { assignedDateCheck(e.target.value) }}
                                    isInvalid={assignedDateError.error}
                                />
                                {<Form.Control.Feedback type="invalid">
                                    {assignedDateError.message}
                                </Form.Control.Feedback>}
                            </Col>
                        </Row>
                    </Form.Group>
                    <br></br>
                    <Form.Group controlId="formBasicTextArea">
                        <Row>
                            <Col>
                                <Form.Label>Note</Form.Label>
                            </Col>
                            <Col md={8}>
                                <Form.Control
                                    as="textarea"
                                    rows={3}
                                    value={note}
                                    onChange={(e) => setNote(e.target.value)}
                                />
                                {/* <Form.Control.Feedback type="invalid">
                  {dateOfBirthError.message}
                </Form.Control.Feedback> */}
                            </Col>
                        </Row>
                    </Form.Group>
                    <br></br>
                    <Form.Group className="text-end">
                        <p className="err-msg">{mess}</p>
                        <Button
                            className="me-3"
                            variant="danger"
                            type="submit"
                            disabled={enabled}
                        >
                            Save
                        </Button>
                        <Button
                            variant="outline-secondary"
                            onClick={() => history.push("/manage-assignment")}
                        >
                            Cancel
                        </Button>
                    </Form.Group>
                </Form>
            </Container>

            <PickUser show={showUser} toggleShow={toggleShowUser} setUserName={setUser} />
            <PickAsset show={showAsset} toggleShow={toggleShowAsset} setUserName={setAsset} />

        </>
    );
};

export default CreateNewAssignment;
