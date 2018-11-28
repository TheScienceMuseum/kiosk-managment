import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Card, CardHeader, Container, Col, Row, Button, ButtonGroup, Badge, Form, FormGroup, Input, Label} from 'reactstrap';
import {user as currentUser, trans} from "../../helpers";
import {userShow, userDestroy} from '../../api.js';
import {Redirect} from 'react-router-dom';
import Select from 'react-select';
import api from "../../api";

class UserShow extends Component {

    state = {
        user: {
            name: '',
            email: '',
            roles: []
        },
        updatedUser: {
            name: '',
            email: ''
        },
        editToggle: false,
        allRoles: [],
        selectedRoles: null
    };


    componentDidMount() {
        const {match} = this.props;
        const userId = match.params.user_id;
        userShow(userId)
            .then(({data}) => {
                const roles = data.roles.map(role => {
                    return {value: role.name, label: trans(`users.${role.name.replace(' ', '_')}`)}
                });
                this.setState({
                    user: data,
                    selectedRoles: roles,
                    updatedUser: {
                        name: data.name,
                        email: data.email
                    }
                });
            }
        );
        api.userRoleIndex()
            .then(({data}) => {
                this.setState({ allRoles: data });
            });
    };

    render() {
        const {user, editToggle, updatedUser, selectedRoles, allRoles} = this.state;
        const userId = this.props.match.params.user_id;
        const roleOptions = allRoles.map(role => {
            return {
                value: role.name,
                label: trans(`users.${role.name.replace(' ', '_')}`)
            }
        });

        return (
            <Container className="py-4">
                {!currentUser.can('view all users') && <Redirect to="/error/401"/>}
                <Card>
                    <CardHeader>
                        <Row>
                            <Col>
                                <a href="javascript:history.back()">
                                    <h4>{`<<<  ${trans('users.title')}`}</h4>
                                </a>
                            </Col>
                            <Col className="d-flex justify-content-end">
                                {currentUser.can('destroy all users') &&
                                <Button className="mr-3" color="danger" onClick={() => this.deleteUser(userId)}>{trans('users.delete')}</Button>
                                }
                                {currentUser.can('edit all users') &&
                                <Button color="primary" onClick={this.editToggle}>{trans('users.edit')}</Button>
                                }
                            </Col>
                        </Row>
                    </CardHeader>
                    {editToggle
                        ? <Form className="w-75 mx-auto mt-3">
                            <Row>
                                <Col>
                                    <FormGroup className="mr-3">
                                        <Label for="update-user-name">{trans('users.name')}</Label>
                                        <Input type="text" name="name" id="update-user-name" onChange={this.handleChange} value={updatedUser.name}/>
                                    </FormGroup>
                                    <FormGroup className="mr-3">
                                        <Label for="update-user-email">{trans('users.email')}</Label>
                                        <Input type="text" name="email" id="update-user-email" onChange={this.handleChange} value={updatedUser.email}/>
                                    </FormGroup>
                                </Col>
                                <Col className="my-auto">
                                    <FormGroup className="mr-3">
                                        <Label for="update-user-role">{trans('users.role')}</Label>
                                        <Select id="update-user-role" value={selectedRoles} onChange={this.handleRoleChange} options={roleOptions} isMulti />
                                    </FormGroup>
                                </Col>
                            </Row>
                            <Row>
                                <ButtonGroup className="mx-auto mt-3">
                                    <Button color="danger" onClick={this.discardChanges}>Discard Changes</Button>
                                    <Button color="success" onClick={this.saveChanges}>Save Changes</Button>
                                </ButtonGroup>
                            </Row>
                        </Form>

                        : <Row>
                            <Col className="text-center">
                                <h3 className="my-3">{`${trans('users.name')}: ${user.name}`}</h3>
                                <h3 className="my-3">{`${trans('users.email')}: ${user.email}`}</h3>
                            </Col>
                            <Col className="text-center">

                                <h3 className="mt-3">Roles:</h3>
                                <Row>
                                    {user.roles.map(role => {
                                        return (
                                            <Col key={role.name} xs="12">
                                                <Badge className="mt-2" color="warning">{trans(`users.${role.name.replace(' ', '_')}`)}</Badge>
                                            </Col>
                                        )
                                    })}
                                </Row>
                            </Col>
                        </Row>
                    }

                    <br/>
                </Card>
            </Container>
        );
    }

    editToggle = () => {
        this.setState({editToggle: !this.state.editToggle});
    };


    deleteUser = (userId) => {
        userDestroy(userId)
            .then(() => {
                window.location.href = '/admin/users'
            });
    };

    handleChange = (e) => {
        const updatedUser = Object.assign({}, this.state.updatedUser);
        updatedUser[e.target.name] = e.target.value;
        this.setState({
            updatedUser
        })
    };

    handleRoleChange = (selectedRoles) => {
        this.setState({ selectedRoles });
    };

    discardChanges = (e) => {
        e.preventDefault();
        const {user} = this.state;
        const roles = user.roles.map(role => {
            return {value: role.name, label: trans(`users.${role.name.replace(' ', '_')}`)}
        });
        this.setState({
           updatedUser: {
               name: user.name,
               email: user.email
           },
            selectedRoles: roles,
            editToggle: false
        });
    };

    saveChanges = (e) => {
        e.preventDefault();
        let {updatedUser, selectedRoles} = this.state;
        const userId = this.props.match.params.user_id;
        updatedUser.roles = selectedRoles.map(role => role.value);
        api.userUpdate(updatedUser, userId)
            .then(() => {
               window.location.reload(true);
            });
    };
}

UserShow.propTypes = {
    match: PropTypes.object
};

export default UserShow;
