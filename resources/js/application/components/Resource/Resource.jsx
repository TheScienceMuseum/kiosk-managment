import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Route} from "react-router-dom";

// Resource Views
import ResourceIndex from "./Views/ResourceIndex";
import ResourceInstance from "./Views/ResourceInstance";

class Resource extends Component {
    constructor(props) {
        super(props);

        this.getComponent = this.getComponent.bind(this);
    }

    getComponent(Component, props) {
        const componentProps = {...props};

        if (props.match.params.id) {
            componentProps.resourceInstanceId = props.match.params.id;
        }

        return ( <Component resourceName={this.props.resourceName} {...componentProps} /> );
    }

    render() {
        return (
            <div>
                <Route component={(props) => this.getComponent(ResourceIndex, props)}
                       path={`/admin/${this.props.resourceName}s`}
                       exact
                />

                <Route component={(props) => this.getComponent(ResourceInstance, props)}
                       path={`/admin/${this.props.resourceName}s/create`}
                />

                <Route component={(props) => this.getComponent(ResourceInstance, props)}
                       path={`/admin/${this.props.resourceName}s/:id([0-9]+)`}
                />
            </div>
        );
    }
}

Resource.propTypes = {
    resourceName: PropTypes.string.isRequired,
};

export default Resource;
