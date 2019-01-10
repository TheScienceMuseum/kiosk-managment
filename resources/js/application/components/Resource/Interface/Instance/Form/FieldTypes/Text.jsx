import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {FormGroup, Input, Label} from "reactstrap";
import {ucwords} from "locutus/php/strings";

class Text extends Component {
    constructor(props) {
        super(props);

        this.handleFieldChange = this.handleFieldChange.bind(this);
    }

    handleFieldChange(event) {
        const value = event.target.value;

        this.props.handleFieldChange(this.props.field, value);
    }

    render() {
        return (
            <Input name={this.props.field.name}
                   value={this.props.defaultValue}
                   onChange={this.handleFieldChange}
                   disabled={this.props.field.readonly}
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
