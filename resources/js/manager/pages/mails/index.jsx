import React from "react";
import { Link, Route, Switch } from "react-router-dom";

import Form from "../../components/forms/CommonForm";
import Table from "../../components/Table";
import NoMatch from "../errors/404";

import FormFields from "./FormFields";

class Mails extends React.Component
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
                            <h1>Mails</h1>
                        </div>
                        <div className="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb float-md-end">
                                    <li className="breadcrumb-item">
                                        <Link to="/manager/dashboard">
                                            Dashboard
                                        </Link>
                                    </li>
                                    <li className="breadcrumb-item active" aria-current="page">Mails</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <section className="content">
                <div className="container-fluid">
                    <Switch>
                        <Route exact path="/manager/mails/create">
                            <Form
                                model="mails"
                                fields={ FormFields() }
                                currentText="Create an user" />
                        </Route>
                        <Route exact path="/manager/mails/edit/:id">
                            <Form
                                model="mails"
                                fields={ FormFields() }
                                currentText="Edit the user" />
                        </Route>
                        <Route exact path="/manager/mails">
                            <Table model="mails" />
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

export default Mails;
