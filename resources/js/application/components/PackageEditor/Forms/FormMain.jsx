import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Alert, Button, Card, CardBody, CardHeader, Col, FormGroup, Input, Label, Row} from 'reactstrap';
import Select from "./Elements/Select";
import File from "./Elements/File";
import Types from '../PropTypes';

class FormMain extends Component {
    constructor(props) {
        super(props);

        this.handleChange     = this.handleChange.bind(this);
        this.handleFormChange = this.handleFormChange.bind(this);
    }

    handleChange(field, value) {
        this.props.handlePackageDataChange(field, value);
    }

    handleFormChange(event) {
        const field = event.target.name;
        const value = event.target.value;

        this.handleChange(field, value);
    }

    render() {
        return (
            <div>
                <FormGroup className={'row'}>
                    <Label sm={3} className={'my-auto'}>Gallery</Label>
                    <Select className={'col-sm-9'}
                            defaultValue={"medicine"}
                            field={'data.content.titles.galleryName'}
                            handleFieldChange={this.handleChange}
                            options={[{
                                label: "The Medicine Gallery",
                                value: "medicine",
                            }]}
                    />
                </FormGroup>
            </div>
        );
    }
}

FormMain.propTypes = {
    handlePackageDataChange: PropTypes.func.isRequired,
    data: PropTypes.shape({
        background_color: PropTypes.string,
        display: PropTypes.oneOf(["standalone"]),
        name: PropTypes.string.isRequired,
        short_name: PropTypes.string.isRequired,
        start_url: PropTypes.string,
        version: PropTypes.number,
        theme_color: PropTypes.string,
        content: PropTypes.shape({
            titles: PropTypes.shape({
                galleryName: PropTypes.string.isRequired,
                image: Types.asset,
                title: PropTypes.string.isRequired,
                type: PropTypes.oneOf(["text"]).isRequired,
            }).isRequired,
            contents: PropTypes.arrayOf(PropTypes.shape({
                articleID: PropTypes.string,
                subpages: PropTypes.arrayOf(PropTypes.shape({
                    image: Types.image,
                    pageID: PropTypes.string,
                    subtitle: PropTypes.string,
                    title: PropTypes.string,
                    type: PropTypes.oneOf(["title", "textImage", "image"]),
                    layout: PropTypes.oneOf(["left", "right"]),
                })),
                title: PropTypes.string,
                titleImage: Types.asset,
                type: PropTypes.oneOf(["mixed", "video"]),
                videoSrc: Types.asset,
            })),
        }),
    }).isRequired,
};

export default FormMain;
