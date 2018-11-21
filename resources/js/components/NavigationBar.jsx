import React, {Component} from 'react';
import {Navbar, NavbarBrand, Nav, NavItem, NavLink, Container, UncontrolledDropdown, DropdownToggle, DropdownMenu, DropdownItem, Form} from 'reactstrap';
import {user, trans} from '../helpers';
import {userLogout} from '../api';

class NavigationBar extends Component {
    render() {
        return (
            <div>
                <Navbar color="dark">
                    <Container>
                        <NavbarBrand href="/">Kiosk Manager</NavbarBrand>
                        <Nav className="ml-auto">
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
                                        <DropdownItem>
                                            <a href="/logout" onClick={this.logout}>{trans('auth.logout')}</a>
                                        </DropdownItem>
                                    </DropdownMenu>
                                </UncontrolledDropdown>

                            }
                        </Nav>
                    </Container>
                </Navbar>
            </div>
        );
    }

    logout = () => {
        userLogout()
    }
}

export default NavigationBar;