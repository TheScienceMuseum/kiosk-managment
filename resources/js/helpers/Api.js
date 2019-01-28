import ApplicationSchema from '../../application-schema';
import axios from 'axios';
import {each, get, has, last} from "lodash";

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