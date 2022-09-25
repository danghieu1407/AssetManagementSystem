import React from 'react';
import {
    Form, InputGroup, Button
} from 'react-bootstrap'
import {
    FaSearch,
} from "react-icons/fa"
import { Link } from "react-router-dom";

export default function Search({
    currentSearch,
    handleSearch, setCurrentSearch
}) {

    const [currentSearchValue, setCurrentSearchValue] = React.useState('');

    return (
        <div className="d-flex justify-content-end">
            <Form onSubmit={(e) => handleSearch(e, currentSearchValue)} >
                <InputGroup className="search-bar">
                    <Form.Control
                        placeholder="Search"
                        aria-label="Text input with dropdown button"
                        value={currentSearchValue}
                        onChange={(e) => setCurrentSearchValue(e.target.value)}
                    />
                    <InputGroup.Text
                        id="basic-addon2"
                        onClick={(e) => handleSearch(e, currentSearchValue)}
                    >
                        {" "}
                        <FaSearch />
                    </InputGroup.Text>
                </InputGroup>
            </Form>
     
        </div>
    );
}