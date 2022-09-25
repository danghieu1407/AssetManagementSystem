import React from 'react';
import {
    Form, InputGroup, Button
} from 'react-bootstrap'
import {
    FaSearch,
} from "react-icons/fa"
import { Link } from "react-router-dom";

export default function SearchCreate({
    currentSearch,
    handleSearch, setCurrentSearch
}) {

    const [currentSearchValue, setCurrentSearchValue] = React.useState('');

    return (
        <>
            <Form id="form-search" onSubmit={(e) => handleSearch(e, currentSearchValue)}>
                <InputGroup className="search-bar mb-1">
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
            <Link to="/create-asset">
                <Button id="btn-createnewuser" className="btn-createnewuser" >
                    Create new Asset
                </Button>
            </Link>
        </>
    );
}