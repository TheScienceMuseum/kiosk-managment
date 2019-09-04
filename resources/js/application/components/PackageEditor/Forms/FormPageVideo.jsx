import React, {Component} from 'react';
import {FormGroup, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";
import Validation from '../../../../helpers/PackageDataValidation';

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
        const {validationErrors} = this.props;

        const validation = Validation(validationErrors);

        return (
            <div>
                <FormGroup>
                    <Label>Title</Label>
                    <Input bsSize={'sm'}
                           name={'title'}
                           value={this.props.data.data.title}
                           onChange={this.handleBSFormChange}
                           maxLength={72}
                           invalid={validation.has(`content.contents[${this.props.data.pageIndex}].title`)}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>Subtitle</Label>
                    <Input bsSize={'sm'}
                           name={'subtitle'}
                           value={this.props.data.data.subtitle}
                           onChange={this.handleBSFormChange}
                           type={'textarea'}
                           rows={10}
                           maxLength={300}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>Video</Label>
                    <Asset name={'asset'}
                           value={this.props.data.data.asset}
                           packageId={this.props.packageId}
                           packageVersionId={this.props.packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'contentVideo'}
                           aspectRatio={this.props.aspectRatio}
                           invalid={validation.has(`content.contents[${this.props.data.pageIndex}].asset`)}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>Video Image</Label>
                    <Asset name={'titleImage'}
                           value={this.props.data.data.titleImage}
                           packageId={this.props.packageId}
                           packageVersionId={this.props.packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'contentVideoImage'}
                           aspectRatio={this.props.aspectRatio}
                           invalid={validation.has(`content.contents[${this.props.data.pageIndex}].titleImage`)}
                    />
                </FormGroup>
            </div>
        );
    }
}

FormPageVideo.propTypes = {

};

export default FormPageVideo;
