import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FormGroup, Input, Label} from "reactstrap";
import Asset from "./Form/Asset";
import Select from "./Form/Select";

class FormPageVideo extends Component {
    constructor(props) {
        super(props);

        this.handleFormChange   = this.handleFormChange.bind(this);
        this.handleBSFormChange = this.handleBSFormChange.bind(this);
    }

    handleFormChange(field, value) {
        this.props.handlePackageDataChange(field, value);
    }

    handleBSFormChange(event) {
        const field = event.target.name;
        const value = event.target.value;

        this.props.handlePackageDataChange(field, value);
    }
    render() {
        return (
            <div>
                <FormGroup>
                    <Label>Title</Label>
                    <Input bsSize={'sm'}
                           name={'title'}
                           value={this.props.data.data.title}
                           onChange={this.handleBSFormChange}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>Video</Label>
                    <Asset name={'videoSrc'}
                           value={this.props.data.data.videoSrc}
                           packageId={this.props.packageId}
                           packageVersionId={this.props.packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'contentVideo'}
                    />
                </FormGroup>
            </div>
        );
    }
}

FormPageVideo.propTypes = {

};

export default FormPageVideo;
