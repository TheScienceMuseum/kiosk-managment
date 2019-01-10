import React, {Component} from 'react';
import {Card, CardBody, CardHeader, CardTitle} from "reactstrap";
import PropTypes from 'prop-types';

class HealthCheckComponent extends Component {
    constructor(props) {
        super(props);

    }

    getKioskHealth() {

    }

    componentDidMount() {

    }

    render() {
        return (
            <Card>
                <CardHeader>Kiosk Health</CardHeader>
                <CardBody>

                </CardBody>
            </Card>
        );
    }
}

HealthCheckComponent.propTypes = {};

export default HealthCheckComponent;
