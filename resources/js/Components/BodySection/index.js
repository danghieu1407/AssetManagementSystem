import React from "react";
import { Route, Switch } from "react-router-dom";
import Sidebar from "../Sidebar";
import "./style.scss";
import { ManageUser } from "../TableManageUser";
import CreateNewUser from "../CreateNewUser";
import Test from "../Test";
import ManageAsset from "../TableManageAsset";
import assetEditReducer from "../../Reducers/asset.reducer";
import userEditReducer from "../../Reducers/userEdit.reducer";
import { useSelector } from "react-redux";
import EditForm from "../ManageUser/EditUser";
import CreateNewAsset from "../CreateNewAsset";
import CreateNewAssignment from "../CreateNewAssignment";
import EditAssetForm from "../ManageAsset/EditAsset";
import EditAssignmentForm from "../ManageAssignment/EditAssignment";
import ManageAssignment from "../TableManageAssignment";
import TableHome from "../TableHome";
import TableReport from "../TableReport";
import TableReturning from "../TableReturning";

export default function BodySection() {
    const assetEditReducer = useSelector((state) => state.assetEditReducer.value);
    const userEditReducer = useSelector((state) => state.userEditReducer.value);
    return (
        <div className="body-section">
            <div className="sidebar col-lg-3 col-md-6 col-sm-12">
                <Sidebar />
            </div>
            <div className="body-content col-lg-9 col-md-6 col-sm-12">
                <Switch>
                    <Route exact path="/">
                    <TableHome/>
                    </Route>
                    <Route path="/home">
                    <TableHome/>
                    </Route>
                    <Route path="/HomePage">
                    <TableHome/>
                    </Route>
                    <Route path="/manage-user">
                        {userEditReducer ? <EditForm /> : <ManageUser />}
                    </Route>
                    <Route path="/manage-asset">
                        {assetEditReducer ? <EditAssetForm /> : <ManageAsset />}
                    </Route>
                    <Route path="/create-asset">
                        <CreateNewAsset />
                    </Route>
                    <Route path="/create-user">
                        <CreateNewUser />
                    </Route>
                    <Route path="/create-assignment">
                        <CreateNewAssignment />
                    </Route>
                    <Route path="/manage-assignment">
                        <ManageAssignment />
                    </Route>
                    <Route path="/edit-assignment">
                        <EditAssignmentForm />
                    </Route>
                    <Route path="/request-for-returning">
                        <TableReturning />
                    </Route>
                    <Route path="/report">
                        <TableReport />
                    </Route>
                </Switch>
            </div>

        </div>
    );
}
