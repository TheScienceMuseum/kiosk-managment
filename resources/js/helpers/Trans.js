import _ from 'lodash';
import applicationSchema from '../../application-schema';

class Trans {
    constructor() {
        this.lang = document.documentElement.lang;
        this.translations = applicationSchema.language;
    }

    get(path) {
        _.get(this.translations, `${this.lang}.${path}`, path);
    }
}

export default new Trans();
