import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FormGroup, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";

class SectionTitle extends Component {
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
                    <Label>Subtitle</Label>
                    <Input bsSize={'sm'}
                           name={'subtitle'}
                           value={this.props.data.data.subtitle}
                           onChange={this.handleBSFormChange}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>Title Image</Label>
                    <Asset name={'asset'}
                           value={this.props.data.data.asset}
                           packageId={this.props.packageId}
                           packageVersionId={this.props.packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'titleImage'}
                    />
                </FormGroup>
            </div>
        );
    }
}

SectionTitle.propTypes = {};

export default SectionTitle;
