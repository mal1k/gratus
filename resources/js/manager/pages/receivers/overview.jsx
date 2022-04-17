import React from "react";
import { Link, withRouter } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import PropTypes from "prop-types";

import Spinner from "../../components/Spinner";

class Overview extends React.Component
{
    static propTypes = {
        match: PropTypes.object.isRequired,
        location: PropTypes.object.isRequired,
        history: PropTypes.object.isRequired
    };

    constructor( props )
    {
        super( props );
        this.state = {
            item: {},
            isRendered: false,
            serverErrorMessage: null,
        };

        this.handleUnauthorized = this.handleUnauthorized.bind(this);
    }

    componentWillUnmount()
    {
        this.setState({
            item: {},
        });
    }

    getCookie( name )
    {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    handleUnauthorized()
    {
        this.setState({
            serverErrorMessage: "You became to be unauthorized. <br /> Please log in again"
        });
    }

    componentDidMount()
    {
        if ( this.props.match.params.id ) {
            const token = this.getCookie("atoken");
            let url = "/api/manager/receivers/" + this.props.match.params.id,
                params = {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": "Bearer " + token,
                        "X-Requested-With": "XMLHttpRequest",
                    }
                };

            fetch( url, params )
                .then(response => {
                    if ( response.status === 401 ) {

                        this.handleUnauthorized();

                    }

                    return response.json();
                })
                .then(data => {
                    this.setState({
                        item: data,
                        isRendered: true
                    });
                })
                .catch(error => console.error("Pipe all hands on deck! We've got an error with the response", error));
        }
    }

    render() {
        const {
            item,
            isRendered,
            serverErrorMessage
        } = this.state;
        const backLinkTo = "/manager/receivers";
        let headerText = "The receiver #" + this.props.match.params.id;

        const transactions = item.transactions ?
            item.transactions.map((transaction) => {
                const transactionDate = new Date(transaction.created_at);

                const day = transactionDate.getDate(),
                    transactionDay = (day < 10) ? `0${day}` : day;

                const month = transactionDate.getMonth() + 1,
                    transactionMonth = (month < 10) ? `0${month}` : month;

                const year = transactionDate.getFullYear();
                const hours = transactionDate.getHours(),
                    transactionHours = (hours < 10) ? `0${hours}` : hours;

                const minutes = transactionDate.getMinutes(),
                    transactionMinutes = (minutes < 10) ? `0${minutes}` : minutes;

                const dateFormat = `${transactionDay}/${transactionMonth}/${year} ${transactionHours}:${transactionMinutes}`;
                return (
                    <tr key={transaction.transaction_id}>
                        <th scope="row">{ transaction.transaction_id }</th>
                        <td>{ `${transaction.first_name} ${transaction.last_name}` }</td>
                        <td>{ transaction.org_name }</td>
                        <td>{ transaction.status }</td>
                        <td>{ transaction.amount }</td>
                        <td>{ transaction.stars !== null ? transaction.stars : "" }</td>
                        <td>{ transaction.comment !== null ? transaction.comment : "" }</td>
                        <td>{ dateFormat }</td>
                    </tr>
                )
        }) : new Array();

        let content = (
            <>
            <div className="card card-widget widget-user shadow">
                <div className="widget-user-header bg-sidebar-like little">
                    <div className="widget-user-username">
                        { item.first_name + " " + item.last_name }
                    </div>
                </div>
                <div className="widget-user-image">
                    <img className="img-circle elevation-2" src="/storage/img/mock-user-128x128.jpg" alt="User Avatar" />
                </div>
                <div className="card-footer">
                    <div className="d-flex justify-content-center">
                        <div className="description-block">
                            <h5 className="description-header">$0</h5>
                            <span className="description-text">Tips</span>
                        </div>
                    </div>
                </div>
            </div>
            <div className="bg-primary ps-2 py-2">Transactions</div>
            <div className="table-responsive-xl">
                <table className="table table-primary table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tipper</th>
                            <th scope="col">Organization</th>
                            <th scope="col">Status</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Stars</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Date and time</th>
                        </tr>
                    </thead>
                    <tbody>
                        {transactions}
                    </tbody>
                </table>
            </div>
            </>
        );

        return (
            <>
            <div className="card">
                <div className="card-header card-header-with-padding">
                    <div className="d-flex justify-content-between">
                        <div>
                            <Link
                                to={ backLinkTo }
                                className="btn btn-link"
                                data-object="expandable"
                            >
                                <FontAwesomeIcon
                                    icon={ ["fa", "chevron-left"] }
                                    size="1x" />
                                <span>
                                    Back to all
                                </span>
                            </Link>
                            <span className="ps-sm-2">
                                { headerText }
                            </span>
                        </div>

                        <div>
                            <span className="d-block pt-1 text-danger text-nowrap">
                                { serverErrorMessage }
                            </span>
                            {/* <a href="/manager/organizations/providers/overview/1" class="btn btn-warning m-1">Service providers info</a> */}
                            <a href="/manager/receivers" className="btn btn-danger m-1">View shift</a>
                        </div>

                    </div>
                </div>
            </div>
            {
                isRendered ? content : <Spinner />
            }
            </>
        );
    }
}

export default withRouter( Overview );
