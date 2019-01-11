import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Api from "../../../../helpers/Api";
import {Button, Card, CardBody, CardHeader, FormGroup} from "reactstrap";
import {BounceLoader} from "react-spinners";

import Field from "../Interface/Instance/Form/Field";
import {ucwords} from "locutus/php/strings";
import {each} from "lodash";

class ResourceInstance extends Component {
    constructor(props) {
        super(props);

        this._api = new Api(props.resourceName);

        const initialResourceInstance = {};

        _.each(this._api._resourceFields, field => {
            switch (field.type) {
                case 'text':
                    initialResourceInstance[field.name] = '';
                    break;
                case 'select':
                    initialResourceInstance[field.name] = field.multiple ? [] : '';
                    break;
            }
        });

        this.state = {
            resourceInstance: initialResourceInstance,
            resourceInstanceLoading: this.props.resourceInstanceId !== undefined,
        };

        this.flush              = this.flush.bind(this);
        this.handleFieldChange  = this.handleFieldChange.bind(this);
        this.requestInstance    = this.requestInstance.bind(this);
        this.setInstance        = this.setInstance.bind(this);
        this.updateInstance     = this.updateInstance.bind(this);
    }

    componentDidMount() {
        if (this.props.resourceInstanceId) {
            this.requestInstance();
        }
    }

    requestInstance() {
        this.setState(prevState => ({
            ...prevState,
            resourceInstanceLoading: true,
        }), () => {
            this._api.request('show', {}, {id: this.props.resourceInstanceId})
                .then(response => {
                    this.setInstance(response.data.data);
                });
        });
    }

    setInstance(instance) {
        this.setState(prevState => ({
            ...prevState,
            resourceInstance: instance,
            resourceInstanceLoading: false,
        }));
    }

    flush() {
        if (this.state.resourceInstance.id === undefined) {
            this.createInstance();
        } else {
            this.updateInstance();
        }
    }

    updateInstance() {
        this._api.request('update', this.state.resourceInstance, this.state.resourceInstance)
            .then(response => {
                toastr.success(`${ucwords(this._api._resourceName)} has been updated`);
                this.setInstance(response.data.data);
            })
    }

    createInstance() {
        this._api.request('store', this.state.resourceInstance, this.state.resourceInstance)
            .then(response => {
                toastr.success(`${ucwords(this._api._resourceName)} has been created`);
                this.props.history.push(`/admin/${this.props.resourceName}s/${response.data.data.id}`);
                this.setInstance(response.data.data);
            })
    }

    handleFieldChange(field, value) {
        this.setState(prevState => {
            const instance = {...prevState.resourceInstance};

            instance[field.name] = value;

            return {
                ...prevState,
                resourceInstance: instance,
            }
        });
    }

    render() {
        return (
            <Card>
                <CardHeader>
                    {(this.props.resourceInstanceId && (this.state.resourceInstance.name || this.state.resourceInstance.identifier) &&
                        <span>Viewing {this.state.resourceInstance.name || this.state.resourceInstance.identifier}</span>
                    ) || (this.props.resourceInstanceId &&
                        <span>&nbsp;</span>
                    ) || (
                        <span>Creating new {this.props.resourceName}</span>
                    )}
                </CardHeader>

                {(this.state.resourceInstanceLoading &&
                    <CardBody className={'d-flex justify-content-center'}>
                        <BounceLoader/>
                    </CardBody>
                ) || (
                    <CardBody>
                        {this._api._resourceFields.map(field =>
                            <Field field={field}
                                   handleFieldChange={this.handleFieldChange}
                                   key={field.name}
                                   value={field.type === 'resource_collection' ? this.state.resourceInstance : this.state.resourceInstance[field.name]}
                            />
                        )}

                        <FormGroup className={'row mb-0'}>
                            <div className={'offset-sm-2 col-sm-10 d-flex justify-content-between'}>
                                <Button color={'secondary'} onClick={this.props.history.goBack}>Cancel</Button>
                                <Button color={'primary'} onClick={this.flush}>Save</Button>
                            </div>
                        </FormGroup>
                    </CardBody>
                )}
            </Card>
        );
    }
}

ResourceInstance.propTypes = {
    resourceInstanceId: PropTypes.string,
    resourceName: PropTypes.string.isRequired,
};

export default ResourceInstance;
