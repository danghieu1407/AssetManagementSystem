import React, { useState, useEffect } from "react";
import "./style.scss";
import "./toast.css";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import { Row, Container, Col } from "reactstrap";
import { useSelector, useDispatch } from "react-redux";
import userEditReducer from "../../../Reducers/userEdit.reducer";
import UserService from "../../../Services/user.service";
import { getUserEdit } from "../../../Actions/user.action";
import {Modal} from "react-bootstrap";


export default function EditForm() {
    const userEditInfo = useSelector(
        (state) => state.userEditReducer.userEditInfo
    );
    const [date, setDate] = useState(userEditInfo.date_of_birth);
    const [joinDate, setJoinDate] = useState(userEditInfo.joined_date);
    const [selectedRadio, setSelectedRadio] = useState(
        userEditInfo.gender || userEditInfo.gender == null ? "Male" : "Female"
    );
    const [dateOfBirthError, setDateOfBirthError] = React.useState({error: false, message: ""})
    const [joinedDateError, setJoinedDateError] = React.useState({error: false, message: ""})
    const [disableSubmit, setDisableSubmit] = React.useState(true)
    const [showModal, setShowModal] = React.useState(false)
    const [modalHeader, setModalHeader] = React.useState("")
    const [modalBody, setModalBody] = React.useState("")
    const type = userEditInfo.type ? "Admin" : "Staff";
    const dispatch = useDispatch();
    function handleCloseEditForm(e) {
        e.preventDefault();
        const userId = userEditInfo.id;
        const displayValue = false;
        dispatch(getUserEdit(displayValue, userId));
    }

    async function handleUpdateUserInfo(e) {
        e.preventDefault();
        const userId = userEditInfo.id;
        const date_of_birth = e.target.form[2].value;
        const gender = e.target.form[4].checked ? 1 : 0;
        const joined_date = e.target.form[5].value;
        const type = e.target.form[6].value === "Admin" ? 1 : 0;

        const response = await UserService.updateUserInfo({
            'userId' : userId,
            'date_of_birth' : date_of_birth,
            'gender' : gender,
            'joined_date' : joined_date,
            'type' : type
        });

        const message = response.data == undefined ? response.message : response.data.message;
        const code = response.code;
        handleShowMessage(code, message, userId);
    }

    function handleShowMessage(code, message, userId) {
        setShowModal(true)
        switch (code) {
            case 200:
            {
                setModalHeader("Success")
                setModalBody(message)
                setTimeout(
                    () => {const displayValue = false;
                        dispatch({
                            type: 'GET_MESSAGE',
                            payload: {
                                sort_at: 'sortByEditUser' || ''
                            },
                        });
                        dispatch(getUserEdit(displayValue, userId,'sortByEditUser'))},
                    1500);
            }
                break;
            case 422:
                setModalHeader('Failed!')
                setModalBody(message)
                setTimeout(() => {
                    setShowModal(false)
                }, 1500);
                break;
        }
    }

        // Validate date
          useEffect(() => {
            handleDateOfBirthCheck(date);
            handleJoinDateCheck(joinDate);
            }, [date,joinDate]);

    const handleDateOfBirthCheck = (e)=>{
        setDate(e);
        if (new Date(e).getFullYear() > (new Date().getFullYear()-18)) {
            setDateOfBirthError({
                error: true,
                message: "User is under 18. Please select a different date"
            })
            setDisableSubmit(true)
        }
        else {
            setDateOfBirthError({error:false,message:""});
            if(joinedDateError.error === false)  setDisableSubmit(false)
        }
    }

    const handleJoinDateCheck = (e)=>{
        setJoinDate(e)
        if (e<date) {
            setJoinedDateError({
                error: true,
                message: "Joined date is not later than Date of Birth. Please select a different date"
            })
            setDisableSubmit(true)
        }
        else if(new Date(e).getDay() === 0 || new Date(e).getDay() === 6) {
            setJoinedDateError({
                error: true,
                message: "Joined date is Saturday or Sunday. Please select a different date"
            })
            setDisableSubmit(true)
        }
        else {
            setJoinedDateError({error: false, message: ""})
            if(dateOfBirthError.error === false)  setDisableSubmit(false)
        }
    }

    return (
        <>
            <Container id="containerFormEdit">
                <Row className="mb-3">
                    <Col
                        md={4}
                        className="editUser fs-4 mx-3"
                    >
                        Edit User
                    </Col>
                    <Col md={8}></Col>
                </Row>
                <Row>
                    <Form className="fs-5">
                        <Form.Group className="mb-3" controlId="firstNameForm">
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">First Name</Form.Label>
                                </Col>
                                <Col md={8}>
                                    <Form.Control
                                        id="userNameDisable"
                                        type="input"
                                        value={userEditInfo.first_name}
                                        className="fs-5"
                                        disabled
                                    />
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group className="mb-3" controlId="lastNameForm">
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">Last Name</Form.Label>
                                </Col>
                                <Col md={8}>
                                    <Form.Control
                                        id="userNameDisable"
                                        type="text"
                                        className="fs-5"
                                        value={userEditInfo.last_name}
                                        disabled
                                    />
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group className="mb-3" controlId="DateOfBirthForm">
                            <Row>
                                <Col md={4} >
                                    <Form.Label className="mx-4">Date of Birth</Form.Label>
                                </Col>
                                <Col md={8}>
                                    <Form.Control
                                        type="date"
                                        value={date}
                                        placeholder="Due Date"
                                        className="fs-5"
                                        onChange={(e) => handleDateOfBirthCheck(e.target.value)}
                                        isInvalid={dateOfBirthError.error}
                                    />
                                    <Form.Control.Feedback type="invalid">{dateOfBirthError.message}</Form.Control.Feedback>
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group className="mb-3" controlId="genderForm">
                            <Row>
                                <Col md={4} >
                                    <Form.Label className="mx-4">Gender</Form.Label>
                                </Col>
                                <Col md={8} style={{ display: "inherit" }}>
                                    {["Female", "Male"].map((labelName) => (
                                        <div key={labelName} className="mb-3">
                                            <Form.Check inline>
                                                <Form.Check.Input
                                                    type="radio"
                                                    id={labelName}
                                                    className="fs-5"
                                                    checked={
                                                        selectedRadio === labelName
                                                    }
                                                    name="groupGender"
                                                    isInvalid={
                                                        selectedRadio == labelName
                                                    }
                                                    onChange={() =>
                                                    { setSelectedRadio(labelName)
                                                        setDisableSubmit(false)}
                                                    }
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
                        <Form.Group className="mb-3" controlId="JoinDateForm">
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">Joined Date</Form.Label>
                                </Col>
                                <Col md={8}>
                                    <Form.Control
                                        type="date"
                                        value={joinDate}
                                        placeholder="Due Join Date"
                                        className="fs-5"
                                        onChange={(e) =>
                                            handleJoinDateCheck(e.target.value)
                                        }
                                        isInvalid={joinedDateError.error}
                                    />
                                    <Form.Control.Feedback type="invalid">{joinedDateError.message}</Form.Control.Feedback>
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group className="mb-3" controlId="TypeForm">
                            <Row>
                                <Col md={4}>
                                    <Form.Label className="mx-4">Type</Form.Label>
                                </Col>
                                <Col md={8}>
                                    <Form.Control
                                        as="select"
                                        defaultValue={type}
                                        className="fs-5"
                                        onChange={()=>
                                            setDisableSubmit(false)}
                                    >
                                        <option>Staff</option>
                                        <option>Admin</option>
                                    </Form.Control>
                                </Col>
                            </Row>
                        </Form.Group>
                        <Row className="text-end">
                            <Col>
                                <Button
                                    id="pwSaveButton"
                                    variant="light"
                                    onClick={handleUpdateUserInfo}
                                    disabled={disableSubmit}
                                >
                                    Save
                                </Button>
                                <b>  </b>
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
            <Modal
                show={showModal}
                backdrop="static"
                keyboard={false}
            >
                <Modal.Header>
                    <Modal.Title id="pwChangePasswordHeader">{modalHeader}</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Container id='pwChangePasswordFirstContainer'>
                        <Row>
                            <p id='successAlert'>{modalBody}</p>
                        </Row>
                    </Container>
                </Modal.Body>
            </Modal>
        </>
    );
}
