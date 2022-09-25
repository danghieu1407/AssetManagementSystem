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
import assetService from "../../Services/asset.service";
import CustomPagination from "./CustomPagination";
import AssetTable from "./AssetTable";
import FilterByState from "./FilterByState";
import SearchCreate from "./SearchCreate";
import AssetDetailModal from "./AssetDetailModal";
import DeleteAsset from "../DeleteAsset";
import FilterByCategory from "./FilterByCategory";
import _ from "lodash";
import assetEditReducer from "../../Reducers/asset.reducer";
import assetGetMessageReducer from "../../Reducers/getMessageTopSort.reducer";

export default function ManageAsset() {
  const [currentButton, setFilter] = React.useState(["3"]);
  const [currentSearch, setCurrentSearch] = React.useState("");
  const [page, setPage] = React.useState(1);
  const [total, setTotal] = React.useState(1);
  const [sortArray, setSortArray] = React.useState([]);
  const [deleteAsset, setDeleteAsset] = React.useState({ show: false, id: 0 });
  const [filterCategory, setFilterCategory] = React.useState(['3']);
  const [disableUser, setDisableUser] = React.useState({ show: false, id: 0 });
  const [modal, setModal] = React.useState(false);
  const [arrayState, setArrayState] = React.useState([{ key: 'Assigned', value: '2' }, { key: 'Available', value: '1' }, { key: 'Not Available', value: '0' }]);
  


  const sort_create_at = useSelector(
    (state) => state.userEditReducer.sort_update_at
  );
  const sort_at = useSelector(
    (state) => state.assetEditReducer.sort_at
  );
  const sort_at_get_mesage = useSelector(
    (state) => state.assetGetMessageReducer.sort_at
  );

  const [tableHeader, setTableHeader] = React.useState([
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
      name: "Category",
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
    getApiUser({
      FilterByState: arrayState,
    });
  }, []);

  const handleDeleteAsset = (e, id) => {
    e.stopPropagation();
    setDeleteAsset({ show: true, id: id });
    setTimeout(() => setDeleteAsset({ show: false, id: id }), 1);
  }


  const getApiUser = async ({
    FilterByCategory = undefined,
    FilterByState = undefined,
    search = undefined,
    page = 1,
    sort = undefined,
  } = {}) => {
    let url = "api/asset";
    let array = [];

    if (FilterByCategory) {
      if (FilterByCategory.length > 0) {
        array.push(`filterByCategory=${FilterByCategory}`);
      }
      else {
        array.push('');
      }
    }
    
    if(sort_at === 'sortByEditAsset'){
      array.push('sortByEditAsset');
  }
    if(sort_at_get_mesage=== 'sortByCreateAsset'){
      array.push('sortByCreateAsset');
  }

    if (FilterByState) {
      if (FilterByState.length > 0) {
        if (FilterByState && FilterByState !== "3") {
          const numberValue = [];
          FilterByState.forEach((item) => {
            numberValue.push(item.value);
          })
          const stringFilter = numberValue.toString();
          array.push(`filterByState=${stringFilter}`);
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
        if (item.key === "Asset Code") {
          array.push(`sortByAssetCode=${item.value}`);
        }
        if (item.key === "Asset Name") {
          array.push(`sortByName=${item.value}`);
        }
        if (item.key === "Category") {
          array.push(`sortByCategory=${item.value}`);
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

  const handleFilterCategory = (id) => {
    let tempFilterCategory = JSON.parse(JSON.stringify(filterCategory));

    const index = tempFilterCategory.findIndex((e) => e === id);
    const indexAll = tempFilterCategory.findIndex((e) => e === "3");
    
    if (index === -1 && id !== "3") {
      if (indexAll !== -1) {
        tempFilterCategory.splice(indexAll, 1);
      }
      tempFilterCategory.push(id);
    }

    if (indexAll === -1 && id === "3") {
      tempFilterCategory = ["3"];
    }

    if (index !== -1 && id !== "3") {
      if (tempFilterCategory.length === 1) {
        tempFilterCategory = ["3"];
      } else {
        tempFilterCategory.splice(index, 1);
      }
    }
  
    setFilterCategory(tempFilterCategory);

    let temp_filter_state;
    let temp_search;
    let temp_sort;
    let temp_page;

    if (arrayState.length > 0) {
      temp_filter_state = JSON.parse(JSON.stringify(arrayState));
    }

    if (page >= 1) {
      temp_page = page;
    }

    if (sortArray.length > 0) {
      temp_sort = JSON.parse(JSON.stringify(sortArray));
    }

    if (currentSearch !== "") {
      temp_search = currentSearch;
    }

    getApiUser({
      FilterByState: temp_filter_state,
      FilterByCategory: tempFilterCategory,
      page: temp_page,
      sort: temp_sort,
      search: temp_search
    });
  };
  const handleFilter = (key, value) => {
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

    let temp_page;
    let temp_search;
    let temp_sort;
    let temp_filter_category;

    if (currentSearch !== "") {
      temp_search = currentSearch;
    }

    if (filterCategory.length > 0) {
      temp_filter_category = JSON.parse(JSON.stringify(filterCategory));
    }

    if (sortArray.length > 0) {
      temp_sort = JSON.parse(JSON.stringify(sortArray));
    }

    if (page >= 1) {
      temp_page = page;
    }


    getApiUser({
      FilterByState: arrayStateTemp,
      page: temp_page,
      search: temp_search,
      sort: temp_sort,
      FilterByCategory: temp_filter_category,
    });
  };
  const handleSearch = (e, value) => {
    e.preventDefault();
    setCurrentSearch(value);

    let temp_filter_state;
    let temp_page;
    let temp_filter_category;
    let temp_sort;

    if (arrayState.length > 0) {
      temp_filter_state = JSON.parse(JSON.stringify(arrayState));
    }

    if (page >= 1) {
      temp_page = page;
    }

    if (filterCategory.length > 0) {
      temp_filter_category = JSON.parse(JSON.stringify(filterCategory));
    }

    if (sortArray.length > 0) {
      temp_sort = JSON.parse(JSON.stringify(sortArray));
    }

    getApiUser({
      FilterByState: temp_filter_state,
      search: value,
      page: temp_page,
      sort: temp_sort,
      FilterByCategory: temp_filter_category,
    });
  };
  const handlePageChange = (pageNumber) => {
    setPage(pageNumber);
    let temp_filter_state;
    let temp_search;
    let temp_filter_category;
    let temp_sort;

    if (arrayState.length > 0) {
      temp_filter_state = JSON.parse(JSON.stringify(arrayState));
    }

    if (currentSearch !== "") {
      temp_search = currentSearch;
    }

    if (filterCategory.length > 0) {
      temp_filter_category = JSON.parse(JSON.stringify(filterCategory));
    }

    if (sortArray.length > 0) {
      temp_sort = JSON.parse(JSON.stringify(sortArray));
    }

    getApiUser({
      FilterByState: temp_filter_state,
      search: temp_search,
      page: pageNumber,
      sort: temp_sort,
      FilterByCategory: temp_filter_category,
    });
  };
  const handleSort = (key, value) => {
    let temp_filter_state;
    let temp_page;
    let temp_search;
    let temp_filter_category;

    if (arrayState.length > 0) {
      temp_filter_state = JSON.parse(JSON.stringify(arrayState));
    }

    if (page >= 1) {
      temp_page = page;
    }

    if (currentSearch !== "") {
      temp_search = currentSearch;
    }

    if (filterCategory.length > 0) {
      temp_filter_category = JSON.parse(JSON.stringify(filterCategory));
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
        if (i != indexHeader && i != 4) {
          tempHeader[i].isSortASC = true;
          tempHeader[i].isSortDESC = false;
        }
        if (i === 4) {
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
        if (i != 4) {
          tempHeader[i].isSortASC = true;
          tempHeader[i].isSortDESC = false;
        }``
      }
    }

    setTableHeader(tempHeader);

    getApiUser({
      FilterByState: temp_filter_state,
      search: temp_search,
      page: temp_page,
      sort: tempSortArray,
      FilterByCategory: temp_filter_category,
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

  const handleGetUserById = async (assetId) => {
    const response = await assetService.getAssetById(assetId);
    setModal(true);
    console.log(response);
    setUser(response.data.data);
  }

  return (
    <div className="containermanageuser">
      <DeleteAsset show={deleteAsset.show} id={deleteAsset.id} />
      <h5 style={{ color: "red", fontWeight: "bold" }}>Asset List </h5>
      <div id="filter-search" className="d-flex justify-content-between type-seach-create">
      <div className="d-flex ml-2">
        <FilterByState
          currentButton={currentButton}
          handleFilter={handleFilter}
          arrayState={arrayState}
        />
        <div id="secondFilterAsset">
        <FilterByCategory handleFilter={handleFilterCategory} filterCategory={filterCategory}  />

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
          <AssetTable
            data={data}
            tableHeader={tableHeader}
            Nodata={Nodata}
            setPage={setPage}
            handleSort={handleSort}
            handleOpenEditForm={handleOpenEditForm}
            handleGetUserById={handleGetUserById}
            handleDeleteAsset={handleDeleteAsset}
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
      <AssetDetailModal
        modal={modal}
        user={user}
        setModal={setModal}
      />
    </div>
  );
}
