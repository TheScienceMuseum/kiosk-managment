import axios from 'axios';
import _ from 'lodash';
import application_schema from '../../application-schema.json';

export default class Api {
    constructor(resource) {
        this._resource = resource;
        this._schema = _.get(application_schema.resources, resource);

        _.each(this._schema, (options, action) => {
            let verb = options.verb;

            if (false === ["get", "post", "put", "delete"].includes(verb)) {
                throw Error(`An invalid verb was used: ${verb}`);
            }

            this[action] = (params = {}, instance = null) => {
                let requestOptions = (verb === "get") ? { params: params } : params;

                let path = options.path;
                let instancePropsRegex = /{[A-Za-z0-9]+}/g;
                let matches = path.match(instancePropsRegex);

                if (matches && matches.length > 0) {
                    if (instance === null) {
                        throw TypeError('The required instance was not passed to the API request');
                    }

                    _.each(matches, (match) => {
                        let matchProp = match.replace('{', '').replace('}', '');
                        console.log(`matching ${match}`);
                        if (false === instance.hasOwnProperty(matchProp)) {
                            throw TypeError(`The instance passed to the API does not have the property ${matchProp}`);
                        }

                        path = path.replace(match, instance[matchProp]);
                    });
                }


                return axios[verb](path, requestOptions)
                    .then(response => response.data);
            };
        });
    }

    /**
     * Placeholder methods for the api
     */
    index() {}
    show() {}
    store() {}
    update() {}
    destroy() {}
}
