import React, {Component} from 'react';
import api from '../../api';
import { Card, CardHeader, CardBody, CardTitle, CardSubtitle, Button, ButtonGroup, ListGroup, ListGroupItem,
    Container, Row, Col, Badge, Collapse, Form, FormGroup, Label, Input, CardFooter, Pagination, PaginationItem, PaginationLink } from 'reactstrap';
import queryString from 'query-string';
import {trans, user as currentUser} from '../../helpers';
import {Redirect} from 'react-router-dom';
import Select from 'react-select';

class UserIndex extends Component {

    state = {
        users: [],
        links: [],
        meta: [],
        allRoles: [],
        filterToggle: false,
        filter: {
            name: '',
            email: '',
            role: null
        }
    };

    componentDidMount() {
        const queryObj = queryString.parse(this.props.location.search);
        const apiQueryObj = {};
        if (queryObj.page) apiQueryObj['page[number]'] = queryObj.page;
        if (queryObj.name) apiQueryObj['filter[name]'] = queryObj.name;
        if (queryObj.email) apiQueryObj['filter[email]'] = queryObj.email;
        if (queryObj.role) apiQueryObj['filter[role]'] = queryObj.role;
        const apiQueryString = queryString.stringify(apiQueryObj);

        let roleOption = '';
        if (queryObj.role) roleOption = {
            value: queryObj.role,
            label: trans(`users.${queryObj.role.replace(' ', '_')}`)
        };

        api.userIndex(apiQueryString)
           .then(data => this.setState({
               users: data.data,
               links: data.links,
               meta: data.meta,
               filter: {
                   name: queryObj.name || '',
                   email: queryObj.email || '',
                   role: roleOption || null
               }
           }));
       api.userRoleIndex()
           .then(({data}) => {
               this.setState({ allRoles: data });
           });

    }

    render() {
        let { users } = this.state;
        const { allRoles, links, meta, filter } = this.state;

        const roleOptions = allRoles.map(role => {
            return {
                value: role.name,
                label: trans(`users.${role.name.replace(' ', '_')}`)
            }
        });

        const paginationNumbersArray = new Array(meta.last_page).fill(0).map((el, index) => {
            return index + 1;
        });

        return (
            <Container className="py-4">

                {!currentUser.can('view all users') && <Redirect to="/error/401" /> }

                <Card>
                    <CardHeader>
                        <Row>
                            <Col>
                                <h4>
                                    <a href="/admin/users" style={{color: "inherit", textDecoration: "none"}}>{trans('users.title')}</a>
                                </h4>
                            </Col>
                            <Col className="d-flex justify-content-end">
                                {currentUser.can('create new users') &&
                                <a href="/admin/users/edit/create">
                                    <Button className="mr-3" color="success">{trans('users.create')}</Button>
                                </a>}
                                <Button onClick={this.filterToggle} color="dark">{trans('users.filter')}</Button>
                            </Col>
                        </Row>
                    </CardHeader>
                    <Collapse isOpen={this.state.filterToggle}>
                        <CardBody>
                            <Form>
                                <Row form>
                                    <Col xs={12} md={4}>
                                        <FormGroup className="mr-3">
                                            <Label for="user-name-filter">{trans('users.name')}</Label>
                                            <Input type="text" name="name" id="user-name-filter" onChange={this.handleChange} value={filter.name}/>
                                        </FormGroup>
                                    </Col>
                                    <Col xs={12} md={4}>
                                        <FormGroup className="mr-3">
                                            <Label for="user-email-filter">{trans('users.email')}</Label>
                                            <Input type="text" name="email" id="user-email-filter" onChange={this.handleChange} value={filter.email}/>
                                        </FormGroup>
                                    </Col>
                                    <Col xs={12} md={4}>
                                        <FormGroup className="mr-3">
                                            <Label for="user-role-filter">{trans('users.roles')}</Label>
                                            <Select id="user-role-filter" onChange={this.handleRoleChange} value={filter.role} options={roleOptions}/>
                                        </FormGroup>
                                    </Col>
                                </Row>
                                <Row className="float-right">
                                    <Button className="mb-2 mr-3" outline color="danger" href="/admin/users">{trans('users.reset')}</Button>
                                    <Button className="mb-2" onClick={this.applyFilters} color="dark">{trans('users.apply_filter')}</Button>
                                </Row>
                            </Form>
                        </CardBody>
                    </Collapse>
                    <ListGroup>
                        {users.map((user, index) => {
                            const userId = _.last(user.path.split('/'));
                            return (
                                <ListGroupItem key={index} className="rounded-0">
                                    <Row>
                                        <Col className="text-center text-md-left" xs={12} md={4}>
                                            <CardTitle>{user.name}</CardTitle>
                                            <CardSubtitle>{user.email}</CardSubtitle>
                                        </Col>
                                        <Col className="text-center" xs={12} md={4}>
                                            <Row>
                                                {user.roles.map(role => <Col xs="12" key={role.name}><Badge className="mt-2 mx-2" color="warning">{trans(`users.${role.name.replace(' ', '_')}`)}</Badge></Col>)}
                                            </Row>
                                        </Col>
                                        <Col className=" text-center text-md-right mt-2 mt-md-0" xs={12} md={4} >
                                            <a href={`/admin/users/${userId}`}>
                                                <Button color="dark" outline >{trans('users.view')}</Button>
                                            </a>
                                        </Col>
                                    </Row>
                                </ListGroupItem>
                            )
                        })}
                    </ListGroup>
                    <CardFooter className=" d-flex justify-content-center">
                        <Pagination>
                            <PaginationItem disabled={!links.prev}>
                                <PaginationLink id="pagination-prev-page" previous href={`/admin/users?${this.increaseOrDecreasePagination('down')}`}/>
                            </PaginationItem>
                            {paginationNumbersArray.map(pageNumber => {
                                return (
                                    <PaginationItem active={meta.current_page === pageNumber} key={pageNumber}>
                                        <PaginationLink href={`/admin/users?${this.addPageNumberToQuery(pageNumber)}`}>{pageNumber}</PaginationLink>
                                    </PaginationItem>
                                )
                            })}
                            <PaginationItem disabled={!links.next}>
                                <PaginationLink id="pagination-next-page" next href={`/admin/users?${this.increaseOrDecreasePagination('up')}`}/>
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

    applyFilters = (e) => {
        e.preventDefault();
        const {filter} = this.state;
        let newRole = '';
        if (filter.role) newRole = filter.role.value;
        const queryObj = {
            name: filter.name,
            email: filter.email,
            role: newRole
        };

        const qString = queryString.stringify(queryObj);
        window.location.href = '?' + qString;
    };

    handleChange = (e) => {
        const filter = Object.assign({}, this.state.filter);
        filter[e.target.name] = e.target.value;
        this.setState({
            filter
        })
    };

    handleRoleChange = (selectedRole) => {
        const filter = Object.assign({}, this.state.filter)
        filter.role = selectedRole;
        this.setState({ filter });
    };

    increaseOrDecreasePagination = (direction) => {
        const queryObj = queryString.parse(this.props.location.search);
        if(!queryObj.page) queryObj.page = 1;

        if (direction === 'up') queryObj.page = parseInt(queryObj.page) + 1;
        else if (direction === 'down') queryObj.page = parseInt(queryObj.page) - 1;
        return queryString.stringify(queryObj);
    };

    addPageNumberToQuery = (pageNumber) => {
        const queryObj = queryString.parse(this.props.location.search);
        queryObj.page = pageNumber;
        return queryString.stringify(queryObj);
    };
}

export default UserIndex;