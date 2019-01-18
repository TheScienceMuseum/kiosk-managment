import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Route} from "react-router-dom";

// Resource Views
import ResourceIndex from "./Views/ResourceIndex";
import ResourceInstance from "./Views/ResourceInstance";
import Api from "../../../helpers/Api";

class Resource extends Component {
    constructor(props) {
        super(props);

        this.getComponent = this.getComponent.bind(this);
        this._api = new Api(this.props.resourceName);
    }

    getComponent(Component, props) {
        const componentProps = {...props};

        if (props.match.params.id) {
            componentProps.resourceInstanceId = props.match.params.id;
        }

        return ( <Component resourceName={this.props.resourceName} path={props.path} {...componentProps} /> );
    }

    render() {
        return (
            <div>
                <Route component={(props) => this.getComponent(ResourceIndex, props)}
                       path={`${this.props.path}`}
                       exact
                />

                <Route component={(props) => this.getComponent(ResourceInstance, props)}
                       path={`${this.props.path}/create`}
                       exact
                />

                <Route component={(props) => this.getComponent(ResourceInstance, props)}
                       path={`${this.props.path}/:id([0-9]+)`}
                       exact
                />

                {this._api._resourceFields.map(field =>
                    field.link_to_resource &&
                        <Route component={(props) => this.getComponent(ResourceInstance, props)}
                               exact
                               key={`resource-route-sub-${field.name}`}
                               path={`${this.props.path}/:package_id([0-9]+)/${field.link_insert}/:id([0-9]+)`}
                        />
                )}
            </div>
        );
    }
}

Resource.propTypes = {
    resourceName: PropTypes.string.isRequired,
};

export default Resource;
