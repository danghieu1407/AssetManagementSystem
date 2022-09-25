import React from "react";
import "./style.scss";
import "./style.css";
import Table from "react-bootstrap/Table";
import { Loading } from "notiflix/build/notiflix-loading-aio";
import moment from "moment";

import {
  FaAngleDown,
  FaAngleUp,
  FaPencilAlt,
  FaRegTimesCircle,
  FaFilter,
  FaSearch,
  FaRegWindowClose
} from "react-icons/fa";
import Pagination from "react-js-pagination";
import Row from "react-bootstrap/Row";
import Dropdown from "react-bootstrap/Dropdown";
import Form from "react-bootstrap/Form";
import InputGroup from "react-bootstrap/InputGroup";
import { Button } from "react-bootstrap";
import axios from "axios";
import Swal from "sweetalert2";
import { getUserEdit } from "../../Actions/user.action";
import { useDispatch,useSelector } from "react-redux";
import userEditReducer from "../../Reducers/userEdit.reducer";
import Nodata from "../../../assets/Nodata.png";
import { Link } from "react-router-dom";
import DisableUser from "../DisableUser";
import Modal from 'react-bootstrap/Modal';
import userService from "../../Services/user.service";

export const ManageUser = () => {
  const [currentButton, setFilter] = React.useState("All");
  const [currentSearch, setCurrentSearch] = React.useState("");
  const [page, setPage] = React.useState(1);
  const [total, setTotal] = React.useState(1);
  const [sortArray, setSortArray] = React.useState([]);
  const [disableUser, setDisableUser] = React.useState({ show: false, id: 0, type: '' });
  const [modal, setModal] = React.useState(false);

   const sort_update_at = useSelector(
    (state) => state.userEditReducer.sort_update_at
);

const sort_create_at = useSelector(
  (state) => state.userEditReducer.sort_update_at
);

  const sort_at_get_mesage = useSelector(
    (state) => state.assetGetMessageReducer.sort_at
  );

  console.log('sort_at_get_mesage',sort_at_get_mesage);
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
      name: "Username",
    },
    {
      name: "Joined Date",
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
  const handleDisableUser = async (e, id) => {
    e.stopPropagation();
    const response = await axios.get(`/api/can-disable/${id}`);
    const disable = response.data.disable
    if (disable) {
        setDisableUser({show: true, id: id, type: 'delete'})
    }
    else {
        setDisableUser({show: true, id: id, type: 'warning'})
    }
  }

  const handleDisableUserDialog = async () => {
    try {
        const token = localStorage.getItem('token')
        const headers = {headers: {Authorization: `Bearer ${token}`}};
        const id = disableUser.id
        await axios.get(`/api/disable/${id}`, headers);
        setDisableUser({ show: false, id: 0, type: '' })
        window.location.reload();
    } catch (e) {
        const error = new Error("Something went wrong");
        throw error;
    }
}

    const handleClose = () => {
        setDisableUser({ show: false, id: 0, type: '' })
    }

  const getApiUser = async ({
    filter = undefined,
    search = undefined,
    page = 1,
    sort = undefined,
  } = {}) => {
    let url = "api/manageUser";
    let array = [];

    if (filter && filter !== "All") {
      array.push(`filter=${filter === "Admin" ? true : false}`);
    }

    if (search) {
      array.push(`search=${search}`);
    }

    if (page) {
      array.push(`page=${page}`);
    }
    if(sort_update_at === 'sortByEditUser'){
      array.push('sortByEditUser');

    }
    if(sort_create_at ===  'sortByCreateUser'){
      array.push('sortByCreateUser');
    }





    if (sort) {
      sort.forEach((item) => {
        if (item.key === "Staff Code") {
          array.push(`sortByStaffCode=${item.value}`);
        }
        if (item.key === "Fullname") {
          array.push(`sortByFullName=${item.value}`);
        }
        if (item.key === "Joined Date") {
          array.push(`sortByJoinedDate=${item.value}`);
        }
        if (item.key === "Type") {
          array.push(`sortByType=${item.value}`);
        }
      });
    }

    for (let i = 0; i < array.length; i++) {
      if (i === 0) {
        url += "?" + array[i];
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
  const handleFilter = (value) => {
    setFilter(value);
    let temp_page;
    let temp_search;
    let temp_sort;
    if (temp_search) {
      temp_search = currentSearch;
    }

    if (temp_page >= 1) {
      temp_page = page;
    }
    if (sortArray.length > 0) {
      temp_sort = [...sortArray];
    }



    setPage(1);

    getApiUser({
      filter: value,
      page: temp_page,
      temp_search: temp_search,
      sort: temp_sort,
    });
  };

  const handleSearch = (e) => {
    e.preventDefault();
    let temp_filter;
    let temp_page;
    let temp_sort;
    if (currentButton !== "All") {
      temp_filter = currentButton;
    }
    if (temp_page >= 1) {
      temp_page = page;
    }
    if (sortArray.length > 0) {
      temp_sort = [...sortArray];
    }

    getApiUser({
      filter: temp_filter,
      search: currentSearch,
      page: temp_page,
      sort: temp_sort,
    });
  };
  const handlePageChange = (pageNumber) => {
    setPage(pageNumber);
    console.log(page);

    let temp_filter;
    let temp_search;
    let temp_sort;

    if (currentButton !== "All") {
      temp_filter = currentButton;
    }

    if (currentSearch !== "") {
      temp_search = currentSearch;
    }

    if (sortArray.length > 0) {
      temp_sort = [...sortArray];
    }

    getApiUser({
      filter: temp_filter,
      search: temp_search,
      page: pageNumber,
      sort: temp_sort,
    });
  };
  const handleSort = (key, value) => {
    let temp_filter;
    let temp_page;
    let temp_search;

    if (currentButton !== "All") {
      temp_filter = currentButton;
    }

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
        if (i != indexHeader && i != 5 && i != 2) {
          tempHeader[i].isSortASC = true;
          tempHeader[i].isSortDESC = false;
        }
        if (i === 5) {
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
        if (i != 5 && i != 2) {
          tempHeader[i].isSortASC = true;
          tempHeader[i].isSortDESC = false;
        }
      }
    }


    setTableHeader(tempHeader);

    getApiUser({
      filter: temp_filter,
      search: currentSearch,
      page: page,
      sort: tempSortArray,
    });
  };

  const dispatch = useDispatch();
  async function handleOpenEditForm(e, userId = "") {
    e.stopPropagation();
    const displayValue = true;
    const response = await dispatch(getUserEdit(displayValue, userId));
    handleShowMessage(response);
  }

  function handleShowMessage(response) {
    const message =
      response.data == undefined ? response.message : response.data.message;
    const code = response.code;
    switch (code) {
      case 200:
        {
          //
        }
        break;
      case 401:
        {
          Swal.fire({
            position: "center",
            icon: "info",
            title: message,
            showConfirmButton: false,
            timer: 2000,
          });
        }
        break;
    }
  }
  const [user, setUser] = React.useState([]);

  const handleGetUserById = async (userId) => {
    const response =  await userService.getUserById(userId);
    setModal(true);
    console.log(response);
    setUser(response.data.data);
  }

  return (
    <div className="containermanageuser">
      <DisableUser {...disableUser} handleDisableUserDialog = {handleDisableUserDialog} handleClose ={handleClose} />
      <h5 style={{ color: "red", fontWeight: "bold" }}>User List </h5>
      <div id="filter-search" className="d-flex justify-content-between type-seach-create">
        <Dropdown onSelect={() => handleFilter}>
          <Dropdown.Toggle className="filter-button d-flex align-items-center justity-content-center ">
            <p className="flex-grow-1 font-weight-bold mb-0">Type</p>
            <div className="fb-icon">
              <FaFilter />
            </div>
          </Dropdown.Toggle>
          <Dropdown.Menu>
            <Form>
            <Dropdown.Item onClick={() => handleFilter("All")}> 
              <Form.Check
                type="checkbox"
                id="checkbox-all"
                className=" font-weight-bold"
                label="All"
                checked={currentButton === "All"}
                onChange={() => handleFilter("All")}
                eventKey="All"
              />
              </Dropdown.Item>

              <Dropdown.Item onClick={() => handleFilter("Admin")}> 
              <Form.Check
                type="checkbox"
                id="checkbox-admin"
                className="font-weight-bold"
                label="Admin"
                checked={currentButton === "Admin"}
                onChange={() => handleFilter("Admin")}
                eventKey="Admin"
              />
              </Dropdown.Item>
              <Dropdown.Item onClick={() => handleFilter("Staff")}>
              <Form.Check
                type="checkbox"
                id="checkbox-staff"
                className=" font-weight-bold"
                label="Staff"
                checked={currentButton === "Staff"}
                onChange={() => handleFilter("Staff")}
                eventkey="Staff"
              />
              </Dropdown.Item>
            </Form>
          </Dropdown.Menu>
        </Dropdown>

        <div id="search-create" className="d-flex search-create">
          <Form id="form-search" onSubmit={(e) => handleSearch(e)}>
            <InputGroup className="search-bar mb-1">
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
          <Link to="/create-user">
          <Button id="btn-createnewuser" className="btn-createnewuser" >
            Create new user
          </Button>
          </Link>

        </div>
      </div>
      <Row>
        <div id="table-manage-user">
          <Table responsive="md" id= "table-container">
            <thead>
              <tr>
                {data.length > 0
                  ? tableHeader.map((item, index) => {
                      return (
                        <th
                          key={index}
                          onClick={() => {
                            if (item.name !== "Username") {
                              handleSort(item.name, item.isSortASC);
                            }
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
                  <tr key={item.id} onClick={() => handleGetUserById(item.id)}>
                    <td>{item.staff_code}</td>
                    <td>{item.full_name}</td>
                    <td>{item.username}</td>
                    <td>{item.joined_date}</td>
                    <td>{item.admin == true ? "Admin" : "Staff"}</td>
                    <td className="td-without_border">
                      <FaPencilAlt 
                        onClick={(e) => handleOpenEditForm(e, item.id)} id='editUserButton'
                      />{" "}
                      {"  "}
                      <FaRegTimesCircle id="deleteIcon" className="delete-icon"  onClick={(e) => handleDisableUser(e,item.id)} type="button" />
                    </td>
                  </tr>
                ))
              ) : (
                <img id="img-nodata" style={{marginLeft : 370}} src={Nodata}></img>
              )}
            </tbody>
          </Table>
        </div>
      </Row>
      <Row id = "pagination-container">
      {total > 1 ? (
          <Pagination
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
        )}
      </Row>
      <Modal
      show={modal}
      size="lg"
      onHide={() => setModal(false)}
      aria-labelledby="contained-modal-title-vcenter"
      centered
    >
      <Modal.Header className="w-100">
        <Modal.Title id="contained-modal-title-vcenter" className="d-flex justify-content-betweeen align-items-center w-100 flex-grow-1">
          <h4 className="flex-grow-1"> Detailed User Information</h4>
          <FaRegWindowClose onClick={() => setModal(false)} style={{ cursor: 'pointer' }} />
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
          <div className="d-flex">
            <p className="w-25">Staff Code</p>
            <p className="w-75">{user.staff_code}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Full Name</p>
            <p className="w-75">{user.full_name}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Username</p>
            <p className="w-75">{user.username}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Date of Birth</p>
            <p className="w-75">{moment(user.birthday).format('DD/MM/YYYY') }</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Gender</p>
            <p className="w-75">{user.gender}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Joined Date</p>
            <p className="w-75">{user.joined_date}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Type</p>
            <p className="w-75">{user.admin ? 'Admin' : 'Staff'}</p>
          </div>
          <div className="d-flex">
            <p className="w-25">Location</p>
            <p className="w-75">{user.location}</p>
          </div>
      </Modal.Body>
    </Modal>
    </div>
  );
};
