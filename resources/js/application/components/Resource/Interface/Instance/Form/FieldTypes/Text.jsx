import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FormGroup, Input, Label} from "reactstrap";
import {ucwords} from "locutus/php/strings";

class Text extends Component {
    constructor(props) {
        super(props);

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
    }

    render() {
        return (
            <Input name={this.props.field.name}
                   value={this.props.defaultValue ? this.props.defaultValue : ''}
                   onChange={this.handleFieldChange}
                   readOnly={! this.fieldIsEditable()}
                   autoComplete={"false"}
            />
        );
    }
}

Text.propTypes = {
    defaultValue: PropTypes.string,
    handleFieldChange: PropTypes.func.isRequired,
    field: PropTypes.object.isRequired,
};

export default Text;
