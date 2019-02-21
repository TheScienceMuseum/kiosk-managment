import React, {Component, Fragment} from 'react';
import PropTypes from 'prop-types';
import Api from "../../../../helpers/Api";
import {Button, Card, CardBody, CardHeader, FormGroup} from "reactstrap";
import {BounceLoader} from "react-spinners";

import confirm from 'reactstrap-confirm';
import {ucwords} from "locutus/php/strings";
import {each, get, has, keys} from "lodash";

import Field from "../Interface/Instance/Form/Field";

class ResourceInstance extends Component {
    constructor(props) {
        super(props);

        this._api = new Api(props.resourceName);

        const initialResourceInstance = {};

        each(this._api._resourceFields, field => {
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
            resourceInstanceLoading: !! keys(this.props.resource).length,
            resourceInstanceErrors: {},
            isCreating: ! keys(this.props.resource).length,
        };

        this.flush                          = this.flush.bind(this);
        this.handleFieldChange              = this.handleFieldChange.bind(this);
        this.handleErrorOnInstanceUpdate    = this.handleErrorOnInstanceUpdate.bind(this);
        this.getErrorsForField              = this.getErrorsForField.bind(this);
        this.getResourceInstanceTitleValue  = this.getResourceInstanceTitleValue.bind(this);
        this.requestInstance                = this.requestInstance.bind(this);
        this.setInstance                    = this.setInstance.bind(this);
        this.updateInstance                 = this.updateInstance.bind(this);

        this.resourceInstanceActions = [];
    }

    componentDidMount() {
        if (!this.state.isCreating) {
            this.requestInstance();
        }
    }

    requestInstance() {
        this.setState(prevState => ({
            ...prevState,
            resourceInstanceLoading: true,
        }), () => {
            this._api.request('show', this.props.resource, this.props.resource)
                .then(response => {
                    this.setInstance(response.data.data);
                });
        });
    }

    setInstance(instance) {
        console.log(`setting instance ${JSON.stringify(instance)}`);

        if (this._api._resourceActions.show.actions) {
            this.resourceInstanceActions = [];

            each(this._api._resourceActions.show.actions, action => {
                let hasDisplayCondition = has(action, 'display_condition');
                let displayConditionPassed = false;

                if (hasDisplayCondition) {
                    let displayCondition = get(action, 'display_condition');

                    each(displayCondition, (value, field) => {
                        if (field === 'PERMISSION') {
                            displayConditionPassed = User.can(value);
                        }

                        if (value.constructor === Boolean && !!get(instance, field) === value) {
                            displayConditionPassed = true;
                        }

                        if (value.constructor === String && get(instance, field) === value) {
                            displayConditionPassed = true;
                        }

                        if (value.constructor === Array && value.includes(get(instance, field))) {
                            displayConditionPassed = true;
                        }
                    });
                }

                if (! hasDisplayCondition || (hasDisplayCondition && displayConditionPassed)) {
                    this.resourceInstanceActions.push({
                        label: action.label,
                        callback: () => {
                            if (has(action, 'action.path')) {
                                this.props.history.push(this._api.getUrlFromPathAndInstance(get(action, 'action.path'), this.state.resourceInstance));
                            } else {
                                const doRequest = () => {
                                    if (this._api._resourceName === action.action.resource) {
                                        const params = {...action.action.params};

                                        if (has(action, 'confirmation.choices')) {
                                            each(get(action, 'confirmation.choices'), choice => {
                                                params[choice.name] = this.state.resourceInstance[choice.name];
                                            });
                                        }

                                        this._api.request(action.action.action, params, instance)
                                            .then(response => {
                                                toastr.success(`${action.label} completed`);

                                                if (has(action, 'post_action')) {
                                                    this.requestInstance();
                                                }
                                            });
                                    } else {
                                        const resourceApi = new Api(action.action.resource);
                                        resourceApi.request(action.action.action, {}, instance)
                                            .then(response => {
                                                if (has(action, 'post_action')) {
                                                    if (this._api._resourceName === action.post_action.resource) {
                                                        this.requestInstance();
                                                    } else {
                                                        // find the needed route to display the data
                                                        this.props.history.push(
                                                            `${this.props.location.pathname}/${action.post_action.link_insert}/${response.data.data.id}`
                                                        );
                                                    }
                                                }
                                            })
                                    }
                                };

                                if (has(action, 'confirmation')) {
                                    const confirmation = get(action, 'confirmation');

                                    confirm({
                                        className: 'modal-lg',
                                        message: (
                                            <Fragment>
                                                {confirmation.text}
                                                {confirmation.choices &&
                                                    <div>
                                                    <hr />
                                                        {confirmation.choices.map(choice =>
                                                            <Field key={`confirm-${choice.name}`}
                                                                   value={this.state.resourceInstance[choice.name]}
                                                                   field={choice}
                                                                   handleFieldChange={(field, value) => {
                                                                       this.setState(prevState => {
                                                                           const resourceInstance = {
                                                                               ...prevState.resourceInstance,
                                                                               [field.name]: {
                                                                                   value: value.id,
                                                                                   label: `${value.name} (${value.email})`,
                                                                               },
                                                                           };

                                                                           return {...prevState, resourceInstance}
                                                                       });
                                                                   }}
                                                            />
                                                        )}
                                                    </div>
                                                }
                                            </Fragment>
                                        ),
                                        confirmText: confirmation.yes,
                                        cancelText: confirmation.no,
                                    }).then(result => {
                                        if (result) {
                                            doRequest();
                                        }
                                    });
                                } else {
                                    doRequest();
                                }
                            }
                        },
                    })
                }
            });
        }

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
            .catch(this.handleErrorOnInstanceUpdate);
    }

    createInstance() {
        this._api.request('store', this.state.resourceInstance, this.state.resourceInstance)
            .then(response => {
                toastr.success(`${ucwords(this._api._resourceName)} has been created`);
                this.props.history.push(`/admin/${this.props.resourceName}s/${response.data.data.id}`);
                this.setInstance(response.data.data);
            })
            .catch(this.handleErrorOnInstanceUpdate);
    }

    getErrorsForField(field) {
        return get(this.state.resourceInstanceErrors, field.name, null);
    }

    getResourceInstanceTitleValue() {
        return (this._api._resourceLabelKey && has(this.state.resourceInstance, this._api._resourceLabelKey)) ?
            get(this.state.resourceInstance, this._api._resourceLabelKey) : '';
    }

    handleErrorOnInstanceUpdate(error) {
        if (get(error, 'response.status') === 422) {
            this.setState(prevState => ({
                ...prevState,
                resourceInstanceErrors: get(error, 'response.data.errors', {}),
            }))
        }
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
                <CardHeader className={'d-flex justify-content-between'}>
                    <div>
                    {(this.props.resource && this.getResourceInstanceTitleValue() &&
                        <span>Viewing {this.getResourceInstanceTitleValue()}</span>
                    ) || (this.props.resource &&
                        <span>&nbsp;</span>
                    ) || (
                        <span>Creating new {this.props.resourceName}</span>
                    )}
                    </div>
                    <div>
                        {!this.state.resourceInstanceLoading &&
                            this.resourceInstanceActions.map(action =>
                                <Button key={`index-actions-${action.label}`}
                                        size={'xs'}
                                        className={'mx-1'}
                                        onClick={action.callback}
                                        color={action.color || 'primary'}
                                >
                                    {action.label}
                                </Button>
                        )}
                    </div>
                </CardHeader>

                {(this.state.resourceInstanceLoading &&
                    <CardBody className={'d-flex justify-content-center'}>
                        <BounceLoader/>
                    </CardBody>
                ) || (
                    <CardBody>
                        {this._api._resourceFields.map(field =>
                            (!this.state.isCreating || field.type !== 'resource_collection') &&
                                <Field field={field}
                                       fieldErrors={this.getErrorsForField(field)}
                                       handleFieldChange={this.handleFieldChange}
                                       history={this.props.history}
                                       isCreate={this.state.isCreating}
                                       location={this.props.location}
                                       key={field.name}
                                       value={field.type === 'resource_collection' ? this.props.resource : this.state.resourceInstance[field.name]}
                                />
                        )}

                        <FormGroup className={'row mb-0'}>
                            <div className={'offset-sm-2 col-sm-10 d-flex justify-content-between'}>
                                <Button color={'secondary'} onClick={this.props.history.goBack}>Back</Button>
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
    resource: PropTypes.shape({
        id: PropTypes.oneOfType([
            PropTypes.number,
            PropTypes.string
        ]),
    }),
    resourceName: PropTypes.string.isRequired,
};

export default ResourceInstance;
