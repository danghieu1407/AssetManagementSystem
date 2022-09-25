import React, { useEffect, useState } from 'react'
import { NavbarBrand } from "reactstrap"
import ListGroup from 'react-bootstrap/ListGroup'
import './style.scss'
import { useDispatch, useSelector } from "react-redux"
import { updateTitleHeader } from "../../Actions"
import nashtechlogo from "../../../assets/nashtech_logo.svg";
import { Link, useLocation } from 'react-router-dom'


export default function Sidebar() {
    const location = useLocation()
    const arrayPath = location.pathname.split('/')
    const pathName = arrayPath[1]
    let activePath = null
    switch (pathName) {
        case 'edit-assignment':
            activePath = '/manage-assignment'
            break
        case 'create-assignment':
            activePath = '/manage-assignment'
            break
        case 'create-asset':
            activePath = '/manage-asset'
            break
        case 'create-user':
            activePath = '/manage-user'
            break
        case 'home':
            activePath = '/home'
            break
        case 'manage-user':
            activePath = '/manage-user'
            break
        case 'manage-asset':
            activePath = '/manage-asset'
            break
        case 'manage-assignment':
            activePath = '/manage-assignment'
            break
        case 'request-for-returning':
            activePath = '/request-for-returning'
            break
        case 'report':
            activePath = '/report'
            break
        default:
            activePath = '/home'
            break
    }
    const [sidebarName, setSidebarName] = useState('Home')
    const dispatch = useDispatch()
    const data = useSelector(state => state.userReducer.userInfo);

    let sidebarRoutes;
    if (data.admin === true) {
        sidebarRoutes = [
            'home', 'manage-user', 'manage-asset', 'manage-assignment', 'request-for-returning', 'report'
        ]
    } else {
        sidebarRoutes = [
            'home'
        ]
    }

    const handleClickSidebar = (e) => {
        let title = e.target.dataset.name
            .split('-').map(char => char.charAt(0).toUpperCase() + char.slice(1))
            .join(" ");
        setSidebarName(title)
    }
    useEffect(() => {
        dispatch(updateTitleHeader(sidebarName))

    }, [handleClickSidebar])

    const dataBindingGrid = () => sidebarRoutes.map((item, index) => {
        const arrTitle = item.split('-')
        let title = ''
        if (arrTitle.length <= 2) {
            title = arrTitle
                .map(char => char.charAt(0).toUpperCase() + char.slice(1))
                .join(" ")
        } else {
            title = arrTitle.map((word) => {
                if (word !== 'for') return word[0].toUpperCase() + word.substring(1)
                else return word[0] + word.substring(1)
            }).join(" ")
        }
        return (
            <ListGroup.Item key={index} href={`/${item}`}>
                <Link to={`/${item}`} data-name={item} onClick={e => handleClickSidebar(e)}>
                    {title}
                </Link>
            </ListGroup.Item>
        )
    })
    return (
        <div className='sidebar-wrap'>
            <div className='row'>
                <div className='sidebar-brand'>
                    <NavbarBrand>
                        <img width="200" height="200" src={nashtechlogo} />
                        <h4><b>Online Asset Management</b></h4>
                    </NavbarBrand>
                </div>
            </div>
            <div className='row'>
                <div className='col-lg-12'>
                    <div className='sidebar-select'>
                        <ListGroup activeKey={activePath}>
                            {dataBindingGrid()}
                        </ListGroup>
                    </div>
                </div>
            </div>
        </div>
    )
}
