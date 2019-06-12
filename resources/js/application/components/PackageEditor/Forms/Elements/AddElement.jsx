import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {
    Button,
    FormGroup,
    Input,
    Label,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader
} from "reactstrap";
import Select from "./Select";
import {ucwords} from "locutus/php/strings";
import {each} from 'lodash';

class AddElement extends Component {
    _types = {
        page: [{label: 'Select a Type', value: ''},{
            label: "Mixed",
            value: "mixed",
        }, {
            label: "Video",
            value: 'video',
        }, {
            label: 'Custom',
            value: 'custom'
        }],
        section: [{label: 'Select a Type', value: ''},{
            label: "Title",
            value: "title",
        }, {
            label: "Image",
            value: "image",
        }, {
            label: "Video",
            value: "video",
        }, {
            label: "Text with Image",
            value: "textImage",
        }],
    };
    constructor(props) {
        super(props);

        this.state = {
            type: '',
            title: '',
            types: this._types,
            showCustom: false,
            customPages: [],
        };

        this.flushState = this.flushState.bind(this);
        this.getCustomPages = this.getCustomPages.bind(this);
        this.handleFieldChange = this.handleFieldChange.bind(this);
        this.handleInputChange = this.handleInputChange.bind(this);

        if (props.elementType === 'page') {
            this.getCustomPages();
        }
    }

    flushState() {
        const {elementType, onElementAdded, onToggleModal} = this.props;

        onToggleModal();
        onElementAdded(elementType, this.state);

        this.setState(prevState => ({
            ...prevState,
            type: '',
            title: '',
        }));
    }

    handleFieldChange(field, value) {
        this.setState(prevState => ({
            ...prevState,
            [field]: value,
            showCustom:  field === 'custom-page'
                || (field === 'type' && value === 'custom')
                || (prevState.showCustom && field === 'type' && value === 'custom'),
        }));
    }

    handleInputChange(event) {
        const field = event.target.name;
        const value = event.target.value;

        this.handleFieldChange(field, value);
    }

    getCustomPages() {
        axios.get('/api/custom_page')
            .then((response) => {
                const customPages = response.data.data.map((datum) => ({
                    label: datum.name,
                    value: datum.id,
                }));

                customPages.unshift({label: 'Select a Custom Page', value: 0});

                this.setState(prevState => ({
                    ...prevState,
                    customPages,
                }));
            });
    }

    render() {
        const { elementType } = this.props;
        const { customPages, showCustom, title, types, type } = this.state;

        return (
            <Modal isOpen={this.props.showModal} toggle={this.props.onToggleModal}>
                <ModalHeader toggle={this.props.onToggleModal}>
                    Add {elementType}
                </ModalHeader>
                <ModalBody>
                    <FormGroup className={'row'}>
                        <Label className={'col-3 my-auto text-right'}>Type</Label>
                        <div className={'col-9'}>
                            <Select defaultValue={this.state.type}
                                    field={`type`}
                                    handleFieldChange={this.handleFieldChange}
                                    options={types[elementType]}
                            />
                        </div>
                    </FormGroup>
                    {!showCustom &&
                    <FormGroup className={'row'}>
                        <Label className={'col-3 my-auto text-right'}>
                            {ucwords(elementType)} Title
                        </Label>
                        <div className={'col-9'}>
                            <Input
                                name={'title'}
                                value={title}
                                onChange={this.handleInputChange}
                            />
                        </div>
                    </FormGroup>
                    }
                    {showCustom &&
                    <FormGroup className={'row'}>
                        <Label className={'col-3 my-auto text-right'}>Custom Page</Label>
                        <div className={'col-9'}>
                            <Select defaultValue={0}
                                    field={`custom-page`}
                                    handleFieldChange={this.handleFieldChange}
                                    options={customPages}
                            />
                        </div>
                    </FormGroup>
                    }
                </ModalBody>
                <ModalFooter>
                    <Button color={'primary'} size={'sm'} onClick={this.flushState}>Add</Button>
                </ModalFooter>
            </Modal>
        );
    }
}

AddElement.propTypes = {
    showModal: PropTypes.bool.isRequired,
    onToggleModal: PropTypes.func.isRequired,
    elementType: PropTypes.oneOf(['section', 'page']).isRequired,
    onElementAdded: PropTypes.func.isRequired,
};

export default AddElement;
