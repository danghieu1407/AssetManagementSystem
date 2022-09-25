import React from "react";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import Home from "./Home";
import SignIn from "./SignIn";
import TableManageUser from "../Components/TableManageUser/index";
import Test from "../Components/Test/index";

export default function App() {
    return (
        <Router>
            {localStorage.getItem("token") ? (
                <Switch>
                    <Route path="/">
                        <Home />
                    </Route>
                </Switch>
            ) : (
                <Switch>
                    <Route path="/">
                        <SignIn />
                    </Route>
                </Switch>
            )}
        </Router>
    );
}
