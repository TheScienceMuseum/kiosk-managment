import { each, get, set } from 'lodash';

export default (validationErrors) => {
    const errors = {};

    if (validationErrors) {
        each(validationErrors, (message, path) => {
            set(errors, path, message);
        });
    }

    return {
        has: (path) => !!get(errors, path),
        get: (path) => get(errors, path),
    };
};
