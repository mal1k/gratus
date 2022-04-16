import React from "react";
import { Link, Route, Switch } from "react-router-dom";

import Form from "../../components/forms/CommonForm";
import OverviewForm from "../../components/forms/OverviewCommonForm";
import Table from "../../components/Table";
import NoMatch from "../errors/404";

import FormFields from "./FormFields";
import EditFormFields from "./EditFormFields";
import OverviewFormFields from "./OverviewFormFields";

class Customers extends React.Component
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
                            <h1>Tippers</h1>
                        </div>
                        <div className="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb float-md-end">
                                    <li className="breadcrumb-item">
                                        <Link to="/manager/dashboard">
                                            Dashboard
                                        </Link>
                                    </li>
                                    <li className="breadcrumb-item active" aria-current="page">Tippers</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <section className="content">
                <div className="container-fluid">
                    <Switch>
                        <Route exact path="/manager/tippers/create">
                            <Form
                                model="tippers"
                                fields={ FormFields() }
                                currentText="Create a tipper" />
                        </Route>
                        <Route exact path="/manager/tippers/edit/:id">
                            <Form
                                model="tippers"
                                fields={ EditFormFields() }
                                currentText="Edit the tipper" />
                        </Route>
                        <Route exact path="/manager/tippers">
                            <Table model="tippers" />
                        </Route>
                        <Route exact path="/manager/tippers/overview/:id">
                            <OverviewForm
                                model="tippers"
                                fields={ OverviewFormFields() }
                                currentText="Overview for tipper" />
                        </Route>
                        <Route exact path="/manager/tippers/receiver_customers">
                            Tips info per shift pre receiver pre customer
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

export default Customers;
