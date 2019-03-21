import React, {Fragment} from 'react';
import axios from 'axios';
import {each, get, has, last} from "lodash";
import confirm from "reactstrap-confirm";

import ApplicationSchema from '../../application-schema';
import Field from '../application/components/Resource/Interface/Instance/Form/Field';


class Api {
    _resourceActions = {};
    _resourceFields = {};
    _resourceName = '';
    _resourceLabelKey = null;

    constructor(resourceName) {
        if (! ApplicationSchema.resources[resourceName]) {
            throw `The resource ${resourceName} does not exist in the application-schema file.`
        }

        this._resourceName      = resourceName;
        this._resourceLabelKey  = ApplicationSchema.resources[resourceName].label_key;
        this._resourceActions   = ApplicationSchema.resources[resourceName].actions;
        this._resourceFields    = ApplicationSchema.resources[resourceName].fields;
    }

    hasAction(actionName) {
        return this._resourceActions.hasOwnProperty(actionName);
    }

    getUrlFromPathAndInstance(path, instance) {
        const matches = path.match(/{[A-Za-z0-9.]+}/g);

        if (matches && matches.length > 0) {
            if (instance === null) {
                throw `The required instance was not passed to the request for ${path}`;
            }

            each(matches, (match) => {
                const matchProp = match.replace('{', '').replace('}', '');

                if (! has(instance, matchProp)) {
                    throw `The instance passed to the API does not have the property ${matchProp}`;
                }

                path = path.replace(match, get(instance, matchProp));
            });
        }

        return path;
    }

    action(action, inputInstance, callbacks) {
        const instance = {...inputInstance};
        return () => {
            if (has(action, 'action.path')) {
                callbacks.path(this.getUrlFromPathAndInstance(get(action, 'action.path'), instance));
            } else {

                const postAction = (response) => {
                    if (has(action, 'post_action')) {
                        toastr.success(`${action.label} completed`);

                        if (has(action, 'post_action.refresh') && has(callbacks, 'requestInstance')) {
                            callbacks.requestInstance();
                        }

                        if (has(action, 'post_action.resource')) {
                            if (this._resourceName === get(action, 'post_action.resource')) {
                                callbacks.requestInstance();
                            }
                        }
                        if (has(action, 'post_action.path')) {
                            // find the needed route to display the data
                            callbacks.path(
                                this.getUrlFromPathAndInstance(
                                    get(action, 'post_action.path'),
                                    response.data.data
                                )
                            );
                        }
                    }
                };

                const doRequest = () => {
                    if (this._resourceName === action.action.resource) {
                        const params = {...action.action.params};

                        if (has(action, 'confirmation.choices')) {
                            each(get(action, 'confirmation.choices'), choice => {
                                if (
                                    get(choice, 'nullable') &&
                                    JSON.stringify(get(choice, 'default')) === JSON.stringify(instance[choice.name])
                                ) {
                                    instance[choice.name] = null;
                                }

                                params[choice.name] = instance[choice.name];
                            });
                        }

                        this.request(action.action.action, params, instance)
                            .then(postAction);
                    } else {
                        const resourceApi = new Api(action.action.resource);
                        const params = {...action.action.params};

                        if (has(action, 'confirmation.choices')) {
                            each(get(action, 'confirmation.choices'), choice => {
                                if (
                                    get(choice, 'nullable') &&
                                    JSON.stringify(get(choice, 'default')) === JSON.stringify(instance[choice.name])
                                ) {
                                    instance[choice.name] = null;
                                }

                                params[choice.name] = instance[choice.name];
                            });
                        }

                        resourceApi.request(action.action.action, params, instance)
                            .then(postAction)
                    }
                };

                if (has(action, 'confirmation')) {
                    const confirmation = get(action, 'confirmation');

                    confirm({
                        className: 'modal-lg',
                        message: (
                            <Fragment>
                                <div dangerouslySetInnerHTML={{__html: this.getUrlFromPathAndInstance(confirmation.text, instance)}} />
                                {confirmation.choices &&
                                <div>
                                    <hr />
                                    {confirmation.choices.map(choice => {
                                        instance[choice.name] = get(choice, 'default', instance[choice.name]);
                                        return (
                                            <Field key={`confirm-${choice.name}`}
                                                   value={instance[choice.name]}
                                                   field={choice}
                                                   stateful
                                                   handleFieldChange={(field, value) => {
                                                       if (!value) {
                                                           return instance[field.name] = value;
                                                       }

                                                       if (value.constructor === String) {
                                                           return instance[field.name] = value;
                                                       }

                                                       if (has(value, 'id')) {
                                                           return instance[field.name] = value.id;
                                                       }

                                                       return instance[field.name] = null;
                                                   }}
                                            />
                                        )
                                    }

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
        };
    }

    request(actionName, params, instance = null) {
        console.log(`triggering request ${actionName} on resource ${this._resourceName} with params: ${JSON.stringify(params)} and instance: ${JSON.stringify(instance)}`);
        const action = this._resourceActions[actionName];
        let actionParams = {};

        if (action === undefined) {
            throw `The action ${actionName} is not configured for the resource ${this._resourceName}`;
        }

        let path = action.path;

        if (instance) {
            path = this.getUrlFromPathAndInstance(path, instance);

            if (["post", "put"].includes(action.verb)) {
                each(params, (param, name) => {
                    const found = this._resourceFields.find(field => field.name === name);

                    if (found === undefined) {
                        actionParams[name] = param;
                    }
                });

                this._resourceFields.forEach(field => {
                    if (action.verb === 'post' && !field.create_with) {
                        return;
                    }

                    let param = get(params, field.name);

                    if (param) {
                        if (field.collapse_on_store) {
                            param = field.multiple ?
                                param.map(o => get(o, last(field.id_key))) :
                                param[last(field.id_key)];

                        }
                    }

                    actionParams[field.name] = param ? param : '';
                });
            }
        } else {
            actionParams = params;
        }

        if (action.verb === 'get') {
            actionParams = { params: actionParams };
        }

        return axios[action.verb](path, actionParams)
            .catch(error => {
                let uncaught = true;

                if (get(error, 'response.status') === 401) {
                    uncaught = false;
                    window.location = window.location.origin + '/login';
                }

                if (get(error, 'response.status') === 422) {
                    toastr.error(`The form has missing or invalid fields`);
                }

                if (uncaught) {
                    throw error;
                }
            });
    }
}

export default Api;