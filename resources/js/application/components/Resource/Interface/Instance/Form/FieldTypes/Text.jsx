import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Input} from "reactstrap";

export default class Text extends Component {
    static propTypes = {
        defaultValue: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
        handleFieldChange: PropTypes.func.isRequired,
        field: PropTypes.object.isRequired,
        stateful: PropTypes.bool,
    };

    static defaultProps = {
        defaultValue: '',
        stateful: false,
    };

    constructor(props) {
        super(props);

        if (this.props.stateful) {
            this.state = { value: this.props.defaultValue };
        }

        this.fieldIsEditable = this.fieldIsEditable.bind(this);
        this.handleFieldChange = this.handleFieldChange.bind(this);
    }

    fieldIsEditable() {
        if (this.props.isCreate) {
            return this.props.field.create_with || ! this.props.field.readonly;
        }
        return ! this.props.field.readonly;
    }

    handleFieldChange(event) {
        const value = event.target.value;

        this.props.handleFieldChange(this.props.field, value);
        if (this.props.stateful) {
            this.setState(prevState => ({
                ...prevState,
                value,
            }))
        }
    }

    render() {
        return (
            <Input name={this.props.field.name}
                   value={this.props.stateful ? this.state.value : this.props.defaultValue}
                   onChange={this.handleFieldChange}
                   readOnly={! this.fieldIsEditable()}
                   autoComplete={"false"}
            />
        );
    }
}
