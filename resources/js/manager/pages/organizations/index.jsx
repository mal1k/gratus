import React from "react";
import { Link, Route, Switch } from "react-router-dom";

import Form from "../../components/forms/OrgForm";
import NoMatch from "../errors/404";
import Table from "../../components/TableOrg";
import TableWithoutBtn from "../../components/TransactionTable";

import FormFields from "./FormFields";

class Organizations extends React.Component
{
    /*
     * The main method of the object
    */
    render()
    {
        return (
            <>
            <section className="content-header">
                <div className="container-fluid">
                    <div className="row mb-2">
                        <div className="col-sm-6">
                            <h1>Organizations</h1>
                        </div>
                        <div className="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb float-md-end">
                                    <li className="breadcrumb-item">
                                        <Link to="/manager/dashboard">
                                            Dashboard
                                        </Link>
                                    </li>
                                    <li className="breadcrumb-item active" aria-current="page">Organizations</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <section className="content">
                <div className="container-fluid">
                    <Switch>
                        <Route exact path="/manager/organizations/create">
                            <Form
                                model="organizations"
                                fields={ FormFields() }
                                currentText="Create a organization" />
                        </Route>
                        <Route exact path="/manager/organizations/edit/:id">
                            <Form
                                model="organizations"
                                fields={ FormFields() }
                                currentText="Edit the organization" />
                        </Route>
                        <Route exact path="/manager/organizations/overview/:id">
                            <Form
                                model="organizations"
                                fields={ FormFields() }
                                currentText="Organization preview" />
                        </Route>
                        <Route exact path="/manager/organizations/receiversInfo/:id">
                            <h1>Receivers list</h1>
                            <div>
                                <a href="/manager/organizations/providers/overview/1" class="btn btn-danger">Service providers info</a>
                            </div>
                            {/* <Table model="organizations2" /> */}
                        </Route>
                        <Route exact path="/manager/organizations/customersInfo/:id">
                            <h1>Customers list</h1>
                            <div>
                                <a href="/manager/tippers/overview/1" class="btn btn-danger">Tips info</a>
                            </div>
                            {/* <Table model="organizations2" /> */}
                        </Route>

                        <Route exact path="/manager/organizations/providers/schedule">
                            <h1>Shifts</h1>
                            <div>
                                {/* change /1 to /:id */}
                                <a href="/manager/receivers" class="btn btn-danger">Receivers per shift</a>
                            </div>
                        </Route>
                        <Route exact path="/manager/organizations/providers/overview/:id">
                            <h1>Service providers info</h1>
                            <div>
                                {/* change /1 to /:id */}
                                <a href="/manager/receivers/overview/1" class="btn btn-danger">Receiver</a>
                            </div>
                        </Route>
                        <Route exact path="/manager/organizations/providers">
                            Service providers per Groups
                            <div class="m-2">
                                <a href="/manager/organizations/providers/overview/1" class="btn btn-danger">Service providers info</a>
                            </div>
                            <div class="m-2">
                                <a href="/manager/organizations/providers/schedule" class="btn btn-danger">Shifts</a>
                            </div>
                        </Route>

                        <Route exact path="/manager/organizations">
                            <Table model="organizations" />
                        </Route>
                        <Route path="*">
                            <NoMatch />
                        </Route>
                    </Switch>
                </div>
            </section>
            </>
        );
    }
}

export default Organizations;
