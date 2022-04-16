import React from "react";
import { Link, Route, Switch } from "react-router-dom";

import Form from "../../components/forms/CommonForm";
import Table from "../../components/TransactionTable";
import NoMatch from "../errors/404";

import FormFields from "./FormFields";

class Schedule extends React.Component
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
                            <h1>Shifts</h1>
                        </div>
                        <div className="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb float-md-end">
                                    <li className="breadcrumb-item">
                                        <Link to="/manager/dashboard">
                                            Dashboard
                                        </Link>
                                    </li>
                                    <li className="breadcrumb-item active" aria-current="page">Shifts</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <section className="content">
                <div className="container-fluid">
                    <Switch>
                        {/* <Route exact path="/manager/schedule/create">
                            <Form
                                model="schedules"
                                fields={ FormFields() }
                                currentText="Create a transaction" />
                        </Route> */}
                        {/* <Route exact path="/manager/schedule/edit/:id">
                            <Form
                                model="schedules"
                                fields={ FormFields() }
                                currentText="Edit the transaction" />
                        </Route> */}
                        <Route exact path="/manager/schedule">
                            <Table model="schedule" />
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

export default Schedule;
