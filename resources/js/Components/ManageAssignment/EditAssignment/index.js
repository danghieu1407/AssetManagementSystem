/* eslint-disable react/prop-types */
/* eslint-disable react/display-name */
import React, { useState, useEffect } from "react";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import Dropdown from 'react-bootstrap/Dropdown';
import { Row, Container, Col } from "reactstrap";
import {useDispatch } from "react-redux";
import AssignmentService from "../../../Services/assignment.service";
import UserService from "../../../Services/user.service";
import AssetService from "../../../Services/asset.service";
import { getAssignmentEdit } from "../../../Actions/assignment.action";
import assignmentEditReducer from "../../../Reducers/assignment.reducer";
import {Modal } from "react-bootstrap";
import { Link } from "react-router-dom";
import { useHistory } from "react-router-dom";
import {Scrollbars} from 'react-custom-scrollbars-2';


export default function EditAssignmentForm() {
    const [showModal, setShowModal] = useState(false);
    const [modalHeader, setModalHeader] = useState("");
    const [modalBody, setModalBody] = useState("");
    const [disableSave, setDisableSave] = useState(true);
    const [assignmentInfo, setAssignmentInfo] = useState([]);
    const [userList, setUserList] = useState([]);
    const [assetInfo, setAssetInfo] = useState([]);
    const [userInfo, setUserInfo] = useState([]);
    const [note, setNote] = useState('');
    const [assignedDate, setAssignedDate] = useState(new Date().toISOString().slice(0, 10));
    const [select, setSelect] = useState(false);
    const [items, setItems] = useState([]);

    let history = useHistory();
    const assignmentId = Number.parseInt(window.location.pathname.split("/").at(-1));
    const [assignedDateError, setAssignedDateError] = React.useState({
        error: false,
        message: "",
     });
    const dispatch = useDispatch();
    function handleCloseEditForm(e) {
        e.preventDefault();
        history.push(`/manage-assignment`);
    }

    // Validate date
    const assignedDateCheck = (date) => {
        setAssignedDate(date);
        if (date < new Date().toISOString().slice(0, 10)) {
          setAssignedDateError({
            error: true,
            message: "Only current or future date. Please select a different date",
          });
        } else {
          setSelect(true);
          setAssignedDateError({ error: false, message: "" });
        }
      };

      useEffect(() => {
        setDisableSave(true);
        if ((assignedDate !== '' && assignedDate !== null && assignedDate >= new Date().toISOString().slice(0, 10) )
        && select == true)
            setDisableSave(false);
        }, [assignedDate, select]);
    // Action
    async function handleUpdateAssignmentInfo(e) {
        e.preventDefault();
        const data = {
            assignmentInfo: assignmentInfo.id || '',
            staffId: userInfo.id,
            assetId: assetInfo.id,
            assigned_date: assignedDate || '',
            note: note || '',
        };

        const response = await AssignmentService.updateAssignmentInfo(data);

        const message =
            response.data == undefined
                ? response.message
                : response.data.message;
        const code = response.code;
        handleShowMessage(code, message);
    }

    function handleShowMessage(code, message) {
        setShowModal(true);
        switch (code) {
            case 200:
                {
                    setModalHeader("Success");
                    setModalBody(message);
                    setTimeout(() => {
                        setShowModal(false);
                        dispatch({
                            type: 'GET_MESSAGE',
                            payload: {
                                sort_at: 'sortByEditAssignment' || ''
                            },
                        })
                        history.push(`/manage-assignment`);
                    }, 1500);
                }
                break;
            case 422:
                setModalHeader("Failed!");
                setModalBody(message);
                setTimeout(() => {
                    setShowModal(false);
                }, 1500);
                break;
            case 400:
                setModalHeader("Failed!");
                setModalBody(message);
                setTimeout(() => {
                    setShowModal(false);
                }, 1500);
                break;
        }
    }

    // Get data to show when reload page
    async function fetcDataAssignmentById(){
          const respone = await AssignmentService.getAssignmentEdit(assignmentId);
          setAssignmentInfo(respone.data);
          setUserInfo(respone.data.staff);
          setAssetInfo(respone.data.asset);
          setAssignedDate(respone.data.assigned_date);
          setNote(respone.data.note);
          setItems(items => [...items,{
            id: respone.data.asset.id,
            asset_code: respone.data.asset.asset_code,
            name: respone.data.asset.name
        }])
    }

    async function fetcDataUserList(){
          const respone = await UserService.getUserList();
          setUserList(respone.data.data);
    }

    async function fetcDataAssetList(){
          const respone = await AssetService.getAssetList();
          respone.data.data.map((item) => (
            setItems(items => [...items,{
                id: item.id,
                asset_code: item.asset_code,
                name: item.name
            }])
         ))

    }

    useEffect(() => {
        fetcDataAssignmentById();
        fetcDataUserList();
        fetcDataAssetList();
      }, [])


    function handleSelectUser(e,user){
        setSelect(true);
        setUserInfo(user);
    }

    function handleSelectAsset(e,asset){
        setSelect(true);
        setAssetInfo(asset);
    }

    // Element
    const CustomToggleUser = React.forwardRef(({onClick }, ref) => (
        <div
          ref={ref}
          className = "d-flex justify-content-between"
          style = {{cursor: "pointer"}}
          onClick={(e) => {
            e.preventDefault();
            onClick(e);
          }}
          defaultValue = {userInfo.name || userInfo.full_name}
        >
          <div>
            {userInfo.name || userInfo.full_name}
          </div>
          <div>&#x25bc;</div>
        </div>
      ));

      const CustomMenuUser = React.forwardRef(
        ({style, className, 'aria-labelledby': labeledBy }, ref) => {
          const [searchUser, setSearchUser] = useState('');
          return (
            <div
              ref={ref}
              style={style}
              className={className}
              aria-labelledby={labeledBy}
            >
            <Container className="w-auto">
                <Row>
                    <Col md={12} className="d-flex justify-content-center">
                        <Form.Control
                        autoFocus
                        type="text"
                        placeholder = "Search..."
                        className="mx-3 my-2 w-auto fs-6"
                        onChange={(e) => setSearchUser(e.target.value)}
                        defaultValue = {searchUser}
                        />
                    </Col>
                </Row>
                <Row>
                <Scrollbars style = {{width:"100%" , height: '300px'}}>

                    <ul className="list-unstyled fs-6">
                    {userList.filter(
                    (item) =>
                        {
                            if(searchUser == ""){
                                return item;
                            }
                            else if(item.full_name.toLowerCase().includes(searchUser.toLowerCase()) || item.staff_code.toLowerCase().includes(searchUser.toLowerCase())){
                                return item;
                            }
                        }).map((user) => (
                            <Dropdown.Item key={user.id} onClick ={(e)=>handleSelectUser(e,user)}>
                                <Row>
                                    <Col md={4}>{user.staff_code}</Col>
                                    <Col md={7}>{user.full_name}</Col>
                                    <Col md={1}></Col>
                                </Row>
                        </Dropdown.Item>
                        ))}
                    </ul>
                </Scrollbars>
                </Row>
            </Container>
            </div>
          );
        },
      );

      const CustomToggleAsset = React.forwardRef(({onClick }, ref) => (
        <div
          ref={ref}
          className = "d-flex justify-content-between"
          style = {{cursor: "pointer"}}
          onClick={(e) => {
            e.preventDefault();
            onClick(e);

          }}
        >
          <div>{assetInfo.name || assetInfo}</div>
          <div>&#x25bc;</div>
        </div>
      ));

      const CustomMenuAsset = React.forwardRef(
        ({style, className, 'aria-labelledby': labeledBy }, ref) => {
          const [searchAsset, setSearchAsset] = useState('');
          return (
            <div
              ref={ref}
              style={style}
              className={className}
              aria-labelledby={labeledBy}
            >
            <Container className="w-auto">
                <Row>
                    <Col md={12} className="d-flex justify-content-center">
                        <Form.Control
                        autoFocus
                        type="text"
                        placeholder = "Search..."
                        className="mx-3 my-2 w-auto fs-6"
                        onChange={(e) => setSearchAsset(e.target.value)}
                        defaultValue = {searchAsset}
                        />
                    </Col>
                </Row>
                <Row>
                <Scrollbars style = {{width:"100%" , height: '300px'}}>
                    <ul className="list-unstyled fs-6">
                    {items.filter(
                    (item) =>
                        {
                            if(searchAsset == ""){
                                return item;
                            }
                            else if(item.asset_code.toLowerCase().includes(searchAsset.toLowerCase()) || item.name.toLowerCase().includes(searchAsset.toLowerCase())){
                                return item;
                            }
                        }).map((asset) => (
                            <Dropdown.Item key={asset.id} onClick ={(e)=>handleSelectAsset(e,asset)}>
                                <Row>
                                    <Col md={4}>{asset.asset_code}</Col>
                                    <Col md={7}>{asset.name}</Col>
                                    <Col md={1}></Col>
                                </Row>
                        </Dropdown.Item>
                        ))}
                    </ul>
                </Scrollbars>
                </Row>
            </Container>
            </div>
          );
        },
      );
    return (
        <>
            <Container id="containerFormEdit">
                <Row className="mb-3">
                    <Col md={5} className="editUser fs-4 mx-3">
                        Edit Assignment
                    </Col>
                    <Col md={7}></Col>
                </Row>
                <Row>
                    <Form
                        className="fs-5"
                    >
                        <Form.Group className="mb-3" controlId="UserSelectForm">
                            <Row>
                                <Col md={5}>
                                    <Form.Label className="mx-4">
                                        User
                                    </Form.Label>
                                </Col>
                                <Col md={7}>
                                    <Dropdown className="fs-5 form-control" defaultValue = {userInfo.name || userInfo.full_name || ""}>
                                        <Dropdown.Toggle
                                        as={CustomToggleUser}
                                        id="dropdown-custom-components"
                                        >
                                        </Dropdown.Toggle>

                                        <Dropdown.Menu as={CustomMenuUser}></Dropdown.Menu>
                                    </Dropdown>
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group className="mb-3" controlId="AssetSelectForm">
                            <Row>
                                <Col md={5}>
                                    <Form.Label className="mx-4">
                                        Asset
                                    </Form.Label>
                                </Col>
                                <Col md={7}>
                                    <Dropdown className="fs-5 form-control">
                                        <Dropdown.Toggle as={CustomToggleAsset} id="dropdown-custom-components">
                                         Asset Name
                                        </Dropdown.Toggle>

                                        <Dropdown.Menu as={CustomMenuAsset}></Dropdown.Menu>
                                    </Dropdown>
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group
                            className="mb-3"
                            controlId="AssignedDateForm"
                        >
                            <Row>
                                <Col md={5}>
                                    <Form.Label className="mx-4">
                                        Assigned Date
                                    </Form.Label>
                                </Col>
                                <Col md={7}>
                                    <Form.Control
                                        type="date"
                                        value={assignedDate}
                                        required
                                        className="fs-5"
                                        min={new Date().toISOString().slice(0, 10)}
                                        onChange = {(e) => {assignedDateCheck(e.target.value)}}
                                        isInvalid = {assignedDateError.error}
                                    ></Form.Control>
                                    { <Form.Control.Feedback type="invalid">
                                    {assignedDateError.message}
                                    </Form.Control.Feedback> }
                                </Col>
                            </Row>
                        </Form.Group>
                        <Form.Group
                            className="mb-3"
                            controlId="NoteForm"
                        >
                            <Row>
                                <Col md={5}>
                                    <Form.Label className="mx-4">
                                        Note
                                    </Form.Label>
                                </Col>
                                <Col md={7}>
                                    <Form.Control
                                        as="textarea"
                                        rows={3}
                                        defaultValue={assignmentInfo.note || ''}
                                        onChange = {(e) => {setNote(e.target.value); setSelect(true)}}
                                        className="fs-5"
                                    />
                                </Col>
                            </Row>
                        </Form.Group>
                        <Row className="text-end">
                            <Col>
                                <Button
                                    id="pwSaveButton"
                                    variant="light"
                                    onClick={handleUpdateAssignmentInfo}
                                    disabled={disableSave}
                                >
                                    Save
                                </Button>
                                <b> </b><Link to ='/manage-assignment'>
                                <Button
                                    id="pwCancelButton"
                                    variant="light"
                                    onClick={handleCloseEditForm}
                                >
                                    Cancel
                                </Button>
                                </Link>
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
