import React, {Component} from 'react';
import api from '../../api';
import { Card, CardHeader, CardBody, CardTitle, CardSubtitle, Button, ListGroup, ListGroupItem, Container, Row, Col, Badge} from 'reactstrap';

class UserIndex extends Component {
    constructor() {
        super();
        this.state = {
            users: [],
            links: [],
            meta: []
        }
    };

    componentDidMount() {
       api.userIndex()
           .then(data => this.setState({
               users: data.data,
               links: data.links,
               meta: data.meta
           }))
    }


    render() {
        const { users } = this.state;

        return (
            <Container>
                <Card>
                    <CardHeader>
                        <h4>Users</h4>
                    </CardHeader>
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
                                                {user.roles.map(role => <Badge color="primary" key={role.name}>{role.name}</Badge>)}
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
}

export default UserIndex;