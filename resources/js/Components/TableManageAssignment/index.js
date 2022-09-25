/* eslint-disable no-unused-vars */
import React from "react";
import "./style.scss";
import "./style.css";
import { Loading } from "notiflix/build/notiflix-loading-aio";
import Row from "react-bootstrap/Row";
import axios from "axios";
import Swal from "sweetalert2";
import { getUserEdit } from "../../Actions/user.action";
import { useDispatch, useSelector } from "react-redux";
import Nodata from "../../../assets/Nodata.png";
import assignmentService from "../../Services/assignment.service";
import CustomPagination from "./CustomPagination";
import AssignmentTable from "./AssignmentTable";
import FilterByState from "./FilterByState";
import SearchCreate from "./SearchCreate";
import AssignmentDetailModal from "./AssignmentDetailModal";
import DeleteAsset from "../DeleteAsset";
import FilterByAssignedDate from "./FilterByAssignedDate";
import _ from "lodash";
import DeleteAssignment from "../DeleteAssignment";
import CreateReturn from "../CreateReturn";


export default function ManageAssignment() {
  const [currentButton, setFilter] = React.useState(["3"]);
  const [currentSearch, setCurrentSearch] = React.useState("");
  const [page, setPage] = React.useState(1);
  const [total, setTotal] = React.useState(1);
  const [sortArray, setSortArray] = React.useState([]);
  const [deleteAssignment, setDeleteAssignment] = React.useState({ show: false, id: 0 });
  const [filterCategory, setFilterCategory] = React.useState([]);
  const [filterByDate, setFilterByDate] = React.useState([]);
  const [modal, setModal] = React.useState(false);
  const [arrayState, setArrayState] = React.useState([{ key: 'All', value: '3' }]);
  const [createReturn, setCreateReturn] = React.useState({ show: false, id: 0 });
    // console.log('createReturn', createReturn)

  const sort_create_at = useSelector(
    (state) => state.userEditReducer.sort_update_at
  );

  const sort_at_get_mesage = useSelector(
    (state) => state.assetGetMessageReducer.sort_at
  );

    const handleCreateReturn = (e, id) => {
        e.stopPropagation();
        // console.log("handleCreateReturn", id);
        setCreateReturn({ show: true, id: id});
        setTimeout(() => setCreateReturn({ show: false, id: id }), 1);
    }
  const [tableHeader, setTableHeader] = React.useState([
    {
      name: "No.",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Asset Code",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Asset Name",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Assigned to",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Assigned by",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Assigned Date",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "State",
      isSortASC: true,
      isSortDESC: false,
    },
  ]);

  const [data, setData] = React.useState([]);

  React.useEffect(() => {
    getApiAssignment({
      FilterByState: arrayState,
    });
  }, []);

  const handleDeleteAssignment = (e, id) => {
    e.stopPropagation();
    // console.log("id", id);
    setDeleteAssignment({ show: true, id: id});
    setTimeout(() => setDeleteAssignment({ show: false, id: id }), 1);
  }


  const getApiAssignment = async ({
    FilterByDate = undefined,
    FilterByState = undefined,
    search = undefined,
    page = 1,
    sort = undefined,
  } = {}) => {
    let url = "api/assignment";
    let array = [];
    // console.log(FilterByDate)

    if (FilterByDate) {
      if (FilterByDate.length > 0 ) {
      array.push(`filterByDate=${FilterByDate}`);
      }
      else {
        array.push('');
      }
    }

    if (sort_at_get_mesage === 'sortByEditAssignment') {
      array.push('sortByEditAssignment');
    }
    if (sort_at_get_mesage === 'sortByCreateAssignment') {
      array.push('sortByCreateAssignment');
    }


    if (FilterByState) {
      if (FilterByState.length > 0) {
        if (FilterByState && FilterByState !== "3") {
          const numberValue = [];
          FilterByState.forEach((item) => {
            numberValue.push(item.value);
          })
          const stringFilter = numberValue.toString();
          array.push(`filterByStateManage=${stringFilter}`);
        }
      }
    }

    if (search) {
      array.push(`search=${search}`);
    }

    if (page) {
      array.push(`page=${page}`);
    }

    if (sort) {
      sort.forEach((item) => {
        if (item.key === "No.") {
          array.push(`sortByNo=${item.value}`);
        }
        if (item.key === "Asset Code") {
          array.push(`sortByAssetCode=${item.value}`);
        }
        if (item.key === "Asset Name") {
          array.push(`sortByAssetName=${item.value}`);
        }
        if (item.key === "Assigned to") {
          array.push(`sortByAssignedTo=${item.value}`);
        }
        if (item.key === "Assigned by") {
          array.push(`sortByAssignedBy=${item.value}`);
        }
        if (item.key === "Assigned Date") {
          array.push(`sortByAssignedDate=${item.value}`);
        }
        if (item.key === "State") {
          array.push(`sortByState=${item.value}`);
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
      backgroundColor: "rgba(255, 255, 255, 0.44)"
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

  const handleFilterDate = (date) => {
    setPage(1);
    let temp_filter_state;
    let temp_search;
    let temp_sort;
    if (arrayState.length > 0) {
      temp_filter_state = JSON.parse(JSON.stringify(arrayState));
    }
    if (currentSearch !== "") {
      temp_search = currentSearch;
    }
    if (sortArray.length > 0) {
      temp_sort = JSON.parse(JSON.stringify(sortArray));
    }


    setFilterByDate(date);
    getApiAssignment({
      FilterByDate: date,
      FilterByState: temp_filter_state,
      search: temp_search,
      sort: temp_sort,
    });
  };
  const handleFilter = (key, value) => {
    setPage(1);

    let arrayStateTemp = JSON.parse(JSON.stringify(arrayState));
    if (key !== 'All') {
      const findIndex = arrayStateTemp.findIndex((item) => item.key === 'All');
      if (findIndex !== -1) {
        arrayStateTemp.splice(findIndex, 1);
      }
    }
    const index = arrayStateTemp.findIndex((e) => e.value === value);
    if (index === -1) {
      arrayStateTemp.push({ key, value });
    } else {
      arrayStateTemp.splice(index, 1);
      if (arrayStateTemp.length === 0) {
        arrayStateTemp.push({ key: 'All', value: '3' });
      }
    }


    setArrayState(arrayStateTemp);

    let temp_search;
    let temp_sort;
    let temp_filter_date;
    if (filterByDate.length > 0) {
      temp_filter_date = JSON.parse(JSON.stringify(filterByDate));
    }

    if (sortArray.length > 0) {
      temp_sort = JSON.parse(JSON.stringify(sortArray));
    }
    if (currentSearch !== "") {
      temp_search = currentSearch;
    }

    getApiAssignment({
      FilterByState: arrayStateTemp,
      search: temp_search,
      sort: temp_sort,
      FilterByDate: temp_filter_date,
    });
  };
  const handleSearch = (e, value) => {
    setPage(1);
    e.preventDefault();
    setCurrentSearch(value);

    let temp_filter_state;
    let temp_page;
    let temp_filter_date;
    let temp_sort;

    if (arrayState.length > 0) {
      temp_filter_state = JSON.parse(JSON.stringify(arrayState));
    }

    if (page >= 1) {
      temp_page = page;
    }
    // console.log(filterByDate);
    if (filterByDate.length > 0) {
      temp_filter_date = JSON.parse(JSON.stringify(filterByDate));
    }

    if (sortArray.length > 0) {
      temp_sort = JSON.parse(JSON.stringify(sortArray));
    }

    getApiAssignment({
      FilterByState: temp_filter_state,
      search: value,
      page: temp_page,
      sort: temp_sort,
      FilterByDate: temp_filter_date,
    });
  };
  const handlePageChange = (pageNumber) => {
    setPage(pageNumber);

    let temp_filter_state;
    let temp_search;
    let temp_filter_date;
    let temp_sort;

    if (arrayState.length > 0) {
      temp_filter_state = JSON.parse(JSON.stringify(arrayState));
    }

    if (currentSearch !== "") {
      temp_search = currentSearch;
    }

    if (filterByDate.length > 0) {
      temp_filter_date = JSON.parse(JSON.stringify(filterByDate));
    }

    if (sortArray.length > 0) {
      temp_sort = JSON.parse(JSON.stringify(sortArray));
    }

    getApiAssignment({
      FilterByState: temp_filter_state,
      search: temp_search,
      page: pageNumber,
      sort: temp_sort,
      FilterByDate: temp_filter_date,
    });
  };
  const handleSort = (key, value) => {
    let temp_filter_state;
    let temp_page;
    let temp_search;
    let temp_filter_date;

    if (arrayState.length > 0) {
      temp_filter_state = JSON.parse(JSON.stringify(arrayState));
    }
    if (currentSearch !== "") {
      temp_search = currentSearch;
    }
    if (page) {
      temp_page = page;
    }
    if (filterByDate.length > 0) {
      temp_filter_date = JSON.parse(JSON.stringify(filterByDate));
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
        if (i != indexHeader && i != 7) {
          tempHeader[i].isSortASC = true;
          tempHeader[i].isSortDESC = false;
        }
        if (i === 7) {
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
        if (i != 7) {
          tempHeader[i].isSortASC = true;
          tempHeader[i].isSortDESC = false;
        }
      }
    }


    setTableHeader(tempHeader);
    getApiAssignment({
      FilterByState: temp_filter_state,
      search: temp_search,
      page: temp_page,
      sort: tempSortArray,
      FilterByDate: temp_filter_date,
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
  const [assignment, setAssignment] = React.useState([]);

  const handleGetAssignmentById = async (assignmentId) => {
    const response = await assignmentService.getAssignmentById(assignmentId);
    setModal(true);
    // console.log(response.data.data);
    setAssignment(response.data.data);
  }

  return (
    <div className="containermanageuser">
        <CreateReturn show={createReturn.show} id={createReturn.id} />
        <DeleteAssignment show={deleteAssignment.show} id={deleteAssignment.id} />
      <h5 style={{ color: "red", fontWeight: "bold" }}>Assignment List </h5>
      <div id="filter-search" className="d-flex justify-content-between type-seach-create">
        <div className="d-flex ml-2">
          <FilterByState
            currentButton={currentButton}
            handleFilter={handleFilter}
            arrayState={arrayState}
          />
          <div id="secondFilterAsset">
            <FilterByAssignedDate handleFilterDate={handleFilterDate} />
          </div>
        </div>

        <div id="search-create" className="d-flex search-create">
          <SearchCreate
            currentSearch={currentSearch}
            handleSearch={handleSearch}
            setCurrentSearch={setCurrentSearch}
          />
        </div>
      </div>
      <Row >
          <div id="table-manage-user">
          <AssignmentTable
            data={data}
            tableHeader={tableHeader}
            Nodata={Nodata}
            handleSort={handleSort}
            handleOpenEditForm={handleOpenEditForm}
            handleGetAssignmentById={handleGetAssignmentById}
            handleDeleteAssignment={handleDeleteAssignment}
            handleCreateReturn={handleCreateReturn}
          />
        </div>
      </Row>
      <Row id = "pagination-container">
        <CustomPagination
          total={total}
          page={page}
          handlePageChange={handlePageChange}
        />
      </Row>
      <AssignmentDetailModal
        modal={modal}
        assignment={assignment}
        setModal={setModal}
      />
    </div>
  );
}
