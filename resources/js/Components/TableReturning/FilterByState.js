/* eslint-disable react/prop-types */
import React from "react";
import {
    Dropdown, Form
} from "react-bootstrap";
import {
    FaFilter
} from 'react-icons/fa';
export default function FilterByState({
    currentButton,
    handleFilter,
    arrayState
}) {

    return (
        
        <>
    
            <Dropdown>
                <Dropdown.Toggle className="filter-button d-flex align-items-center justity-content-center ">
                    <p className="flex-grow-1 font-weight-bold mb-0">State</p>
                    <div className="fb-icon">
                        <FaFilter />
                    </div>
                </Dropdown.Toggle>
                <Dropdown.Menu>
                    <Form>
                        <Dropdown.Item onClick={() => handleFilter("All", "3")}>
                        <Form.Check
                            type="checkbox"
                            id="checkbox-all"
                            className="font-weight-bold"
                            label="All"
                            checked={arrayState.toString().length >0 && arrayState.findIndex(item => item.value === "3") !== -1 && arrayState[arrayState.toString().length >0 && arrayState.findIndex(item => item.value === "3")].value === "3"}
                            onChange={() => handleFilter("All", "3")}
                            eventKey="3"
                        />
                        </Dropdown.Item>
                        <Dropdown.Item onClick={() => handleFilter("Completed", "1")}>
                        <Form.Check 
                            type="checkbox"
                            id="checkbox-admin"
                            className="font-weight-bold"
                            label="Completed"
                            checked={arrayState.toString().length >0 && arrayState.findIndex(item => item.value === "1") !== -1 && arrayState[arrayState.toString().length >0 && arrayState.findIndex(item => item.value === "1")].value === "1"}

                            onChange={() => handleFilter("Completed", "1")}
                            eventKey="1"
                        />
                        </Dropdown.Item>
                        <Dropdown.Item onClick={() => handleFilter("Wait for returning", "0")}>
                        <Form.Check
                            type="checkbox"
                            id="checkbox-staff"
                            className="font-weight-bold"
                            label="Wait for returning"
                            checked={arrayState.toString().length >0 && arrayState.findIndex(item => item.value === "0") !== -1 && arrayState[arrayState.toString().length >0 && arrayState.findIndex(item => item.value === "0")].value === "0"}
                            onChange={() => handleFilter("Wait for acceptance", "0")}
                            eventkey="0"
                        />
                        </Dropdown.Item>
                    </Form>
                </Dropdown.Menu>
            </Dropdown>
        </>
    );
}