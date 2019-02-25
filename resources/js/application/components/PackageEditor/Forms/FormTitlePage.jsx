import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FormGroup, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";

export default class FormTitlePage extends Component {
    static propTypes = {
        packageId: PropTypes.string.isRequired,
        packageVersionId: PropTypes.string.isRequired,
        handlePackageDataChange: PropTypes.func.isRequired,
        data: PropTypes.shape({
            data: PropTypes.shape({
                galleryName: PropTypes.string.isRequired,
                image: PropTypes.oneOfType([
                    PropTypes.string,
                    PropTypes.shape({
                        imageLandscape: PropTypes.string,
                        imagePortrait: PropTypes.string,
                        imageSource: PropTypes.string,
                        imageThumbnail: PropTypes.string,
                        nameText: PropTypes.string,
                        sourceText: PropTypes.string,
                    }),
                ]),
                attractorImage: PropTypes.oneOfType([
                    PropTypes.string,
                    PropTypes.shape({
                        imageLandscape: PropTypes.string,
                        imagePortrait: PropTypes.string,
                        imageSource: PropTypes.string,
                        imageThumbnail: PropTypes.string,
                        nameText: PropTypes.string,
                        sourceText: PropTypes.string,
                    }),
                ]),
                title: PropTypes.string.isRequired,
                type: PropTypes.oneOf(["text"]).isRequired,
            }).isRequired,
        }).isRequired,
    };

    constructor(props) {
        super(props);

        this.handleBSFormChange = this.handleBSFormChange.bind(this);
        this.handleFormChange   = this.handleFormChange.bind(this);
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
                               name={'content.titles.title'}
                               value={this.props.data.data.title}
                               onChange={this.handleBSFormChange}
                        />
                </FormGroup>
                <FormGroup>
                    <Label>Title Image</Label>
                        <Asset name={'content.titles.image'}
                               value={this.props.data.data.image}
                               packageId={this.props.packageId}
                               packageVersionId={this.props.packageVersionId}
                               onChange={this.handleFormChange}
                               assetType={'titleImage'}
                        />
                </FormGroup>
                <FormGroup className={'mb-0'}>
                    <Label>Attractor</Label>
                        <Asset name={'content.titles.attractor'}
                               value={this.props.data.data.attractor}
                               packageId={this.props.packageId}
                               packageVersionId={this.props.packageVersionId}
                               onChange={this.handleFormChange}
                               assetType={'attractor'}
                        />
                </FormGroup>
            </div>
        );
    }
}
