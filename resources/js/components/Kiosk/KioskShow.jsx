import React, {Component} from 'react';
import {user as loggedInUser, trans} from "../../helpers";
import {Card, CardHeader, Container, Col, Row, Button, ListGroup, ListGroupItem, Badge} from 'reactstrap';
import {kioskShow} from '../../api.js';
class KioskShow extends Component {

    state = {
        kiosk: {}
    };


    componentDidMount() {
        const {match} = this.props;
        const kioskId = match.params.kiosk_id;
        kioskShow(kioskId)
            .then(({data}) => this.setState({kiosk: data}))
    }

    render() {
        const {kiosk} = this.state;
        return (
            <Container className="py-4">
                <Card>
                    <CardHeader>
                        <Row>
                            <Col>
                                <a href="/admin/kiosks">
                                    <h4>{`<<<  ${trans('kiosks.title')}`}</h4>
                                </a>
                            </Col>
                            <Col className="d-flex justify-content-end">
                                {loggedInUser.can('destroy all kiosks') &&
                                <Button className="mr-3" color="danger">{trans('kiosks.delete')}</Button>
                                }
                                {loggedInUser.can('edit all kiosks') &&
                                <Button color="primary">{trans('kiosks.edit')}</Button>
                                }
                            </Col>
                        </Row>
                    </CardHeader>
                    <ListGroup>
                        <ListGroupItem>
                            <Row className="text-center">
                                <Col><h3>{`${trans('kiosks.name')}: ${kiosk.name}`}</h3></Col>
                                <Col><h3>{`${trans('kiosks.location')}: ${kiosk.location}`}</h3></Col>
                            </Row>
                        </ListGroupItem>
                        <ListGroupItem>
                            <Row className="text-center">
                                <Col>{`${trans('kiosks.identifier')}: ${kiosk.identifier}`}</Col>
                                <Col>{`${trans('kiosks.tag')}: ${kiosk.asset_tag}`}</Col>
                            </Row>
                        </ListGroupItem>
                    </ListGroup>
                </Card>
            </Container>
        );
    }
}

export default KioskShow;