import React, {Component, Fragment} from 'react';
import PropTypes from 'prop-types';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import { Converter } from 'showdown';
import {Button, Modal, ModalBody, ModalFooter, ModalHeader} from "reactstrap";
import { Editor } from '@toast-ui/react-editor';

import 'codemirror/lib/codemirror.css';
import 'tui-editor/dist/tui-editor.min.css';
import 'tui-editor/dist/tui-editor-contents.min.css';

export default class Help extends Component {
    static propTypes = {
        location: PropTypes.shape({
            pathname: PropTypes.string.isRequired,
        }).isRequired,
    };

    constructor(props) {
        super(props);

        this.state = {
            content: '',
            location: this.props.location,
            edit: false,
            show: false,
            editTab: 'write',
            id: null,
        };

        this.converter = new Converter({
            tables: true,
            simplifiedAutoLink: true,
            strikethrough: true,
            tasklists: true,
        });

        this.editorRef = React.createRef();

        this.getHelpForContext = this.getHelpForContext.bind(this);
        this.toggleModal = this.toggleModal.bind(this);
        this.saveChanges = this.saveChanges.bind(this);
    }

    static getDerivedStateFromProps(nextProps, prevState) {
        if (nextProps.location.pathname !== prevState.location.pathname) {
            return {...prevState, location: nextProps.location};
        }
        return null;
    }

    componentDidMount() {
        this.getHelpForContext();
    }

    componentDidUpdate(prevProps, prevState) {
        if (this.props.location.pathname !== prevProps.location.pathname) {
            this.getHelpForContext();
        }
    }

    getHelpForContext() {
        axios.get('/api/help/context', {params: {context: this.state.location.pathname}})
            .then(response => response.data.data)
            .then(data => {
                const {content, id} = data;
                this.setState(prevState => ({
                    ...prevState,
                    content,
                    id,
                }));
            });
    }

    saveChanges () {
        const { id } = this.state;
        const content = this.editorRef.current.getInstance().getMarkdown();

        axios.put(`/api/help/${id}`, {content})
            .then(response => response.data.data)
            .then(data => {
                const {content, id} = data;
                this.setState(prevState => ({
                    ...prevState,
                    content,
                    id,
                }));
                this.toggleModal('edit')();
                this.toggleModal('show')();
            });
    }

    toggleModal(type) {
        return () => {
            this.setState(prevState => ({
                ...prevState,
                [type]: ! prevState[type],
            }))
        }
    }

    render() {
        return (
            <div>
                <Modal isOpen={this.state.edit} toggle={this.toggleModal('edit')} size={'lg'}>
                    <ModalHeader toggle={this.toggleModal('edit')}>Help</ModalHeader>
                    <ModalBody>
                        <div className={'container'}>
                            <Editor
                                initialValue={this.state.content}
                                initialEditType="wysiwyg"
                                previewStyle="vertical"
                                height="400px"
                                usageStatistics={false}
                                ref={this.editorRef}
                            />
                        </div>
                    </ModalBody>
                    <ModalFooter>
                        <Button color="secondary" size={'sm'} onClick={() => {this.toggleModal('edit')(); this.getHelpForContext();}}>Cancel</Button>
                        <Button color="primary" size={'sm'} onClick={this.saveChanges}>Save</Button>
                    </ModalFooter>
                </Modal>

                <Modal isOpen={this.state.show} toggle={this.toggleModal('show')} size={'lg'}>
                    <ModalHeader toggle={this.toggleModal('show')}>Help</ModalHeader>
                    <ModalBody dangerouslySetInnerHTML={{__html: this.converter.makeHtml(this.state.content)}} />
                    <ModalFooter>
                        {User.can('edit all help topics') &&
                        <Button color="secondary" size={'sm'} onClick={() => {this.toggleModal('show')(); this.toggleModal('edit')();}}>Edit</Button>
                        }
                        <Button color="primary" size={'sm'} onClick={this.toggleModal('show')}>Close</Button>
                    </ModalFooter>
                </Modal>

                <a className="nav-link"
                   onClick={this.toggleModal('show')}
                   style={{
                       cursor: 'pointer',
                   }}
                >
                    <FontAwesomeIcon icon={['fal', 'question-circle']} size={'2x'} fixedWidth />
                    <span className={'nav-text'}>Help</span>
                </a>
            </div>
        );
    }
}
