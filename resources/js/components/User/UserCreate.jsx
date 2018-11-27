import React, {Component} from 'react';
import {Container, Row, Col, Button, Badge, Card, CardBody, CardHeader, Form, FormGroup, Label, Input} from 'reactstrap';
import {trans, user as currentUser} from "../../helpers";
import {Redirect} from 'react-router-dom';
import api from "../../api";


class UserCreate extends Component {

    state = {
        newUser: {
            name: '',
            email: '',
            roles: []
        },
        allRoles: [],
        roleChoice: 'Select',
        redirect: ''
    };

    componentDidMount() {
        api.userRoleIndex()
            .then(({data}) => {
                this.setState({ allRoles: data });
            });
    }


    render() {
        let {allRoles, newUser, roleChoice, redirect} = this.state;
        allRoles = allRoles.filter(role => !newUser.roles.includes(role.name));

        return (
            <Container className="py-4">
                {!currentUser.can('create new users') && <Redirect to="/error/401"/>}
                {redirect !== '' && <Redirect to={`/admin/users/${redirect}`} />}
                <Card>
                    <CardHeader>
                        <h3>Create a new user</h3>
                    </CardHeader>
                    <CardBody>
                        <Form>
                            <FormGroup className="mr-3">
                                <Label for="new-user-name">{trans('users.name')}</Label>
                                <Input type="text" name="name" id="new-user-name" onChange={this.handleChange} value={this.state.newUser.name}/>
                            </FormGroup>
                            <FormGroup className="mr-3">
                                <Label for="new-user-email">{trans('users.email')}</Label>
                                <Input type="text" name="email" id="new-user-email" onChange={this.handleChange} value={this.state.newUser.email}/>
                            </FormGroup>
                            <Row>
                                <Col>
                                    <FormGroup className="mr-3">
                                        <Label for="new-user-role">{trans('users.role')}</Label>
                                        <Input type="select" name="roles" id="new-user-role" onChange={(e) => this.setState({roleChoice: e.target.value})} value={roleChoice}>
                                            <option>Select</option>
                                            {allRoles.map(role => <option key={role.name} value={role.name}>{trans(`users.${role.name.replace(' ', '_')}`)}</option>)}
                                        </Input>
                                        <Button className="mt-2 float-right" color="primary" disabled={roleChoice === 'Select'} onClick={this.addRole}>Add Role</Button>
                                    </FormGroup>
                                </Col>
                                <Col className="text-center">
                                    <h5>Roles to be assigned:</h5>
                                    {newUser.roles.map(role => <Badge onClick={this.removeRole} className="mt-2 mx-2" color="warning" key={role}>{trans(`users.${role.replace(' ', '_')}`)}</Badge>)}
                                </Col>
                            </Row>
                            <br/>
                            <Button onClick={this.createUser} color="primary" block>Create User</Button>
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
        const {newUser} = this.state;
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

