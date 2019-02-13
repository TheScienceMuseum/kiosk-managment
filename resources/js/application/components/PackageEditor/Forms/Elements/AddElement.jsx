import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {
    Button, FormGroup, Input, Label,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader
} from "reactstrap";
import Select from "./Select";
import {ucwords} from "locutus/php/strings";

class AddElement extends Component {
    _types = {
        page: [{label: 'Select a Type', value: ''},{
            label: "Mixed",
            value: "mixed",
        }, {
            label: "Video",
            value: 'video',
        }],
        section: [{label: 'Select a Type', value: ''},{
            label: "Title",
            value: "title",
        }, {
            label: "Image",
            value: "image",
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
        };

        this.flushState = this.flushState.bind(this);
        this.handleFieldChange = this.handleFieldChange.bind(this);
        this.handleInputChange = this.handleInputChange.bind(this);
    }

    flushState() {
        this.props.onToggleModal();
        this.props.onElementAdded(this.props.type, this.state);
        this.setState(prevState => ({
            type: '',
            title: '',
        }));
    }

    handleFieldChange(field, value) {
        this.setState(prevState => ({
            ...prevState,
            [field]: value,
        }))
    }

    handleInputChange(event) {
        const field = event.target.name;
        const value = event.target.value;

        this.handleFieldChange(field, value);
    }

    render() {
        return (
            <Modal isOpen={this.props.showModal} toggle={this.props.onToggleModal}>
                <ModalHeader toggle={this.props.onToggleModal}>
                    Add {this.props.type}
                </ModalHeader>
                <ModalBody>
                    <div>
                        <FormGroup className={'row'}>
                            <Label className={'col-3 my-auto text-right'}>Type</Label>
                            <div className={'col-9'}>
                                <Select defaultValue={this.state.type}
                                        field={`type`}
                                        handleFieldChange={this.handleFieldChange}
                                        options={this._types[this.props.type]}
                                />
                            </div>
                        </FormGroup>
                    </div>
                    <FormGroup className={'row'}>
                        <Label className={'col-3 my-auto text-right'}>{ucwords(this.props.type)} Title</Label>
                        <div className={'col-9'}>
                            <Input name={'title'} value={this.state.title} onChange={this.handleInputChange}/>
                        </div>
                    </FormGroup>
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
    type: PropTypes.oneOf(['section', 'page']).isRequired,
    onElementAdded: PropTypes.func.isRequired,
};

export default AddElement;
