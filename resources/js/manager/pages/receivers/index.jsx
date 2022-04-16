import React from "react";
import { Link, Route, Switch } from "react-router-dom";

import Form from "../../components/forms/ReceiverForm";
import Table from "../../components/Table";
import NoMatch from "../errors/404";
import OverviewPage from "./overview";

import FormFields from "./FormFields";

class ServiceProviders extends React.Component
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
                            <h1>Receivers</h1>
                        </div>
                        <div className="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb float-md-end">
                                    <li className="breadcrumb-item">
                                        <Link to="/manager/dashboard">
                                            Dashboard
                                        </Link>
                                    </li>
                                    <li className="breadcrumb-item active" aria-current="page">
                                        Receivers
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <section className="content">
                <div className="container-fluid">
                    <Switch>
                        <Route exact path="/manager/receivers/create">
                            <Form
                                model="receivers"
                                fields={ FormFields() }
                                currentText="The creation of a receiver" />
                        </Route>
                        <Route exact path="/manager/receivers/edit/:id">
                            <Form
                                model="receivers"
                                fields={ FormFields() }
                                currentText="Edit the receiver" />
                        </Route>
                        <Route exact path="/manager/receivers/overview/:id">
                            <OverviewPage />
                        </Route>
                        <Route exact path="/manager/receivers">
                            {/* <div>
                                <a href="/manager/tippers/overview/1" className="btn btn-danger">Tippers info</a>
                            </div> */}
                            <Table model="receivers" />
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

export default ServiceProviders;
