import React, {Component} from 'react';
import api from '../../api';
import { Card, CardHeader, CardBody, CardTitle, CardSubtitle, Button, ListGroup, ListGroupItem, Container, Row, Col, Badge, Collapse, Form, FormGroup, Label, Input} from 'reactstrap';

class UserIndex extends Component {

    state = {
        users: [],
        links: [],
        meta: [],
        filterToggle: false,
        filter: {
            name: '',
            email: '',
            role: null
        }
    };

    componentDidMount() {
       api.userIndex()
           .then(data => this.setState({
               users: data.data,
               links: data.links,
               meta: data.meta,
           }));
    }


    render() {
        const { users } = this.state;

        return (
            <Container>
                <Card>
                    <CardHeader>
                        <Row>
                            <Col><h4>Users</h4></Col>
                            <Col><Button className="float-right" onClick={this.filterToggle}>Filter</Button></Col>
                        </Row>
                    </CardHeader>
                        <Collapse isOpen={this.state.filterToggle}>
                            <CardBody>
                                <Form>
                                    <Row form>
                                        <Col>
                                            <FormGroup className="mr-3">
                                                <Label for="user-name-filter">Name</Label>
                                                <Input type="text" name="name" id="user-name-filter" ></Input>
                                            </FormGroup>
                                        </Col>
                                        <Col>
                                            <FormGroup className="mr-3">
                                                <Label for="user-email-filter">Email</Label>
                                                <Input type="email" name="email" id="user-email-filter"></Input>
                                            </FormGroup>
                                        </Col>
                                        <Col>
                                            <FormGroup className="mr-3">
                                                <Label for="user-role-filter">Role</Label>
                                                <Input type="select" name="role" id="user-role-filter">
                                                    <option>Select</option>
                                                    {   // user.roles.map(role => <option>{role.name}</option>)}
                                                        // Need full list of roles
                                                    }
                                                </Input>
                                            </FormGroup>
                                        </Col>
                                    </Row>
                                </Form>
                            </CardBody>
                        </Collapse>
                        <ListGroup>
                            {users.map((user, index) => {
                                return (
                                    <ListGroupItem key={index}>
                                        <Row>
                                            <Col>
                                              <CardTitle>{user.name}</CardTitle>
                                               <CardSubtitle>{user.email}</CardSubtitle>
                                            </Col>
                                            <Col className="text-center">
                                                {user.roles.map(role => <Badge className="mt-2" color="primary" key={role.name}>{role.name}</Badge>)}
                                            </Col>
                                            <Col>
                                                <a href={'/admin/users/' + user.path.match(/[0-9]+/)[0]}>
                                                    <Button color="dark" outline className="float-right">View</Button>
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
    }
}

export default UserIndex;