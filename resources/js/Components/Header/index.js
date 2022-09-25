import React, { useState, useEffect } from 'react';
import Dropdown from 'react-bootstrap/Dropdown';
import DropdownButton from 'react-bootstrap/DropdownButton';
import { ButtonGroup } from "reactstrap";
import './style.scss'
import ChangePassword from "../ChangePassword";
import ChangePasswordFirst from "../ChangePasswordFirst";
import { useSelector, useDispatch } from "react-redux";
import LogOut from '../LogOut';
import { getUserInfo } from '../../Actions/user.action';
// eslint-disable-next-line no-unused-vars
import assetEditReducer from "../../Reducers/asset.reducer";
import userEditReducer from "../../Reducers/userEdit.reducer";
import homeReducer from "../../Reducers/home.reducer";
import { useLocation } from "react-router-dom";

export default function Header() {

    const assetEditReducer = useSelector((state) => state.assetEditReducer.value);
    const userEditReducer = useSelector((state) => state.userEditReducer.value);

    const location = useLocation()
    const arrayPath = location.pathname.split('/')
    const pathName = arrayPath[1]
    let currentPage = null
    switch (pathName) {
        case 'create-assignment':
            currentPage = 'Manage Assignment > Create New Assignment'
            break
        case 'edit-assignment':
            currentPage = 'Manage Assignment > Edit Assignment'
            break
        case 'create-asset':
            currentPage = 'Manage Asset > Create New Asset'
            break
        case 'create-user':
            currentPage = 'Manage User > Create New User'
            break
        case 'manage-user':
            currentPage = 'Manage User'
            break
        case 'manage-asset':
            currentPage = 'Manage Asset'
            break
        case 'manage-assignment':
            currentPage = 'Manage Assignment'
            break
        case 'request-for-returning':
            currentPage = 'Request For Returning'
            break
        case 'report':
            currentPage = 'Report'
            break
        default:
            currentPage = 'Home'
            break
    }
    useEffect(() => {
        dispatch(getUserInfo())
    }, [])
    const [showChangePassword, setShowChangePassword] = useState(false)
    const [showChangePasswordFirst, setShowChangePasswordFirst] = useState(false)

    const [confirmLogOut, setConfirmLogOut] = useState(false)
    const dispatch = useDispatch()

    const userInformation = useSelector(state => state.userReducer.userInfo)
    const handleShow = () => {
        setConfirmLogOut(true);
        setTimeout(() => setConfirmLogOut(false), 1);
    }

    const handleChangePassword = () => {
        setShowChangePassword(true);
        setTimeout(() => setShowChangePassword(false), 1);
    }

    useEffect(() => {
        if (userInformation.state === 0) setShowChangePasswordFirst(true)
        setTimeout(() => setShowChangePasswordFirst(false), 1);
    }, [userInformation.state])
    return (
        <>
            <header>
                {userEditReducer ? <h5>Manage User > Edit User</h5> : assetEditReducer ? <h5>Manage Asset > Edit Asset</h5> : <h5>{currentPage}</h5>}
                <DropdownButton
                    as={ButtonGroup}
                    key={'down'}
                    id={'dropdown-button-drop-down'}
                    drop={'down'}
                    variant="danger"
                    title={userInformation.username || ''}
                >
                    <Dropdown.Item eventKey="1" onClick={handleChangePassword}>Change Password</Dropdown.Item>
                    <Dropdown.Item eventKey="2">Update profile</Dropdown.Item>
                    <Dropdown.Divider />
                    <Dropdown.Item eventKey="logout" onClick={() => { handleShow() }}>Log out</Dropdown.Item>
                </DropdownButton>
            </header>
            <ChangePassword show={showChangePassword} />
            <ChangePasswordFirst show={showChangePasswordFirst} />
            <LogOut show={confirmLogOut} />
        </>
    )
}
