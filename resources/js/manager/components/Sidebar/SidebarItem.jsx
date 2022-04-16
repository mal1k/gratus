import React from "react";
import { Link } from "react-router-dom";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

class SidebarItem extends React.Component
{
    getBadge() {
        if ( this.props.current.badge instanceof Object ) {
            return (
                <span className={ this.props.current.badge.badgeClass }>
                    New
                </span>
            );
        }
    }

    getItemContent() {
        return (
            <>
                <FontAwesomeIcon 
                    className={ this.props.current.iconClass }
                    icon={ this.props.current.icon } 
                    size="1x" />
                <p>
                    { this.props.current.title }
                    { this.getBadge() }
                </p>
            </>
        );
    }

    getItem() {
        if (!this.props.current.href) {
            return (
                <li className={ this.props.current.itemClass }>
                    { this.props.current.title }
                </li>
            );
        } else {
            return (
                <li className="nav-item">
                    <Link
                        to={ this.props.current.href }
                        className={ this.props.current.itemClass } 
                    >
                        { this.getItemContent() }
                    </Link>
                </li>
            );
        }
    }

    render() {
        return this.getItem();
    }
}

export default SidebarItem;