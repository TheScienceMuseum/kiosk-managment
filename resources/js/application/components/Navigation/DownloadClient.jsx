import React, { Component } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    Button, Modal, ModalBody, ModalFooter, ModalHeader,
} from 'reactstrap';
import Field from '../Resource/Interface/Instance/Form/Field';

export default class DownloadClient extends Component {
    constructor(props) {
        super(props);

        this.state = {
            client: { label: 'Mac', value: 'mac' },
            showModal: false,
        };

        this.actionDownload = this.actionDownload.bind(this);
        this.handleClientDownloadChange = this.handleClientDownloadChange.bind(this);
        this.toggleModal = this.toggleModal.bind(this);
    }

    actionDownload() {
        const { client } = this.state;

        if (client) {
            window.location = `/download/client/${client.value}`;
        }
    }

    handleClientDownloadChange(field, value) {
        this.setState(prevState => ({
            ...prevState,
            client: value,
        }));
    }

    toggleModal() {
        this.setState(prevState => ({
            ...prevState,
            showModal: !prevState.showModal,
        }));
    }

    render() {
        const { showModal, client } = this.state;
        return (
            <div>
                <Modal isOpen={showModal} toggle={this.toggleModal} size="lg">
                    <ModalHeader toggle={this.toggleModal}>Download Kiosk Client</ModalHeader>
                    <ModalBody>
                        <Field
                            field={{
                                type: 'select',
                                help: 'Choose the operating system of the kiosk',
                                id_key: 'value',
                                options: [{
                                    label: 'Mac',
                                    value: 'mac',
                                }, {
                                    label: 'Windows',
                                    value: 'win',
                                }, {
                                    label: 'Linux',
                                    value: 'linux',
                                }],
                            }}
                            handleFieldChange={this.handleClientDownloadChange}
                            value={client}
                            stateful
                        />
                    </ModalBody>
                    <ModalFooter>
                        <Button color="primary" size="sm" onClick={this.actionDownload} disabled={!client}>Download</Button>
                        <Button color="primary" size="sm" onClick={this.toggleModal}>Close</Button>
                    </ModalFooter>
                </Modal>

                <a
                    className="nav-link"
                    onClick={this.toggleModal}
                    style={{
                        cursor: 'pointer',
                    }}
                >
                    <FontAwesomeIcon icon={['fal', 'download']} size="2x" fixedWidth />
                    <span className="nav-text">Download Client</span>
                </a>
            </div>
        );
    }
}
