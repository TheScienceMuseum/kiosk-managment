import React, {Component} from 'react';
import api from '../../api';
import { Card, CardHeader, CardBody, CardTitle, CardSubtitle, Button, ListGroup, ListGroupItem, Container, Row, Col, Badge, Collapse, Form, FormGroup, Label, Input, ButtonGroup} from 'reactstrap';
import {trans} from '../../helpers';

class UserIndex extends Component {

    state = {
        users: [],
        links: [],
        meta: [],
        roles: [],
        filterToggle: false,
        filter: {
            name: '',
            email: '',
            role: trans('users.any')
        }
    };

    componentDidMount() {
       api.userIndex()
           .then(data => this.setState({
               users: data.data,
               links: data.links,
               meta: data.meta,
           }));
       api.userRoleIndex()
           .then(({data}) => {
               this.setState({ roles: data });
           });

    }
    
    render() {
        let { users } = this.state;
        const { roles, filter } = this.state;

        if (filter.name) users = users.filter(user => user.name.toLowerCase().includes(filter.name.toLowerCase()));
        if (filter.email) users = users.filter(user => user.email.toLowerCase().includes(filter.email.toLowerCase()));
        if (filter.role !== trans('users.any')) users = users.filter(user => {
            const userRoles = user.roles.map(role => trans(`users.${role.name.replace(' ', '_')}`));
            return userRoles.includes(filter.role);
        });

        return (
            <Container>
                <Card>
                    <CardHeader>
                        <Row>
                            <Col><h4>{trans('users.title')}</h4></Col>
                            <Col><Button className="float-right" onClick={this.filterToggle} color="dark">{trans('users.filter')}</Button></Col>
                        </Row>
                    </CardHeader>
                        <Collapse isOpen={this.state.filterToggle}>
                            <CardBody>
                                <Form>
                                    <Row form>
                                        <Col>
                                            <FormGroup className="mr-3">
                                                <Label for="user-name-filter">{trans('users.name')}</Label>
                                                <Input type="text" name="name" id="user-name-filter" onChange={this.handleChange} value={this.state.filter.name} />
                                            </FormGroup>
                                        </Col>
                                        <Col>
                                            <FormGroup className="mr-3">
                                                <Label for="user-email-filter">{trans('users.email')}</Label>
                                                <Input type="email" name="email" id="user-email-filter" onChange={this.handleChange} value={this.state.filter.email}/>
                                            </FormGroup>
                                        </Col>
                                        <Col>
                                            <FormGroup className="mr-3">
                                                <Label for="user-role-filter">{trans('users.role')}</Label>
                                                <Input type="select" name="role" id="user-role-filter" onChange={this.handleChange} value={this.state.filter.role}>
                                                    <option>{trans('users.any')}</option>
                                                    {roles.map(role => <option key={role.name}>{trans(`users.${role.name.replace(' ', '_')}`)}</option>)}
                                                </Input>
                                            </FormGroup>
                                        </Col>
                                    </Row>
                                        <Button className="float-right mb-2" outline color="danger" onClick={this.resetFilters}>{trans('users.reset')}</Button>
                                </Form>
                            </CardBody>
                        </Collapse>
                        <ListGroup>
                            {users.map((user, index) => {
                                const userId = _.last(user.path.split('/'));

                                return (
                                    <ListGroupItem key={index}>
                                        <Row>
                                            <Col>
                                              <CardTitle>{user.name}</CardTitle>
                                               <CardSubtitle>{user.email}</CardSubtitle>
                                            </Col>
                                            <Col className="text-center">
                                                {user.roles.map(role => <Badge className="mt-2" color="primary" key={role.name}>{trans(`users.${role.name.replace(' ', '_')}`)}</Badge>)}
                                            </Col>
                                            <Col>
                                                <a href={`/admin/users/${userId}`}>
                                                    <Button color="dark" outline className="float-right">{trans('users.view')}</Button>
                                                </a>
                                            </Col>
                                        </Row>
                                    </ListGroupItem>
                                )
                            })}
                        </ListGroup>
                </Card>
            </Container>
        );
    }

    filterToggle = () => {
        this.setState({filterToggle: !this.state.filterToggle});
    };

    handleChange = (e) => {
        const filter = Object.assign({}, this.state.filter);
        filter[e.target.name] = e.target.value;
        this.setState({
            filter
        })
    };

    resetFilters = (e) => {
        e.preventDefault();
        this.setState({
            filter: {
                name: '',
                email: '',
                role: trans('users.any')
            }
        });
    };
}

export default UserIndex;