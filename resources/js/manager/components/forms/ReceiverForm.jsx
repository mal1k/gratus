import React from "react";
import { Link, withRouter } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import PropTypes from "prop-types";

import Spinner from "../Spinner";

class Form extends React.Component
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
            errors: null,
            item: {},
            isCreatePage: false,
            isRendered: false,
            successSubmitMessage: null,
            serverErrorMessage: null,
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleUnauthorized = this.handleUnauthorized.bind(this);
        this.prepareItem = this.prepareItem.bind(this);

        this.invalidInputCssClass = "is-invalid";
        this.submitButtonRef = React.createRef();
        this.submitTextRef = React.createRef();
        this.submitSpinnerRef = React.createRef();

        const token = this.getCookie("atoken");
        this.baseFetchUrl = "/api/manager/";
        this.fetchParams = {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token,
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": document.querySelector("meta[name=csrf-token]").content
            }
        };
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
            serverErrorMessage: "You became to be unauthorized. Please log in again"
        });
    }

    componentDidMount()
    {
        if ( this.props.match.params.id ) {

            let url = this.baseFetchUrl + this.props.model + "/" + this.props.match.params.id;
    
            fetch( url, this.fetchParams )
                .then(response => {
                    if ( response.status === 401 ) {

                        this.handleUnauthorized();

                    }

                    return response.json();
                })
                .then(data => {
                    console.dir(data);
                    this.setState({
                        item: this.prepareItem( data ),
                        isRendered: true
                    });
                })
                .catch(error => console.error("Pipe all hands on deck! We've got an error with the response", error));

        } else if ( this.props.match.url ) {

            let match = this.props.match.url.match( new RegExp("\/create$", "i") ) || [];
            if ( match.length > 0 ) {
                this.setState({
                    item: this.prepareItem( null ),
                    isCreatePage: true,
                    isRendered: true,
                });
            }

        } else {
            throw new Error("Something went wrong related to the URL!");
        }
    }

    prepareItem( item )
    {
        let $item = {};

        for ( let prop in this.props.fields ) {
            if ( this.props.fields.hasOwnProperty( prop ) ) {
                if ( Array.isArray( this.props.fields[ prop ] ) ) {
                    this.props.fields[ prop ].map((value, index) => {
                        if ( index === 0 ) return;
                        $item[ value.inputElement.name ] = value.value;
                    });
                }
            }
        }

        if ( item !== null ) {
            $item = this.loadDataInItem( $item, item );
        }

        return $item;
    }

    loadDataInItem( mock, item )
    {
        let bankAccount = item.bank_account;
        let creditCard = item.credit_card;
        let receiverData = item.receiver_data;
        delete item.bank_account;
        delete item.credit_card;
        delete item.receiver_data;

        for ( let prop in item ) {
            if ( item.hasOwnProperty( prop ) ) {
                let newProp = "receiver[" + prop + "]";
                mock[ newProp ] = item[ prop ];
            }
        }

        for ( let prop in bankAccount ) {
            if ( bankAccount.hasOwnProperty( prop ) ) {
                let newProp = "bank_account[" + prop + "]";
                mock[ newProp ] = bankAccount[ prop ];
            }
        }

        for ( let prop in creditCard ) {
            if ( creditCard.hasOwnProperty( prop ) ) {
                let newProp = "credit_card[" + prop + "]";
                mock[ newProp ] = creditCard[ prop ];
            }
        }

        for ( let prop in receiverData ) {
            if ( receiverData.hasOwnProperty( prop ) ) {
                let newProp = "receiver_data[" + prop + "]";
                mock[ newProp ] = receiverData[ prop ];
            }
        }

        return mock;
    }

    handleSubmit( e )
    {
        e.preventDefault();
        this.setState({errors: null});

        let submitButton = this.submitButtonRef.current;
        let submitText = this.submitTextRef.current;
        let submitSpinner = this.submitSpinnerRef.current;

        submitButton.disabled = true;
        submitText.classList.toggle("d-none");
        submitSpinner.classList.toggle("d-none");

        let url,
            params = Object.assign({}, this.fetchParams);

        if ( this.state.isCreatePage ) {
            params.method = "POST";
            url = this.baseFetchUrl + this.props.model;
        } else {
            params.method = "PATCH";
            url = this.baseFetchUrl + this.props.model + "/" + this.props.match.params.id;
        }

        params.body = JSON.stringify(this.state.item);

        fetch( url, params )
            .then(response => {
                if ( response.status == 401 ) {

                    this.handleUnauthorized();
                    return;

                }

                return response.json();
            })
            .then(data => {
                submitButton.disabled = false;
                submitText.classList.toggle("d-none");
                submitSpinner.classList.toggle("d-none");

                if (data.exception) {
                    this.sendToClientTheServerException();
                }

                if ( data.errors ) {
                    this.setState({errors: data.errors});
                } else if ( data.success ) {
                    this.setState({successSubmitMessage: data.success});
                    setTimeout(() => {
                        this.setState({successSubmitMessage: null});
                    }, 5000);
                } else if ( data.serverError ) {
                    this.sendToClientTheServerException( data );
                    setTimeout(() => {
                        this.setState({serverErrorMessage: null});
                    }, 5000);
                }
            })
            .catch(error => console.error("Pipe all hands on the deck! We've got an error with the submit.", error));
    }

    sendToClientTheServerException( data = null )
    {
        let message = data !== null ? data.serverError : "Oh, holy cow! We've caught some server error on the board!";

        this.setState({
            serverErrorMessage: "Suddenly, some server error was occurred"
        });
        throw new Error( message );
    }

    handleChange( e )
    {
        const target = e.target;
        const value = target.type === "checkbox" ? target.checked : target.value;
        const name = target.name;

        this.setState((prevState) => {
            let prevItem = prevState.item;
            prevItem[ name ] = value;
            return {
                item: prevItem,
                errors: null,
                serverErrorMessage: null,
                successSubmitMessage: null,
            };
        });
    }

    getLabelElement( object )
    {
        let $label = false;

        if ( object.labelElement ) {

            const { text, ...attributes } = object.labelElement;

            $label = (
                <label { ...attributes }>{ text }</label>
            );

        } else if ( object.spanElement ) {

            const { ...attributes } = object.spanElement;

            $label = (
                <span { ...attributes }>{ "ID: " + object.value }</span>
            );

        }

        return $label;
    }

    getAutocompleteForInput( inputType )
    {
        let autocomplete;

        if ( inputType === "password" ) {
            autocomplete = this.state.isCreatePage ?  "new-password" : "current-password";
        } else if ( inputType === "email" ) {
            autocomplete = "email";
        }

        return autocomplete !== null ? autocomplete : undefined;
    }

    getCurrentValidation( inputName )
    {
        let validationErrors = this.state.errors,
            element = {};

        if ( validationErrors !== null && validationErrors[ inputName ] ) {
            element.valElement = (
                <div className="invalid-feedback">
                    { validationErrors[ inputName ].join("; ") }
                </div>
            );
            element.valCssClass = this.invalidInputCssClass;
        } else {
            element = {
                valElement: null,
                valCssClass: ""
            };
        }

        return element;
    }

    getCurrentClassList( classList, validationClass )
    {
        let current = classList.search( this.invalidInputCssClass );

        if ( validationClass !== "" ) {
            if ( current == -1 ) {
                classList += " " + validationClass;
            } 
        } else {
            if ( current > -1 ) {
                classList = classList.replace( " " + this.invalidInputCssClass, "" );
            }
        }

        return classList;
    }

    getInputElement( object )
    {
        let $inputElement = false,
            $checkboxValElement = null;

        if ( object.inputElement ) {

            let { name } = object.inputElement;

            let { valElement, valCssClass } = this.getCurrentValidation( name );

            if ( object.inputElement.className ) {
                let classList = object.inputElement.className;
                object.inputElement.className = this.getCurrentClassList( classList, valCssClass );
            } 

            let autocomplete = this.getAutocompleteForInput( object.inputElement.type );

            if ( object.inputElement.type === "hidden" ) {
                $inputElement = (
                    <input { ...object.inputElement } value={ object.value } />
                );
            } else if (
                object.inputElement.type === "radio" || 
                object.inputElement.type === "checkbox"
            ) {
                $inputElement = (
                    <input
                        { ...object.inputElement } 
                        value={ object.value }
                        defaultChecked={ object.checked }
                        onChange={ this.handleChange } />
                );
                $checkboxValElement = valElement;
            } else {
                $inputElement = (
                    <>
                    <input
                        { ...object.inputElement }
                        autoComplete={ autocomplete }
                        defaultValue={ object.value }
                        onChange={ this.handleChange } />
                    { valElement }
                    </>
                );
            }
        }

        return [ $inputElement, $checkboxValElement ];
    }

    getLayoutOfElement( object, label, input, valElementOfCheckbox )
    {
        let { beginLayoutWith: begin } = object;

        if ( begin !== undefined && begin === "input" ) {

            return (
                <>{ input }{ label }{ valElementOfCheckbox }</>
            );
        }

        return (
            <>{ label }{ input }</>
        );
    }

    getHintElement( object )
    {
        let { hintElement } = object;

        if ( hintElement !== undefined ) {
            let { content, className } = hintElement;
            return (
                <small className={ className }>
                    { content }
                </small>
            );
        }
        return null;
    }

    getFormElements( fields )
    {
        let $output = [];

        if ( Object.keys( fields ).length > 0 ) {

            fields.map((value, index) => {

                // Set the value in the input from the state of the item
                if ( this.state.isCreatePage && index === 0 ) {
                    return;
                } else if (! this.state.isCreatePage ) {
                    for ( let prop in this.state.item )
                    {
                        if ( this.state.item.hasOwnProperty( prop ) ) {
                            let name = value.inputElement.name;
                            if ( name.match( new RegExp("\\[status\\]$") ) !== null ) {
                                if ( value.value === this.state.item[ name ] ) {
                                    value.checked = true;
                                    break;
                                }
                            } else if ( name.match( new RegExp("\\[is_kyc_passed\\]$") ) !== null ) {
                                value.checked = this.state.item[ name ];
                            } else if ( name === prop ) {
                                value.value = this.state.item[ prop ];
                                break;
                            }
                        }
                    }
                }

                let $label = this.getLabelElement( value );

                let [ $inputElement, $valCheckboxElement ] = this.getInputElement( value );

                let $elementLayout = this.getLayoutOfElement( value, $label, $inputElement, $valCheckboxElement );

                let $hintElement = this.getHintElement( value );

                if ( value.wrapperElement ) {
                    if ( typeof value.wrapperElement.title !== "undefined" ) {
                        let { titleClassName, title } = value.wrapperElement;
                        $output.push(
                            <div key={ "wrapper-title" + index } className={ titleClassName }>
                                { title }
                            </div>
                        );
                    }
                    $output.push(
                        <span key={ index }>
                            <div className={ value.wrapperElement.className }>
                                { $elementLayout }
                            </div>
                            { $hintElement }
                        </span>
                    );
                }
            });
        }

        return $output;
    }

    getForms()
    {
        let output = [];
        let containerClass = "col-lg-6 col-xxl-12";
        if ( typeof this.props.fields.receiver !== "undefined" ) {
            output.push(
                <div key="main" className={ containerClass }>
                    <div className="card">
                        <div className="card-body">
                            { this.getFormElements( this.props.fields.receiver ) }
                        </div>
                    </div>
                </div>
            );
        }
        if ( typeof this.props.fields.receiver_data !== "undefined" ) {
            output.push(
                <div key="other" className={ containerClass }>
                    <div className="card">
                        <div className="card-body">
                            { this.getFormElements( this.props.fields.receiver_data ) }
                        </div>
                    </div>
                </div>
            );
        }
        if ( typeof this.props.fields.credit_card !== "undefined" ) {
            output.push(
                <div key="credit_card" className={ containerClass }>
                    <div className="card">
                        <div className="card-header border-0 gray-card-header">
                            <div className="card-title">The credit card information</div>
                        </div>
                        <div className="card-body">
                            { this.getFormElements( this.props.fields.credit_card ) }
                        </div>
                    </div>
                </div>
            );
        }
        if ( typeof this.props.fields.bank_account !== "undefined" ) {
            output.push(
                <div key="bank_account" className={ containerClass }>
                    <div className="card">
                        <div className="card-header border-0 gray-card-header">
                            <div className="card-title">The bank account information</div>
                        </div>
                        <div className="card-body">
                            { this.getFormElements( this.props.fields.bank_account ) }
                        </div>
                    </div>
                </div>
            );
        }
        return output;
    }

    /*
     * The main method of the object
    */
    render()
    {
        const {
            isRendered, 
            successSubmitMessage, 
            serverErrorMessage
        } = this.state;
        const backLinkTo = "/manager/" + this.props.model;

        return (
            <form onSubmit={ this.handleSubmit }>
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
                                        icon={ ["fas", "chevron-left"] } 
                                        size="1x" />
                                    <span>
                                        Back to all
                                    </span>
                                </Link>
                                <span className="border-start ps-sm-2">
                                    { this.props.currentText }
                                </span>
                            </div>

                            <div>
                                <span className="d-block d-sm-inline pt-1 me-sm-3 text-success">
                                    { successSubmitMessage }
                                </span>
                                <span className="d-block d-sm-inline pt-1 me-sm-3 text-danger">
                                    { serverErrorMessage }
                                </span>

                                <button 
                                    ref={ this.submitButtonRef }
                                    type="submit"
                                    className="btn btn-primary mr-5"
                                >
                                    <span ref={ this.submitTextRef }>Submit</span>
                                    <span className="d-none" ref={ this.submitSpinnerRef }>
                                        <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {
                    isRendered ? 
                    
                    (
                        <div className="container">
                            <div className="row">
                                { this.getForms() }
                            </div>
                        </div>
                    )
                    
                    : <Spinner />
                }

            </form>
        );
    }
}

export default withRouter( Form );