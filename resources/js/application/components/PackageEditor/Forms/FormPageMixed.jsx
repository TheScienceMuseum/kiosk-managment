import React, {Component} from 'react';
import {FormGroup, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";
import Validation from '../../../../helpers/PackageDataValidation';

class FormPageMixed extends Component {
    constructor(props) {
        super(props);

        this.handleFormChange = this.handleFormChange.bind(this);
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
                    <Label>Title Image</Label>
                    <Asset name={'titleImage'}
                           value={this.props.data.data.titleImage}
                           packageId={this.props.packageId}
                           packageVersionId={this.props.packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'titleImage'}
                           aspectRatio={this.props.aspectRatio}
                           invalid={validation.has(`content.contents[${this.props.data.pageIndex}].titleImage`)}
                    />
                </FormGroup>
            </div>
        );
    }
}

FormPageMixed.propTypes = {};

export default FormPageMixed;
