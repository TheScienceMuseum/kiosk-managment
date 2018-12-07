import React, {Component} from 'react';
import {kioskIndex} from '../../api';
import { Card, CardHeader, CardBody, CardTitle, CardSubtitle, Button, ListGroup, ListGroupItem, Container, Row, Col, Collapse, Form, FormGroup, Label, Input, CardFooter} from 'reactstrap';
import {trans, user as loggedInUser} from '../../helpers';

class KioskIndex extends Component {

    state = {
        kiosks: [],
        toggleFilter: false,
        filter: {
            name: '',
            location: trans('kiosks.any')
        }
    };


    componentDidMount() {
        kioskIndex()
            .then(({data}) => this.setState({kiosks: data}))
    }

    render() {
        let {kiosks} = this.state;
        const {filter} = this.state;
        const locations = kiosks.map(kiosk => kiosk.location);

        if (filter.name) kiosks = kiosks.filter(kiosk => kiosk.name.includes(filter.name));
        if (filter.location !== trans('kiosks.any') ) kiosks = kiosks.filter(kiosk => kiosk.location === filter.location);

        return (
            <Container className="py-4">
                <Card>
                    <CardHeader>
                        <Row>
                            <Col><h4>{trans('kiosks.title')}</h4></Col>
                            <Col className="d-flex justify-content-end">
                                {loggedInUser.can('create new kiosks') && <Button className="mr-3" color="success">{trans('kiosks.create')}</Button>}
                                <Button onClick={this.filterToggle} color="dark">{trans('kiosks.filter')}</Button>
                            </Col>
                        </Row>
                    </CardHeader>
                    <Collapse isOpen={this.state.filterToggle}>
                        <CardBody>
                            <Form>
                                <Row form>
                                    <Col>
                                        <FormGroup className="mr-3">
                                            <Label for="kiosk-name-filter">{trans('kiosks.name')}</Label>
                                            <Input type="text" name="name" id="kiosk-name-filter" onChange={this.handleChange} value={this.state.filter.name} />
                                        </FormGroup>
                                    </Col>
                                    <Col>
                                        <FormGroup className="mr-3">
                                            <Label for="kiosk-location-filter">{trans('kiosks.location')}</Label>
                                            <Input type="select" name="location" id="kiosk-location-filter" onChange={this.handleChange} value={this.state.filter.location}>
                                                <option>{trans('kiosks.any')}</option>
                                                {locations.map(location => <option key={location}>{location}</option>)}
                                            </Input>
                                        </FormGroup>
                                    </Col>
                                </Row>
                                <Button className="float-right mb-2" outline color="danger" onClick={this.resetFilters}>{trans('kiosks.reset')}</Button>
                            </Form>
                        </CardBody>
                    </Collapse>
                    <ListGroup>
                        {kiosks.map((kiosk, index) => {
                            const kioskId = _.last(kiosk.path.split('/'));

                            return (
                                <ListGroupItem key={index} className="rounded-0">
                                    <Row>
                                        <Col>
                                            <CardTitle>{kiosk.name}</CardTitle>
                                            <CardSubtitle>{`${trans('kiosks.location')}: ${kiosk.location}`}</CardSubtitle>
                                        </Col>

                                        <Col>
                                            <a href={`/admin/kiosks/${kioskId}`}>
                                                <Button color="dark" outline className="float-right">{trans('kiosks.view')}</Button>
                                            </a>
                                        </Col>
                                    </Row>
                                </ListGroupItem>
                            )
                        })}
                    </ListGroup>
                    <CardFooter>

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

    resetFilters = (e) => {
        e.preventDefault();
        this.setState({
            filter: {
                name: '',
                location: trans('kiosks.any')
            }
        });
    };
}

export default KioskIndex;