import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Card, CardHeader, Container, Col, Row, Button, ListGroup, ListGroupItem, Badge} from 'reactstrap';
import {user as loggedInUser, trans} from "../../helpers";
import {userShow} from '../../api.js';

class UserShow extends Component {

    state = {
        user: {
            name: '',
            email: '',
            roles: []
        }
    };


    componentDidMount() {
        const {match} = this.props;
        const userId = match.params.user_id;
        userShow(userId)
            .then(({data}) => this.setState({ user: data}));
    };

    render() {
        const {user} = this.state;
        return (
            <Container className="py-4">
                <Card>
                    <CardHeader>
                        <Row>
                            <Col>
                                <a href="/admin/users">
                                    <h4>{`<<<  ${trans('users.title')}`}</h4>
                                </a>
                            </Col>
                            <Col className="d-flex justify-content-end">
                                {loggedInUser.can('destroy all users') &&
                                    <Button className="mr-3" color="danger">{trans('users.delete')}</Button>
                                }
                                {loggedInUser.can('edit all users') &&
                                    <Button color="primary">{trans('users.edit')}</Button>
                                }
                            </Col>
                        </Row>
                    </CardHeader>
                    <ListGroup className="text-center">
                        <ListGroupItem><h3>{`${trans('users.name')}: ${user.name}`}</h3></ListGroupItem>
                        <ListGroupItem><h3>{`${trans('users.email')}: ${user.email}`}</h3></ListGroupItem>
                        <ListGroupItem>
                            <h3>Roles:</h3>
                            <Row>
                                {user.roles.map(role => {
                                    return (
                                        <Col>
                                            <Badge className="mt-2" color="primary" key={role.name}>{trans(`users.${role.name.replace(' ', '_')}`)}</Badge>
                                        </Col>
                                    )
                                })}
                            </Row>
                        </ListGroupItem>
                    </ListGroup>
                </Card>
            </Container>
        );
    }
}

UserShow.propTypes = {
    match: PropTypes.object
};

export default UserShow;
