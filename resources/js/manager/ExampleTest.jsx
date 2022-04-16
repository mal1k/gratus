import React from "react";
import { withRouter } from "react-router-dom";
import PropTypes from "prop-types";

class ExampleTest extends React.Component
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
            message: "none",
        };
    }

    componentDidMount()
    {
        if ( this.props.match.params.userId ) {
            this.setState({
                message: "> the user ID was got and the value is: " + this.props.match.params.userId
            });
        } else {
            this.setState({
                message: "nothing was changed"
            });
        }
    }

    render()
    {
        let { message } = this.state;

        if ( this.props.name ) {
            return <h1>Hello, {this.props.name}!</h1>;
        } else {
            return <h1>Hey, stranger { message }</h1>;
        }
    }
}

export default withRouter( ExampleTest );