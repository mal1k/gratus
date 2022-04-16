import React from "react";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

class Navbar extends React.Component 
{
    constructor(props)
    {
        super(props);

        this.state = {
            isToggleOn: true,
            windowWidth: window.innerWidth
        };

        this.handleClick = this.handleClick.bind(this);
        this.handleResize = this.handleResize.bind(this);
        this.handleLogout = this.handleLogout.bind(this);
    }

    handleClick()
    {
        this.setState((prevState) => ({
            isToggleOn: !prevState.isToggleOn
        }));
    }

    handleResize()
    {
        this.setState( {isToggleOn: !(window.innerWidth <= 992)} );
        this.setState( {windowWidth: window.innerWidth} );
    }

    componentDidMount()
    {
        this.setState( {isToggleOn: !(window.innerWidth <= 992)} );
        window.addEventListener("resize", this.handleResize);
    }

    componentWillUnmount()
    {
        window.removeEventListener("resize", this.handleResize);
    }

    toggleSidebar() 
    {
        let body = document.querySelector("body");
        let $classList = body.classList;

        if ( this.state.windowWidth <= 992 ) {
            if ( this.state.isToggleOn ) {
                $classList.add("sidebar-open");
                $classList.add("sidebar-is-opening");
                $classList.remove("sidebar-collapse", "sidebar-closed");
                setTimeout(() => {
                    $classList.remove("sidebar-is-opening");
                }, 50);
            } else {
                $classList.remove("sidebar-open");
                $classList.add("sidebar-closed");
                $classList.add("sidebar-collapse");
            }
        } else {
            if ( this.state.isToggleOn ) {
                $classList.add("sidebar-is-opening");
                $classList.remove("sidebar-collapse", "sidebar-closed");
                setTimeout(() => {
                    $classList.remove("sidebar-is-opening");
                }, 50);
            } else {
                $classList.add("sidebar-collapse");
            }
        }
    }

    handleLogout(e)
    {
        e.preventDefault();

        let fetchParams = {
            method: "POST",
            headers: {
                "X-CSRF-Token": document.querySelector("meta[name=csrf-token]").content
            },
        };

        fetch("/manager/logout", fetchParams)
            .then(response => {
                if (response.redirected) {
                    window.location.assign(response.url);
                } else {
                    console.error("Something is wrong here!");
                    console.dir(response);
                }
            })
            .catch(error => console.error("There was an error!", error));
    }

    render() 
    {
        return (
            <>
            <nav className="main-header navbar navbar-expand navbar-light bg-white">
                <div className="container-fluid">
                    <ul className="navbar-nav">
                        <li className="nav-item">
                            <a 
                                className="nav-link"
                                role="button"
                                onClick={ this.handleClick }
                            >
                                <FontAwesomeIcon 
                                    icon={ ["fas", "bars"] } 
                                    size="1x" />
                                { this.toggleSidebar() }
                            </a>
                        </li>
                        <li className="nav-item">
                            <a href="/" className="nav-link d-none d-md-block" target="_blank">
                                The website home
                            </a>
                            <a href="/" className="nav-link d-md-none" target="_blank">
                                Website
                            </a>
                        </li>
                        <li className="nav-item">
                            <a href="/telescope" className="nav-link d-none d-md-block" target="_blank">
                                Laravel Telescope
                            </a>
                            <a href="/telescope" className="nav-link d-md-none" target="_blank">
                                Telescope
                            </a>
                        </li>
                    </ul>
                    <ul className="navbar-nav ml-auto">
                        <li className="nav-item">
                            <a 
                                onClick={ this.handleLogout }
                                className="nav-link btn btn-sm btn-outline-light"
                            >
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div id="sidebar-overlay" onClick={ this.handleClick }></div>
            </>
        );
    }
}

export default Navbar;