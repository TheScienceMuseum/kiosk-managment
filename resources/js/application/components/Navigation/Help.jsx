import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {NavLink} from "react-router-dom";

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
    }

    static getDerivedStateFromProps(nextProps, prevState) {
        if (nextProps.location.pathname !== prevState.location.pathname) {
            return {...prevState, location: nextProps.location};
        }
        return null;
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

    render() {
        return (
            <a className="nav-link"
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
