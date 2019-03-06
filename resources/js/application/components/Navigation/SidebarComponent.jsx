import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {Link, NavLink} from "react-router-dom";

import logo from '../../images/logo.svg';
import Help from "./Help";

class SidebarComponent extends Component {
    render() {
        return (
            <nav className={'sidebar'}>
                <Link to={'/dashboard'}>
                    <div className="sidebar-header">
                        <img className={'logo'} src={logo} alt={'Brand Logo'}/>
                    </div>
                </Link>

                <ul className="list-unstyled components">
                    {this.props.routes.map((prop, key) =>
                        prop.show_in_menu &&
                        <li key={key}>
                            <NavLink
                                to={prop.path}
                                className="nav-link"
                                activeClassName="active"
                            >
                                <FontAwesomeIcon icon={prop.icon} size={'2x'} fixedWidth />
                                <span className={'nav-text'}>{prop.name}</span>
                            </NavLink>
                        </li>
                    )}

                    <li>
                        <Help location={this.props.location} />
                    </li>
                </ul>

            </nav>
        );
    }
}

SidebarComponent.propTypes = {
    routes: PropTypes.array.isRequired,
};

export default SidebarComponent;
