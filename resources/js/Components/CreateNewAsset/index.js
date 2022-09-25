/* eslint-disable react/display-name */
import React from "react";
import {
  Container,
  Row,
  Col,
  Button,
  Form,
  Dropdown,
  DropdownButton,
  Link,
} from "react-bootstrap";
import axios from "../../Services/base.service";
import "./style.css";
import { useHistory } from "react-router-dom";
import { getUserCreate } from "../../Actions/user.action";
import { useDispatch } from "react-redux";
import DropdownItem from "react-bootstrap/esm/DropdownItem";
import { TiTick, TiTimes } from "react-icons/ti";
import { getAssetEdit } from "../../Actions/asset.action";

const CreateNewAsset = () => {
  let history = useHistory();
  const [name, setName] = React.useState("");
  const [category, setCategory] = React.useState("");
  const [categoryId, setCategoryId] = React.useState("");
  const [specification, setSpecification] = React.useState("");
  const [installedDate, setInstalledDate] = React.useState("");
  const [state, setState] = React.useState(null);
  const [mess, setMess] = React.useState("");
  const [enabled, setEnabled] = React.useState(true);
  const [categoryList, setCategoryList] = React.useState([]);

  const getCategory = async () => {
    const token = localStorage.getItem("token");
    const headers = { headers: { Authorization: `Bearer ${token}` } };
    await axios.get("/category", headers).then(function (response) {
      setCategoryList(response.data.category);
    });
  };
  React.useEffect(() => {
    getCategory();
  }, []);

  React.useEffect(() => {
    setEnabled(true);
    if (
      name !== "" &&
      category !== "" &&
      specification !== "" &&
      installedDate !== "" &&
      state !== null
    )
      setEnabled(false);
  }, [name, category, specification, installedDate, state]);

  const dispatch = useDispatch();
  const handleSubmit = async (e) => {
    e.preventDefault();
    const data = {
      name: name,
      category_id: categoryId,
      specification: specification,
      installed_date: installedDate,
      state: state,
    };
    const token = localStorage.getItem("token");
    const headers = { headers: { Authorization: `Bearer ${token}` } };
    await axios
      .post("/asset", data, headers)
      .then(function (response) {
        dispatch({
          type: 'GET_MESSAGE',
          payload: {
              sort_at: 'sortByCreateAsset'
          },
      })
        history.push("/manage-asset");
      })
      .catch(function (error) {
        console.log("error", error);
      });
  };

  const CustomToggle = React.forwardRef(({ children, onClick }, ref) => (
    <Form.Control
    readOnly
      value={category}
      ref={ref}
      onClick={(e) => {
        e.preventDefault();
        onClick(e);
      }}
    />
  ));

  const CustomMenu = React.forwardRef(
    ({ children, style, className, "aria-labelledby": labeledBy }, ref) => {
      const [newCategoryName, setNewCategoryName] = React.useState("");
      const [newCategoryId, setNewCategoryId] = React.useState("");
      const [add, setAdd] = React.useState(false);
      const [categoryIdError, setCategoryIdError] = React.useState({
        error: false,
        message: "",
      });
      const [categoryNameError, setCategoryNameError] = React.useState({
        error: false,
        message: "",
      });

      React.useEffect(() => {
        let checkId = categoryList.find((o) => o.id === newCategoryId);
        if (checkId !== undefined) {
          setCategoryIdError({
            error: true,
            message:
              "Prefix is already existed. Please enter a different prefix",
          });
        } else {
          setCategoryIdError({ error: false, message: "" });
        }
        let checkName = categoryList.find((o) => o.name === newCategoryName);
        if (checkName !== undefined) {
          setCategoryNameError({
            error: true,
            message:
              "Category is already existed. Please enter a different category",
          });
        } else {
          setCategoryNameError({ error: false, message: "" });
        }
      }, [newCategoryId, newCategoryName]);

      const handleSubmitCategory = async () => {
        const data = { id: newCategoryId, name: newCategoryName };
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        await axios.post("/category", data, headers).then((response) => {
          getCategory();
        });
      };
      return (
        <div
          ref={ref}
          style={style}
          className={className}
          aria-labelledby={labeledBy}
        >
          <ul className="list-unstyled">
            {React.Children.toArray(children).filter(
              (child) => child.props.children
            )}
          </ul>
          <hr></hr>

          {!add ? (
            <a
              className="mx-3 my-2 w-auto text-danger"
              type="button"
              onClick={() => setAdd(true)}
            >
              Add new category
            </a>
          ) : (
            <Form className="mx-3 my-2 w-auto">
              <Row>
                <Col>
                  <Form.Control
                    isInvalid={categoryNameError.error}
                    required
                    placeholder=""
                    value={newCategoryName}
                    onChange={(e) => {
                      setNewCategoryName(e.target.value);
                    }}
                  ></Form.Control>
                  <Form.Control.Feedback type="invalid">
                    {categoryNameError.message}
                  </Form.Control.Feedback>
                </Col>
                <Col md={4}>
                  <Form.Control
                    isInvalid={categoryIdError.error}
                    maxLength="2"
                    placeholder=""
                    value={newCategoryId}
                    onChange={(e) => {
                      setNewCategoryId(e.target.value);
                    }}
                    required
                  />
                  <Form.Control.Feedback type="invalid">
                    {categoryIdError.message}
                  </Form.Control.Feedback>
                </Col>
                <Col sm={1}>
                  <TiTick
                    className="h4 text-danger"
                    type="button"
                    onClick={handleSubmitCategory}
                  />
                </Col>
                <Col sm={2}>
                  <TiTimes
                    className="h4"
                    type="button"
                    onClick={() => setAdd(false)}
                  />
                </Col>
              </Row>
            </Form>
          )}
        </div>
      );
    }
  );
  return (
    <>
      <Container id="containerFormCreate">
        <h4>
          <b>Create New Asset</b>
        </h4>
        <br></br>
        <Form onSubmit={(e) => handleSubmit(e)}>
          <Form.Group controlId="formBasicText">
            <Row>
              <Col>
                <Form.Label>Name</Form.Label>
              </Col>
              <Col md={8}>
                <Form.Control
                  required
                  type="text"
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                />
              </Col>
            </Row>
          </Form.Group>

          <br></br>
          <Form.Group controlId="formBasicSelect">
            <Row>
              <Col>
                <Form.Label>Category</Form.Label>
              </Col>
              <Col md={8}>
                {/* {categoryList.map((item, index) => (
                      <Dropdown.Item key={index} value={item.id} onClick={() => setCategory(item.name)}>{item.name} */}
                <Dropdown>
                  <Dropdown.Toggle
                    as={CustomToggle}
                    id="dropdown-custom-components"
                  >
                    Custom toggle
                  </Dropdown.Toggle>

                  <Dropdown.Menu as={CustomMenu}>
                    {categoryList.map((item, index) => (
                      <Dropdown.Item
                        md={4}
                        key={index}
                        value={item.id}
                        onClick={() => {
                          setCategory(item.name);
                          setCategoryId(item.id);
                        }}
                      >
                        {item.name}
                      </Dropdown.Item>
                    ))}
                  </Dropdown.Menu>
                </Dropdown>
              </Col>
            </Row>
          </Form.Group>
          <br />
          <Form.Group controlId="formBasicTextArea">
            <Row>
              <Col>
                <Form.Label>Specification</Form.Label>
              </Col>
              <Col md={8}>
                <Form.Control
                  required
                  as="textarea"
                  rows={3}
                  value={specification}
                  onChange={(e) => setSpecification(e.target.value)}
                />
              </Col>
            </Row>
          </Form.Group>
          <br></br>
          <Form.Group controlId="formBasicDate">
            <Row>
              <Col>
                <Form.Label>Installed Date</Form.Label>
              </Col>
              <Col md={8}>
                <Form.Control
                  required
                  type="date"
                  onChange={(e) => setInstalledDate(e.target.value)}
                />
              </Col>
            </Row>
          </Form.Group>

          <br></br>

          <Form.Group controlId="formState">
            <Row>
              <Col>
                <Form.Label>State</Form.Label>
              </Col>
              <Col md={4}>
                <Form.Check
                  id="1"
                  name="state"
                  type="radio"
                  value={1}
                  onChange={(e) => setState(e.target.value)}
                  label="Available"
                />
              </Col>
              <Col md={4}>
                <Form.Check
                  id="0"
                  name="state"
                  type="radio"
                  value={0}
                  onChange={(e) => setState(e.target.value)}
                  label="Not available"
                />
              </Col>
            </Row>
          </Form.Group>
          <br></br>
          <Form.Group className="text-end">
            <p className="err-msg">{mess}</p>
            <Button
              className="me-3"
              variant="danger"
              type="submit"
              disabled={enabled}
            >
              Save
            </Button>
            <Button
              variant="outline-secondary"
              onClick={() => history.push("/manage-asset")}
            >
              Cancel
            </Button>
          </Form.Group>
        </Form>
      </Container>
    </>
  );
};

export default CreateNewAsset;
