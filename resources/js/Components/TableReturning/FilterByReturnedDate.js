/* eslint-disable no-unused-vars */
/* eslint-disable react/prop-types */
/* eslint-disable react/jsx-no-duplicate-props */
import DatePicker from "react-datepicker";
import React, { useState } from "react";
import "react-datepicker/dist/react-datepicker.css";
import moment from "moment";

import { Dropdown, Form } from "react-bootstrap";
import { FaFilter } from "react-icons/fa";
export default function FilterByReturnedDate({
  currentButton,
  handleFilter,
  arrayState,
  handleFilterDate,
}) {
  const [startDate, setStartDate] = useState(new Date());
  const [datePickerOpen, setDatePickerOpen] = useState(false);

  return (
    <>
      <Dropdown onClick={() => setDatePickerOpen(true)}>
        <Dropdown.Toggle
          id="button-assigned-date"
          className="filter-button d-flex align-items-center justity-content-center "
        >
          <p className="flex-grow-1 font-weight-bold mb-0">Returned Date</p>
          <div className="fb-icon">
            <FaFilter />
          </div>
        </Dropdown.Toggle>
        <Dropdown.Menu id="menu-datepicker">
          <DatePicker
            open={datePickerOpen}
            onOpenChange={setDatePickerOpen}
            className="datePicker"
            maxDate={new Date()}
            dateFormat="MM/dd/yyyy"
            selected={startDate}
            onChange={(startDate) => {
              handleFilterDate(moment(startDate).format("YYYY-MM-DD"));
              setStartDate(startDate);
            }}
          />
        </Dropdown.Menu>
      </Dropdown>
    </>
  );
}
