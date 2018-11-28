import React, {Component} from 'react';
import {Container, Row, Col, Button, Badge, Card, CardBody, CardHeader, Form, FormGroup, Label, Input} from 'reactstrap';
import {trans, user as currentUser} from "../../helpers";
import {Redirect} from 'react-router-dom';
import api from "../../api";
import Select from 'react-select';

class UserCreate extends Component {

    state = {
        newUser: {
            name: '',
            email: ''
        },
        allRoles: [],
        redirect: '',
        selectedRoles: null,
    };

    componentDidMount() {
        api.userRoleIndex()
            .then(({data}) => {
                this.setState({ allRoles: data });
            });
    }


    render() {
        let {allRoles, redirect, selectedRoles} = this.state;
        const roleOptions = allRoles.map(role => {
            return {
                value: role.name,
                label: trans(`users.${role.name.replace(' ', '_')}`)
            }
        });


        return (
            <Container className="py-4">
                {!currentUser.can('create new users') && <Redirect to="/error/401"/>}
                {redirect !== '' && <Redirect to={`/admin/users/${redirect}`} />}
                <Card>
                    <CardHeader>
                        <h3>Create a new user</h3>
                    </CardHeader>
                    <CardBody>
                        <Form className="w-75 mx-auto">
                            <FormGroup className="mr-3">
                                <Label for="new-user-name">{trans('users.name')}</Label>
                                <Input type="text" name="name" id="new-user-name" onChange={this.handleChange} value={this.state.newUser.name}/>
                            </FormGroup>
                            <FormGroup className="mr-3">
                                <Label for="new-user-email">{trans('users.email')}</Label>
                                <Input type="text" name="email" id="new-user-email" onChange={this.handleChange} value={this.state.newUser.email}/>
                            </FormGroup>
                            <FormGroup className="mr-3">
                                <Label for="new-user-role">{trans('users.roles')}</Label>
                                <Select id="new-user-role" value={selectedRoles} onChange={this.handleRoleChange} options={roleOptions} isMulti />
                            </FormGroup>
                            <Button className="float-right my-3" onClick={this.createUser} color="primary">Create User</Button>
                        </Form>
                    </CardBody>
                </Card>
            </Container>
        );
    }

    handleChange = (e) => {
        const newUser = Object.assign({}, this.state.newUser);
        newUser[e.target.name] = e.target.value;
        this.setState({
            newUser
        })
    };

    handleRoleChange = (selectedRoles) => {
        this.setState({ selectedRoles });
    };

    addRole = (e) => {
        e.preventDefault();
        const newUser = Object.assign({}, this.state.newUser);
        newUser.roles.push(this.state.roleChoice);
        this.setState({
            newUser,
            roleChoice: 'Select'
        })
    };

    removeRole = (e) => {
        e.persist();
        const newUser = Object.assign({}, this.state.newUser);
        const roleToRemove = e.target.innerText.toLowerCase();
        const index = newUser.roles.findIndex(role => role === roleToRemove );
        newUser.roles.splice(index, 1);
        this.setState({
            newUser
        });
    };

    createUser = (e) => {
        e.preventDefault();
        const {newUser, selectedRoles} = this.state;
        newUser.roles = selectedRoles.map(role => role.value);

        if (newUser.name === '' || newUser.email === '' || newUser.roles.length === 0) window.alert('Please fill out all fields');
        else {
        api.userCreate(newUser)
            .then(({data}) => {
                const userId = _.last(data.path.split('/'));
                this.setState({
                    redirect: userId
                })
            });
        }
    };
}

export default UserCreate;

