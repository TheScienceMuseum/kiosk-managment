import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {CardColumns} from "reactstrap";
import HealthCheckComponent from "../../components/Widgets/Kiosks/HealthCheckComponent";

class DashboardLayout extends Component {
    render() {
        return (
            <div>
                <CardColumns>
                    {User.can('view all kiosks') &&
                        <HealthCheckComponent/>
                    }
                </CardColumns>
            </div>
        );
    }
}

DashboardLayout.propTypes = {};

export default DashboardLayout;
