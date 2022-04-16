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

    constructor(props)
    {
        super(props);
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
        if ( item !== null ) {
            item.password = "";
            return item;
        } else {
            let $item = {};

            this.props.fields.map((value, index) => {
                if ( index === 0 ) return;
                $item[ value.inputElement.name ] = value.value;
            });

            return $item;
        }
    }

    handleSubmit( e )
    {
        e.preventDefault();

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
                if ( response.status === 401 ) {

                    this.handleUnauthorized();

                }

                return response.json();
            })
            .then(data => {

                console.log('this is a response');
                console.dir(data);

                submitButton.disabled = false;
                submitText.classList.toggle("d-none");
                submitSpinner.classList.toggle("d-none");

                if (data.exception) {
                    this.setState({
                        serverErrorMessage: "Suddenly, some server error was occurred"
                    });
                    throw new Error("Oh, holy cow! We've caught some server error on the board!");
                }

                if ( data.errors ) {
                    this.setState({errors: data.errors});
                } else if ( data.success ) {
                    this.setState({successSubmitMessage: data.success});
                }
            })
            .catch(error => console.error("Pipe all hands on the deck! We've got an error with the submit.", error));
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
                object.inputElement.type === "checkbox" ||
                object.inputElement.type === "radio"
            ) {
                $inputElement = (
                    <input
                        { ...object.inputElement }
                        defaultChecked={ object.value === "Active" }
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
        let { beginLayoutWith } = object;

        if ( beginLayoutWith !== undefined && beginLayoutWith === "input" ) {
            return (
                <>{ input }{ label }{ valElementOfCheckbox }</>
            );
        }

        return (
            <>{ label }{ input }</>
        );
    }

    getFormElements()
    {
        let $output = [];

        if ( Object.keys( this.props.fields ).length > 0 ) {

            this.props.fields.map((value, index) => {

                if ( this.state.isCreatePage && index === 0 ) {
                    return;
                } else if (! this.state.isCreatePage ) {
                    for ( let prop in this.state.item )
                    {
                        if ( this.state.item.hasOwnProperty( prop ) ) {
                            if ( value.inputElement.name === prop ) {
                                value.value = this.state.item[ prop ];
                                break;
                            }
                        }
                    }
                }

                let $label = this.getLabelElement( value );

                let [ $inputElement, $valCheckboxElement ] = this.getInputElement( value );

                let $elementLayout = this.getLayoutOfElement( value, $label, $inputElement, $valCheckboxElement );

                if ( value.wrapperElement ) {
                    $output.push(
                        <div key={ index } { ...value.wrapperElement }>
                            { $elementLayout }
                        </div>
                    );
                }
            });
        }

        return $output;
    }

    renderForm()
    {
        let { successSubmitMessage, serverErrorMessage } = this.state;

        return (
            <form onSubmit={ this.handleSubmit }>

                { this.getFormElements() }

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

                <span className="d-block d-sm-inline pt-1 ms-sm-3 text-success">
                    { successSubmitMessage }
                </span>
                <span className="d-block d-sm-inline pt-1 ms-sm-3 text-danger">
                    { serverErrorMessage }
                </span>

            </form>
        );
    }

    /*
     * The main method of the object
    */
    render()
    {
        const { isRendered } = this.state;
        const linkTo = "/manager/" + this.props.model;

        return (
            <div className="card">
                <div className="card-header card-header-with-padding">
                    <Link
                        to={ linkTo }
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
                    <span className="border-start ps-sm-2">{ this.props.currentText }</span>
                </div>
                <div className="card-body">

                    { isRendered ? this.renderForm() : <Spinner /> }

                </div>
            </div>
        );
    }
}

export default withRouter( Form );
