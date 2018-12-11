import _ from 'lodash';

class Trans {
    constructor () {
        this.lang = document.documentElement.lang;
        this.translations = window.application_config.translations;
    }
    get(path) {
        _.get(this.translations, path, path);
    }
}

export default new Trans();