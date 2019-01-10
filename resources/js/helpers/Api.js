import ApplicationSchema from '../../application-schema';
import axios from 'axios';
import {each, get, last} from "lodash";

class Api {
    _resourceActions = {};
    _resourceFields = {};
    _resourceName = '';

    constructor(resourceName) {
        if (! ApplicationSchema.resources[resourceName]) {
            throw `The resource ${resourceName} does not exist in the application-schema file.`
        }

        this._resourceName      = resourceName;
        this._resourceActions   = ApplicationSchema.resources[resourceName].actions;
        this._resourceFields    = ApplicationSchema.resources[resourceName].fields;
    }

    hasAction(actionName) {
        return this._resourceActions.hasOwnProperty(actionName);
    }

    getUrlFromPathAndInstance(path, instance) {
        let matches = path.match(/{[A-Za-z0-9]+}/g);

        if (matches && matches.length > 0) {
            if (instance === null) {
                throw `The required instance was not passed to the ${actionName} request for the resource ${this.props.resource}`;
            }

            each(matches, (match) => {
                let matchProp = match.replace('{', '').replace('}', '');
                if (false === instance.hasOwnProperty(matchProp)) {
                    throw `The instance passed to the API does not have the property ${matchProp}`;
                }

                path = path.replace(match, instance[matchProp]);
            });
        }

        return path;
    }

    request(actionName, params, instance = null) {
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
                    if (field.readonly) {
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

                    actionParams[field.name] = param ? param : null;
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

                if (uncaught) {
                    throw error;
                }
            });
    }
}

export default Api;