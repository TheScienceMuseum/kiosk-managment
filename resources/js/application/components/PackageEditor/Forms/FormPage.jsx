import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FormGroup, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";
import Select from "./Elements/Select";
import FormPageMixed from "./FormPageMixed";
import FormPageVideo from "./FormPageVideo";

class FormPage extends Component {
    _pageTypes = {
        mixed: FormPageMixed,
        video: FormPageVideo,
    };

    constructor(props) {
        super(props);

        this.getComponent   = this.getComponent.bind(this);
    }

    getComponent(type) {
        const Component = this._pageTypes[type];

        return (
            <Component {...this.props} />
        )
    }

    render() {
        return (
            <div>
                {this.getComponent(this.props.data.data.type)}
            </div>
        );
    }
}

FormPage.propTypes = {

};

export default FormPage;
