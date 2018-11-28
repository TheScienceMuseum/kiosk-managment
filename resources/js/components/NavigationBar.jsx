import React, {Component} from 'react';
import {Navbar, NavbarBrand, Nav, NavItem, NavLink, NavbarToggler, Collapse, Container, UncontrolledDropdown, DropdownToggle, DropdownMenu, DropdownItem, Form} from 'reactstrap';
import {user, trans} from '../helpers';
import {userLogout} from '../api';

class NavigationBar extends Component {

    state = {
        toggle: false
    };

    render() {
        return (
            <Navbar dark color="dark" expand="sm">
                <NavbarBrand href="/" style={{color: 'white'}}>Kiosk Manager</NavbarBrand>
                <NavbarToggler onClick={this.toggle}/>
                <Collapse isOpen={this.state.toggle} navbar>
                    <Nav className="ml-auto" navbar>
                        {user.can('view all packages') &&
                        <NavItem>
                            <NavLink href="/admin/packages">{trans('packages.title')}</NavLink>
                        </NavItem>
                        }
                        {user.can('view all kiosks') &&
                        <NavItem>
                            <NavLink href="/admin/kiosks">{trans('kiosks.title')}</NavLink>
                        </NavItem>
                        }
                        {user.can('view all users') &&
                        <NavItem>
                            <NavLink href="/admin/users">{trans('users.title')}</NavLink>
                        </NavItem>
                        }
                        {user.guest
                            ? <NavItem>
                                <NavLink href="/login">{trans('auth.login')}</NavLink>
                            </NavItem>
                            : <UncontrolledDropdown nav inNavbar>
                                <DropdownToggle nav caret>
                                    {user.name}
                                </DropdownToggle>
                                <DropdownMenu right>
                                    <DropdownItem tag="a" href="/logout" onClick={this.logout}>{trans('auth.logout')}</DropdownItem>
                                </DropdownMenu>
                            </UncontrolledDropdown>
                        }
                    </Nav>
                </Collapse>
            </Navbar>
        );
    };

    logout = (e) => {
        e.preventDefault();
        userLogout()
            .then(() => {
                window.location.href = '/login';
            })
        // TODO: fix properly...
    };

    toggle = () => {
        this.setState({toggle: !this.state.toggle})
    }
}

export default NavigationBar;