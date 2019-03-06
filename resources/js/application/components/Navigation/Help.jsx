import React, {Component, Fragment} from 'react';
import PropTypes from 'prop-types';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {Converter} from 'showdown';
import confirm from "reactstrap-confirm";

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
        };

        this.getHelpForContext = this.getHelpForContext.bind(this);
        this.displayHelp = this.displayHelp.bind(this);
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
            .then(response => response.data.data.content)
            .then(content => {
                this.setState(prevState => ({
                    ...prevState,
                    content,
                }));
            });
    }

    displayHelp() {
        const converter = new Converter();
        const convertedMarkdown = converter.makeHtml(this.state.content);

        confirm({
            className: 'modal-lg',
            title: 'Help',
            message: (
                <Fragment>
                    <div dangerouslySetInnerHTML={{__html: convertedMarkdown}} />
                </Fragment>
            ),
            cancelText: User.can('edit all help topics') ? 'Edit Help Text' : null,
            confirmText: 'Close',
        });
    }

    render() {
        return (
            <a className="nav-link"
               onClick={this.displayHelp}
               style={{
                   cursor: 'pointer',
               }}
            >
                <FontAwesomeIcon icon={['fal', 'question-circle']} size={'2x'} fixedWidth />
                <span className={'nav-text'}>Help</span>
            </a>
        );
    }
}
