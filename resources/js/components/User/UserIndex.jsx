import React, {Component} from 'react';
import api from '../../api';
import { Card, CardHeader, CardBody, CardTitle, CardSubtitle, Button, ButtonGroup, ListGroup, ListGroupItem,
    Container, Row, Col, Badge, Collapse, Form, FormGroup, Label, Input, CardFooter, Pagination, PaginationItem, PaginationLink } from 'reactstrap';
import queryString from 'query-string';
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
        const queryObj = queryString.parse(this.props.location.search);
        const apiQueryObj = {};
        if (queryObj.page) apiQueryObj['page[number]'] = queryObj.page;
        if (queryObj.name) apiQueryObj['filter[name]'] = queryObj.name;
        if (queryObj.email) apiQueryObj['filter[email]'] = queryObj.email;
        if (queryObj.role !== trans('users.any')) apiQueryObj['filter[role]'] = queryObj.role;
        const apiQueryString = queryString.stringify(apiQueryObj);
        
        api.userIndex(apiQueryString)
           .then(data => this.setState({
               users: data.data,
               links: data.links,
               meta: data.meta,
               filter: {
                   name: queryObj.name || '',
                   email: queryObj.email || '',
                   role: queryObj.role || trans('users.any')
               }
           }));
       api.userRoleIndex()
           .then(({data}) => {
               this.setState({ roles: data });
           });

    }

    render() {
        let { users } = this.state;
        const { roles, links, meta } = this.state;
        return (
            <Container className="py-4">
                <Card>
                    <CardHeader>
                        <Row>
                            <Col><h4>{trans('users.title')}</h4></Col>
                            <Col className="d-flex justify-content-end">
                                <Button className="mr-3" color="success">{trans('users.create')}</Button>
                                <Button onClick={this.filterToggle} color="dark">{trans('users.filter')}</Button>
                            </Col>
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
                                                <Input type="text" name="email" id="user-email-filter" onChange={this.handleChange} value={this.state.filter.email}/>
                                            </FormGroup>
                                        </Col>
                                        <Col>
                                            <FormGroup className="mr-3">
                                                <Label for="user-role-filter">{trans('users.role')}</Label>
                                                <Input type="select" name="role" id="user-role-filter" onChange={this.handleChange} value={this.state.filter.role}>
                                                    <option>{trans('users.any')}</option>
                                                    {roles.map(role => <option key={role.name} value={trans(`users.${role.name.replace(' ', '_')}`)}>{trans(`users.${role.name.replace(' ', '_')}`)}</option>)}
                                                </Input>
                                            </FormGroup>
                                        </Col>
                                    </Row>
                                    <Row className="float-right">
                                        <Button className="mb-2 mr-3" outline color="danger" href="/admin/users">{trans('users.reset')}</Button>
                                        <Button className="mb-2" color="dark">{trans('users.apply_filter')}</Button>
                                    </Row>
                                </Form>
                            </CardBody>
                        </Collapse>
                        <ListGroup>
                            { users.map((user, index) => {
                                const userId = _.last(user.path.split('/'));

                                return (
                                    <ListGroupItem key={index} className="rounded-0">
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
                    <CardFooter className=" d-flex justify-content-center" >
                        <Pagination size="lg">
                            <PaginationItem disabled={!links.prev}>
                                <PaginationLink previous href={`/admin/users?${this.increaseOrDecreasePagination('down')}`}/>
                            </PaginationItem>
                            <PaginationItem disabled={!links.next}>
                                <PaginationLink next href={`/admin/users?${this.increaseOrDecreasePagination('up')}`}/>
                            </PaginationItem>
                        </Pagination>
                    </CardFooter>
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

    increaseOrDecreasePagination = (direction) => {
        const queryObj = queryString.parse(this.props.location.search);
        if(!queryObj.page) queryObj.page = 1;

        if (direction === 'up') queryObj.page = parseInt(queryObj.page) + 1;
        else if (direction === 'down') queryObj.page = parseInt(queryObj.page) - 1;
        return queryString.stringify(queryObj);
    };
}

export default UserIndex;