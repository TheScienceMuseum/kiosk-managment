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
                        ? <Form className="mt-3">
                            <Row>
                                <Col className="text-center" xs={12} md={6}>
                                    <Card className=" w-75 mx-auto mt-3">
                                        <CardHeader>{trans('users.name')}</CardHeader>
                                        <Input type="text" name="name" id="update-user-name" onChange={this.handleChange} value={updatedUser.name}/>
                                    </Card>
                                    <Card className=" w-75 mx-auto mt-3">
                                        <CardHeader>{trans('users.email')}</CardHeader>
                                        <Input type="text" name="email" id="update-user-email" onChange={this.handleChange} value={updatedUser.email}/>
                                    </Card>
                                </Col>
                                <Col className="text-center" xs={12} md={6}>
                                    <Card className=" w-75 mx-auto mt-3">
                                        <CardHeader>{trans('users.roles')}</CardHeader>
                                        <Select id="update-user-role" value={selectedRoles} onChange={this.handleRoleChange} options={roleOptions} isMulti />
                                    </Card>
                                </Col>
                            </Row>
                            <Row>
                                <ButtonGroup className="mx-auto mt-3">
                                    <Button color="danger" onClick={this.discardChanges}>Discard Changes</Button>
                                    <Button color="success" onClick={this.saveChanges}>Save Changes</Button>
                                </ButtonGroup>
                            </Row>
                        </Form>

                        : <Row className="mt-3">
                            <Col className="text-center" xs={12} md={6}>
                                <Card className=" w-75 mx-auto mt-3">
                                    <CardHeader>{trans('users.name')}</CardHeader>
                                    <h3 className="my-3">{user.name}</h3>
                                </Card>
                                <Card className=" w-75 mx-auto mt-3">
                                    <CardHeader>{trans('users.email')}</CardHeader>
                                    <h3 className="my-3">{user.email}</h3>
                                </Card>
                            </Col>
                            <Col className="text-center" xs={12} md={6}>
                                <Card className=" w-75 mx-auto mt-3">
                                    <CardHeader>{trans('users.roles')}</CardHeader>
                                    <h3 className="my-3"><Row>
                                        {user.roles.map(role => {
                                            return (
                                                <Col key={role.name} xs="12">
                                                    <Badge className="mt-2" color="warning">{trans(`users.${role.name.replace(' ', '_')}`)}</Badge>
                                                </Col>
                                            )
                                        })}
                                    </Row></h3>
                                </Card>
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
