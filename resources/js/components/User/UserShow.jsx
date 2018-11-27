import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Card, CardHeader, CardBody, Container, Col, Row, Button, ListGroup, ListGroupItem, Badge} from 'reactstrap';
import {user as currentUser, trans} from "../../helpers";
import {userShow} from '../../api.js';
import {Redirect} from 'react-router-dom';

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
            .then(({data}) => this.setState({user: data}));
    };

    render() {
        const {user} = this.state;
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
                                <Button className="mr-3" color="danger">{trans('users.delete')}</Button>
                                }
                                {currentUser.can('edit all users') &&
                                <Button color="primary">{trans('users.edit')}</Button>
                                }
                            </Col>
                        </Row>
                    </CardHeader>
                    <Row>
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
                                            <Badge className="mt-2" color="primary">{trans(`users.${role.name.replace(' ', '_')}`)}</Badge>
                                        </Col>
                                    )
                                })}
                            </Row>
                        </Col>
                    </Row>
                    <br/>
                </Card>
            </Container>
        );
    }
}

UserShow.propTypes = {
    match: PropTypes.object
};

export default UserShow;
