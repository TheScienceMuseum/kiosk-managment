import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FormGroup, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";
import Select from "./Elements/Select";

export default class SectionTextImage extends Component {
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
        const { aspectRatio, data, packageId, packageVersionId } = this.props;
        const shouldDisplayYear = data.parentPage.type === 'timeline';

        return (
            <div>
                <FormGroup>
                    <Label>Title</Label>
                    <Input bsSize={'sm'}
                           name={'title'}
                           value={data.data.title}
                           onChange={this.handleBSFormChange}
                           maxLength={40}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>Content</Label>
                    <Input bsSize={'sm'}
                           name={'content'}
                           value={data.data.content}
                           onChange={this.handleBSFormChange}
                           type={'textarea'}
                           rows={10}
                           maxLength={1080}
                    />
                </FormGroup>

                {shouldDisplayYear &&
                <FormGroup>
                    <Label>Year</Label>
                    <Input bsSize={'sm'}
                           name={'date'}
                           value={data.data.date}
                           onChange={this.handleBSFormChange}
                           maxLength={40}
                    />
                </FormGroup>
                }

                {aspectRatio === '16:9' &&
                <FormGroup>
                    <Label>Layout</Label>
                    <Select className={'col-sm-9'}
                            defaultValue={data.data.layout}
                            field={'layout'}
                            handleFieldChange={this.handleFormChange}
                            options={[{
                                label: "Left",
                                value: "left",
                            }, {
                                label: "Right",
                                value: "right",
                            }]}
                    />
                </FormGroup>
                }

                <FormGroup>
                    <Label>Audio</Label>
                    <Asset name={'audio'}
                           value={data.data.audio}
                           packageId={packageId}
                           packageVersionId={packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'audio'}
                           aspectRatio={aspectRatio}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>Image</Label>
                    <Asset name={'asset'}
                           value={data.data.asset}
                           packageId={packageId}
                           packageVersionId={packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'sectionTextImage'}
                           aspectRatio={aspectRatio}
                    />
                </FormGroup>
            </div>
        );
    }
}
