import React, {Component} from 'react';
import {FormGroup, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";
import Select from "./Elements/Select";
import Validation from '../../../../helpers/PackageDataValidation';

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
        const { data, validationErrors } = this.props;
        const shouldDisplayYear = data.parentPage.type === 'timeline';

        const validation = Validation(validationErrors);
        return (
            <div>
                <FormGroup>
                    <Label>Title</Label>
                    <Input bsSize={'sm'}
                           name={'title'}
                           value={this.props.data.data.title}
                           onChange={this.handleBSFormChange}
                           maxLength={40}
                    />
                </FormGroup>

                {shouldDisplayYear &&
                <FormGroup>
                    <Label>Year</Label>
                    <Input bsSize={'sm'}
                           name={'date'}
                           value={this.props.data.data.date}
                           onChange={this.handleBSFormChange}
                           maxLength={40}
                    />
                </FormGroup>
                }

                <FormGroup>
                    <Label>Content</Label>
                    <Input bsSize={'sm'}
                           name={'content'}
                           value={this.props.data.data.content}
                           onChange={this.handleBSFormChange}
                           type={'textarea'}
                           rows={10}
                           maxLength={1080}
                    />
                </FormGroup>

                {this.props.aspectRatio === '16:9' &&
                <FormGroup>
                    <Label>Layout</Label>
                    <Select className={'col-sm-9'}
                            defaultValue={this.props.data.data.layout}
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
                    <Label>Image</Label>
                    <Asset name={'asset'}
                           value={this.props.data.data.asset}
                           packageId={this.props.packageId}
                           packageVersionId={this.props.packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'sectionTextImage'}
                           aspectRatio={this.props.aspectRatio}
                           invalid={validation.has(`content.contents[${this.props.data.pageIndex}].subpages[${this.props.data.sectionIndex}].asset`)}
                    />
                </FormGroup>
            </div>
        );
    }
}
