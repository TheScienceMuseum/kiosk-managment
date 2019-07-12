import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Route} from "react-router-dom";

// Resource Views
import ResourceIndex from "./Views/ResourceIndex";
import ResourceInstance from "./Views/ResourceInstance";
import Api from "../../../helpers/Api";
import {each, get, has, set} from "lodash";

class Resource extends Component {
    constructor(props) {
        super(props);

        this.getComponent = this.getComponent.bind(this);
        this._api = new Api(this.props.resourceName);
    }

    shouldComponentUpdate(nextProps, nextState) {
        const { location } = this.props;
        return location.pathname !== nextProps.location.pathname;
    }

    getComponent(Component, props, field = null) {
        const componentProps = {...props};
        let componentResource = this.props.resourceName;

        if (props.match.params) {
            componentProps.resource = {};
            each(props.match.params, (value, param) => {
                set(componentProps.resource, param.replace(/_/g, '.'), value);
            });
        }

        if (field) {
            if (has(field, 'resource')) {
                componentResource = get(field, 'resource')
            }
        }

        return (<Component resourceName={componentResource} path={props.path} {...componentProps} />);
    }

    render() {
        return (
            <div className={'Resource'}>
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
                    <Route component={(props) => this.getComponent(ResourceInstance, props, field)}
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
