import React from "react";
import "./style.scss";
import "./style.css";
import Table from "react-bootstrap/Table";
import { Loading } from "notiflix/build/notiflix-loading-aio";
import {
  FaAngleDown,
  FaAngleUp,
  FaSearch,
} from "react-icons/fa";
import Pagination from "react-js-pagination";
import Row from "react-bootstrap/Row";
import { Col, Button, Modal, Container } from "react-bootstrap"
import Form from "react-bootstrap/Form";
import InputGroup from "react-bootstrap/InputGroup";
import axios from "axios";
import Nodata from "../../../assets/Nodata.png";
import userService from "../../Services/user.service";

export default function PickUser(props) {
  const [currentSearch, setCurrentSearch] = React.useState("");
  const [page, setPage] = React.useState(1);
  const [total, setTotal] = React.useState(1);
  const [sortArray, setSortArray] = React.useState([]);


  const [tableHeader, setTableHeader] = React.useState([
    {
      name: "Staff Code",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Fullname",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Type",
      isSortASC: true,
      isSortDESC: false,
    },
  ]);

  const [data, setData] = React.useState([]);

  React.useEffect(() => {
    getApiUser();
    
  }, []);

  const getApiUser = async ({
    search = undefined,
    page = 1,
    sort = undefined,
  } = {}) => {
    let url = "api/manageUser";
    let array = [];


    if (search) {
      array.push(`search=${search}`);
    }

    if (page) {
      array.push(`page=${page}`);
    }


    if (sort) {
      sort.forEach((item) => {
        if (item.key === "Staff Code") {
          array.push(`sortByStaffCode=${item.value}`);
        }
        if (item.key === "Fullname") {
          array.push(`sortByFullName=${item.value}`);
        }
        if (item.key === "Type") {
          array.push(`sortByType=${item.value}`);
        }
      });
    }

    for (let i = 0; i < array.length; i++) {
      if (i === 0) {
        url += "?no-paginate&" + array[i];
      } else {
        url += "&" + array[i];
      }
    }

    Loading.dots({
      clickToClose: true,
      svgSize: "100px",
      svgColor: "rgb(220 53 69)",
    });
    const response = await axios.get(url, {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    });
    Loading.remove();
    setData(response.data.data);
    setTotal(response.data.meta.total);
    return response.data;
  };

  const handleSearch = (e) => {
    e.preventDefault();
    let temp_page;
    let temp_sort;
    if (temp_page >= 1) {
      temp_page = page;
    }
    if (sortArray.length > 0) {
      temp_sort = [...sortArray];
    }

    getApiUser({
      search: currentSearch,
      page: temp_page,
      sort: temp_sort,
    });
  };
  const handlePageChange = (pageNumber) => {
    setPage(pageNumber);
    console.log(page);


    let temp_search;
    let temp_sort;



    if (currentSearch !== "") {
      temp_search = currentSearch;
    }

    if (sortArray.length > 0) {
      temp_sort = [...sortArray];
    }

    getApiUser({
      search: temp_search,
      page: pageNumber,
      sort: temp_sort,
    });
  };
  const handleSort = (key, value) => {
    let temp_page;
    let temp_search;
    if (temp_page >= 1) {
      temp_page = page;
    }
    if (temp_search) {
      temp_search = currentSearch;
    }

    const tempSortArray = [{
      key: '',
      value: ''
    }];
    const tempHeader = JSON.parse(JSON.stringify(tableHeader));


    const indexHeader = tempHeader.findIndex((item) => item.name === key);


    if (value) {
      tempSortArray[0].key = key;
      tempSortArray[0].value = 'desc';
      tempHeader[indexHeader].isSortASC = false;
      tempHeader[indexHeader].isSortDESC = true;
      for (let i = 0; i < tempHeader.length; i++) {
        if (i != indexHeader) {
          tempHeader[i].isSortASC = true;
          tempHeader[i].isSortDESC = false;
        }

      }
      setSortArray(tempSortArray);
    }

    if (!value) {
      setSortArray([]);
      tempSortArray[0].key = key;
      tempSortArray[0].value = 'asc';
      for (let i = 0; i < tempHeader.length; i++) {

        tempHeader[i].isSortASC = true;
        tempHeader[i].isSortDESC = false;
      }
    }


    setTableHeader(tempHeader);

    getApiUser({
      search: currentSearch,
      page: page,
      sort: tempSortArray,
    });
  };


  const [user, setUser] = React.useState({ name: '', id: '' })
  const handleGetUserById = async (userId) => {
    const response = await userService.getUserById(userId);
    setUser({ name: response.data.data.full_name, id: response.data.data.id })
  }
  const { show, toggleShow } = props

  return (
    <Modal
      show={show}
      onHide={toggleShow}
      backdrop="static"
      keyboard={false}
      size="lg"
    >
      <Container className="p-4">
        <div className="d-flex justify-content-between mt-2">
          <h5 style={{ color: "red", fontWeight: "bold" }}>Select User</h5>
          <div className="d-flex search-create">
            <Form onSubmit={(e) => handleSearch(e)}>
              <InputGroup className="search-bar">
                <Form.Control
                  placeholder="Search"
                  aria-label="Text input with dropdown button"
                  value={currentSearch}
                  onChange={(e) => setCurrentSearch(e.target.value)}
                />
                <InputGroup.Text
                  id="basic-addon2"
                  onClick={(e) => handleSearch(e)}
                >
                  {" "}
                  <FaSearch />
                </InputGroup.Text>
              </InputGroup>
            </Form>
          </div>
        </div>
          <Table responsive="md" id="table-user">
            <thead>
              <tr>
                <th></th>
                {data.length > 0
                  ? tableHeader.map((item, index) => {
                    return (

                      <th
                        key={index}
                        onClick={() => {
                          handleSort(item.name, item.isSortASC);
                        }}
                      >
                        {item.name}&nbsp;
                        {item.isSortASC && <FaAngleDown />}
                        {item.isSortDESC && <FaAngleUp />}
                      </th>
                    );
                  })
                  : ""}
              </tr>
            </thead>
            <tbody>
              {data.length > 0 ? (
                data.length > 0 &&
                data.map((item) => (

                  <tr  key={item.id}>
                    <td><Form.Check type="radio" id={item.id} name="user" value={item.name} onChange={() => handleGetUserById(item.id)}></Form.Check></td>
                    <td>{item.staff_code}</td>
                    <td>{item.full_name}</td>
                    <td>{item.admin == true ? "Admin" : "Staff"}</td>
                  </tr>

                ))
              ) : (
                <img id="nodataimg-create" src={Nodata} />
              )}
            </tbody>
          </Table>
        <div className="d-flex justify-content-end">
            {/* {page > 0 ? (
              <Pagination
              className="mb-0 mr-2"
                activePage={page}
                itemsCountPerPage={20}
                totalItemsCount={total}
                pageRangeDisplayed={3}
                prevPageText="Previous"
                nextPageText="Next"
                itemClass="page-item"
                linkClass="page-link"
                linkClassPrev="page-prev"
                linkClassNext="page-next"
                activeLinkClass="pagination-active"
                hideFirstLastPages={true}
                onChange={(page) => handlePageChange(page)}
              />
            ) : (
              ""
            )} */}
          
          <div>
            <Button id="usSaveButton" onClick={() => { toggleShow(); props.setUserName(user) }}>Save</Button>
          
            <Button id="usCancelButton" onClick={toggleShow} className="btn btn-secondary mr-2" >Cancel</Button>
            </div>
          </div>
      </Container>
    </Modal>
  );
};
