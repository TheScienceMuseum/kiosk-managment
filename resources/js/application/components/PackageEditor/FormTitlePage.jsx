import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FormGroup, Input, Label} from "reactstrap";

class FormTitlePage extends Component {
    constructor(props) {
        super(props);

        this.handleFormChange = this.handleFormChange.bind(this);
    }

    handleFormChange(event) {
        const field = event.target.name;
        const value = event.target.value;

        this.props.handlePackageDataChange(field, value);
    }

    render() {
        return (
            <div>
                <FormGroup className={'row'}>
                    <Label className={'col-sm-2 col-form-label text-right my-auto'}>Title</Label>
                    <div className="col-sm-10">
                        <Input bsSize={'sm'} name={'content.titles.title'} value={this.props.data.title} onChange={this.handleFormChange} />
                    </div>
                </FormGroup>
                <FormGroup className={'row'}>
                    <Label className={'col-sm-2 col-form-label text-right my-auto'}>Title</Label>
                    <div className="col-sm-10">
                        <Input bsSize={'sm'} name={'content.titles.title'} value={this.props.data.title} onChange={this.handleFormChange} />
                    </div>
                </FormGroup>

            </div>
        );
    }
}

FormTitlePage.propTypes = {
    handlePackageDataChange: PropTypes.func.isRequired,
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
        title: PropTypes.string.isRequired,
        type: PropTypes.oneOf(["text"]).isRequired,
    }).isRequired,
};

export default FormTitlePage;
