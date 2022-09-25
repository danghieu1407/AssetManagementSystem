import React from "react";
import { Container, Row, Col, Button, Form } from "react-bootstrap";
import axios from "../../Services/base.service";
import "./style.css";
import nashtechlogo from "../../../assets/nashtech_logo.svg";
// import { useHistory } from "react-router-dom";

const SignIn = () => {
  // let history = useHistory();
  const [username, setUsername] = React.useState("");
  const [password, setPassword] = React.useState("");
  const [mess, setMess] = React.useState("");
  React.useEffect(() => {
    console.log("SignIn");
  }, []);

  const checkForm = () => {
    if (username === "" || password === "") {
      // setMess("Please enter username and password");
      return true;
    }
    return false;
  }

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const data = {
        username: username,
        password: password,
      };

      const response = await axios.post("/login", data);

      console.log(response.data.message);
      localStorage.setItem("token", response.data.token);


      window.location.href = "/home";
    } catch (err) {
      console.log(err.response.data.message);
      setMess(err.response.data.message);
    }
  };

  return (
    <>
      <Container>
        <Row>

          <Col xs={12} md={2}></Col>
          <Col xs={12} md={8}>
            <div className="bg"></div>
            <div className="bg bg2"></div>
            <div className="bg bg3"></div>
            <div className="content">
                <div className="logo">
                    <img className="img-login" src={nashtechlogo} alt="nashtech" width="100" height="100"/>
                </div>
              <Form onSubmit={handleLogin}>
                <Form.Group className="mb-3" controlId="formBasic">
                  <Form.Label>Username</Form.Label>
                  <Form.Control
                    type="text"
                    placeholder="Enter username"
                    value={username}
                    onChange={(e) => setUsername(e.target.value)}
                  />
                </Form.Group>

                <Form.Group className="mb-3" controlId="formBasicPassword">
                  <Form.Label>Password</Form.Label>
                  <Form.Control
                    type="password"
                    placeholder="Enter password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                  />
                </Form.Group>
                <Form.Group className="mb-3" controlId="formBasicCheckbox" hidden={true}>
                  <Form.Check type="checkbox" label="Check me out" />
                </Form.Group>
                <p className="err-msg">{mess} </p>

                <Button className="btn-submit" disabled={checkForm()} type="submit">
                  Login
                </Button>
              </Form>
            </div>
          </Col>
          <Col xs={12} md={2}></Col>
        </Row>
      </Container>
    </>
  );
};

export default SignIn;
