import React, {Component} from 'react';
import PropTypes from 'prop-types';
import moment from 'moment';
import Text from "./Text";

class TimeAgo extends Component {
    constructor(props) {
        super(props);

        this.state = {
            value: this.props.defaultValue ? moment(this.props.defaultValue).fromNow() : 'Never',
        }
    }

    componentWillReceiveProps(nextProps, nextContext) {
        this.setState(prevState => ({
            ...prevState,
            value: nextProps.defaultValue ? moment(nextProps.defaultValue).fromNow() : 'Never',
        }));
    }

    render() {
        return (
            <Text defaultValue={this.state.value}
                  handleFieldChange={this.props.handleFieldChange}
                  field={this.props.field}
                  key={this.props.field.name}
                  disabled={this.props.field.readonly}
            />
        );
    }
}

TimeAgo.propTypes = {};

export default TimeAgo;
