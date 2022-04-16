import React from "react";
import { Link, Route, Switch } from "react-router-dom";

import Form from "../../components/forms/CommonForm";
import OverText from "../../components/forms/OverviewForm";
import Table from "../../components/Table";
import NoMatch from "../errors/404";

import FormFields from "./FormFields";
import OverviewFields from "./Overview";

class PushNotification extends React.Component
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
                            <h1>Push Notifications</h1>
                        </div>
                        <div className="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb float-md-end">
                                    <li className="breadcrumb-item">
                                        <Link to="/manager/dashboard">
                                            Dashboard
                                        </Link>
                                    </li>
                                    <li className="breadcrumb-item active" aria-current="page">Push Notifications</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
            <section className="content">
                <div className="container-fluid">
                    <Switch>
                        <Route exact path="/manager/push-notifications/create">
                            <Form
                                model="push-notifications"
                                url="create"
                                fields={ FormFields() }
                                currentText="Create a push notification" />
                        </Route>
                        <Route exact path="/manager/push-notifications/edit/:id">
                            <Form
                                model="push-notifications"
                                url="update"
                                fields={ FormFields() }
                                currentText="Edit the push notification" />
                        </Route>
                        <Route exact path="/manager/push-notifications/overview/:id">
                            <OverText
                                model="push-notifications"
                                fields={ OverviewFields() }
                            />
                        </Route>
                        <Route exact path="/manager/push-notifications">
                            <Table model="push-notifications" />
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

export default PushNotification;
