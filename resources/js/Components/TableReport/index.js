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
import ReportTable from "./ReportTable";
import Export from "../Export"
import _ from "lodash";
import { Button } from "react-bootstrap";


export default function TableReport() {
  const [currentButton, setFilter] = React.useState(["3"]);
  const [currentSearch, setCurrentSearch] = React.useState("");
  const [page, setPage] = React.useState(1);
  const [total, setTotal] = React.useState(1);
  const [sortArray, setSortArray] = React.useState([]);
  const [deleteAssignment, setDeleteAssignment] = React.useState({ show: false, id: 0 });
  const [filterCategory, setFilterCategory] = React.useState([]);
  const [filterByDate, setFilterByDate] = React.useState([]);

  const [arrayState, setArrayState] = React.useState([{ key: 'All', value: '3' }]);

  const [tableHeader, setTableHeader] = React.useState([
    {
      name: "Category",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Total",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Assigned",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Available",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Not available",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Waiting for recycling",
      isSortASC: true,
      isSortDESC: false,
    },
    {
      name: "Recycled",
      isSortASC: true,
      isSortDESC: false,
    },
  ]);

  const [data, setData] = React.useState([]);

  React.useEffect(() => {
    getApiReport({
      FilterByState: arrayState,
    });
  }, []);



  const getApiReport = async ({
    FilterByDate = undefined,
    FilterByState = undefined,
    search = undefined,
    page = 1,
    sort = undefined,
  } = {}) => {
    let url = "api/report";
    let array = [];
    console.log(FilterByDate)

    if (page) {
      array.push(`page=${page}`);
    }

    if (sort) {
      sort.forEach((item) => {
        if (item.key === "Category") {
          array.push(`sortByCategory=${item.value}`);
        }
        if (item.key === "Total") {
          array.push(`sortByTotal=${item.value}`);
        }
        if (item.key === "Assigned") {
          array.push(`sortByAssigned=${item.value}`);
        }
        if (item.key === "Available") {
          array.push(`sortByAvailable=${item.value}`);
        }
        if (item.key === "Not available") {
          array.push(`sortByNotAvailable=${item.value}`);
        }
        if (item.key === "Waiting for recycling") {
          array.push(`sortByWaitingForRecycling=${item.value}`);
        }
        if (item.key === "Recycled") {
          array.push(`sortByRecycled=${item.value}`);
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
    console.log(response)
    setData(response.data.data);
    setTotal(response.data.meta.total);
    return response.data;
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

    getApiReport({
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
    getApiReport({
      FilterByState: temp_filter_state,
      search: temp_search,
      page: temp_page,
      sort: tempSortArray,
      FilterByDate: temp_filter_date,
    });
  };






  return (
    <div className="containermanageuser">
      <h5 style={{ color: "red", fontWeight: "bold" }}>Report</h5>

      <div id="div-btn-export">
        <Export csvData={data} fileName="Report"/>

        {/* <Button id="btn-export" >
                    Export
                </Button> */}

      </div>
      <Row id="table-container">
        <div id="table-manage-user">
          <ReportTable
            data={data}
            tableHeader={tableHeader}
            Nodata={Nodata}
            handleSort={handleSort}
          />
        </div>
      </Row>
      <Row id="pagination-container">
        <CustomPagination
          total={total}
          page={page}
          handlePageChange={handlePageChange}
        />
      </Row>
    </div>
  );
}
