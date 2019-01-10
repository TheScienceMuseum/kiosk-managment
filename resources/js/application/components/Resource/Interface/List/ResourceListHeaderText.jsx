import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Input} from "reactstrap";
import {ucwords} from "locutus/php/strings";

class ResourceListHeaderText extends Component {
    constructor(props) {
        super(props);

        this.getFieldPlaceholderText = this.getFieldPlaceholderText.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }

    handleChange(event) {
        const key_pressed = event.key;
        const value = event.target.value;

        this.props.handleResourceListParamsUpdate(this.props.options, value, () => {
            if (key_pressed === 'Enter') {
                this.props.handleResourceListSearch(event);
            }
        });
    }

    getFieldPlaceholderText() {
        return this.props.options.sub_fields ?
            `${this.props.options.name.replace(/_at$/, '').replace(/[_]/g, " ")} OR ${this.props.options.sub_fields.join(' OR ')}`.toUpperCase() :
            this.props.options.name.replace(/_at$/, '').replace(/[_]/g, " ").toUpperCase();
    }

    render() {
        return (
            <th>
                <Input bsSize={'sm'}
                       className={'my-auto'}
                       defaultValue={this.props.initialValue}
                       name={`filter[${this.props.options.name}]`}
                       onKeyUp={this.handleChange}
                       placeholder={ucwords(this.getFieldPlaceholderText())}
                />
            </th>
        );
    }
}

ResourceListHeaderText.propTypes = {
    handleResourceListParamsUpdate: PropTypes.func.isRequired,
    handleResourceListSearch: PropTypes.func.isRequired,
    initialValue: PropTypes.string,
    options: PropTypes.object.isRequired,
};

export default ResourceListHeaderText;
