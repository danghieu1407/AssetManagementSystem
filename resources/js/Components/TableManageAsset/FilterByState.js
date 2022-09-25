/* eslint-disable react/prop-types */
import React from "react";
import { Dropdown, Form } from "react-bootstrap";
import { FaFilter } from "react-icons/fa";
export default function FilterByState({
  currentButton,
  handleFilter,
  arrayState,
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
                className=" font-weight-bold"
                label="All"
                checked={
                  arrayState.toString().length > 0 &&
                  arrayState.findIndex((item) => item.value === "3") !== -1 &&
                  arrayState[
                    arrayState.toString().length > 0 &&
                      arrayState.findIndex((item) => item.value === "3")
                  ].value === "3"
                }
                onChange={() => handleFilter("All", "3")}
                eventKey="3"
              />
            </Dropdown.Item>

            <Dropdown.Item onClick={() => handleFilter("Assigned", "2")}>
              <Form.Check
                type="checkbox"
                id="checkbox-admin"
                className="font-weight-bold"
                label="Assigned"
                checked={
                  arrayState.toString().length > 0 &&
                  arrayState.findIndex((item) => item.value === "2") !== -1 &&
                  arrayState[
                    arrayState.toString().length > 0 &&
                      arrayState.findIndex((item) => item.value === "2")
                  ].value === "2"
                }
                onChange={() => handleFilter("Assigned", "2")}
                eventKey="2"
              />
            </Dropdown.Item>
            <Dropdown.Item onClick={() => handleFilter("Available", "1")}>
              <Form.Check
                type="checkbox"
                id="checkbox-staff"
                className=" font-weight-bold"
                label="Available"
                checked={
                  arrayState.toString().length > 0 &&
                  arrayState.findIndex((item) => item.value === "1") !== -1 &&
                  arrayState[
                    arrayState.toString().length > 0 &&
                      arrayState.findIndex((item) => item.value === "1")
                  ].value === "1"
                }
                onChange={() => handleFilter("Available", "1")}
                eventkey="1"
              />
            </Dropdown.Item>
            <Dropdown.Item onClick={() => handleFilter("Not Available", "0")}>
              <Form.Check
                type="checkbox"
                id="checkbox-staff"
                className=" font-weight-bold"
                label="Not Available"
                checked={
                  arrayState.toString().length > 0 &&
                  arrayState.findIndex((item) => item.value === "0") !== -1 &&
                  arrayState[
                    arrayState.toString().length > 0 &&
                      arrayState.findIndex((item) => item.value === "0")
                  ].value === "0"
                }
                onChange={() => handleFilter("Not Available", "0")}
                eventkey="0"
              />
            </Dropdown.Item>

            <Dropdown.Item
              onClick={() => handleFilter("Waiting for recycling", "-1")}
            >
              <Form.Check
                type="checkbox"
                id="checkbox-staff"
                className=" font-weight-bold"
                label="Waiting for recycling"
                checked={
                  arrayState.toString().length > 0 &&
                  arrayState.findIndex((item) => item.value === "-1") !== -1 &&
                  arrayState[
                    arrayState.toString().length > 0 &&
                      arrayState.findIndex((item) => item.value === "-1")
                  ].value === "-1"
                }
                onChange={() => handleFilter("Waiting for recycling", "-1")}
                eventkey="-1"
              />
            </Dropdown.Item>

            <Dropdown.Item onClick={() => handleFilter("Recycled", "-2")}>
              <Form.Check
                type="checkbox"
                id="checkbox-staff"
                className=" font-weight-bold"
                label="Recycled"
                checked={
                  arrayState.toString().length > 0 &&
                  arrayState.findIndex((item) => item.value === "-2") !== -1 &&
                  arrayState[
                    arrayState.toString().length > 0 &&
                      arrayState.findIndex((item) => item.value === "-2")
                  ].value === "-2"
                }
                onChange={() => handleFilter("Recycled", "-2")}
                eventkey="-2"
              />
            </Dropdown.Item>
          </Form>
        </Dropdown.Menu>
      </Dropdown>
    </>
  );
}
