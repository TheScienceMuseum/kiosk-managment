import React, { Component } from 'react';
import PropTypes from 'prop-types';
import ReactSelect from 'react-select';
import { has, flatten } from 'lodash';

class Select extends Component {
    constructor(props) {
        super(props);

        this.state = {
            options: props.options ? props.options : [],
        };

        this.getDefaultValue = this.getDefaultValue.bind(this);
        this.handleFieldChange = this.handleFieldChange.bind(this);
    }

    getDefaultValue() {
        const { options } = this.state;
        const { defaultValue } = this.props;
        const groups = options.filter(option => has(option, 'options'));
        const hasOptionGroups = groups.length > 0;

        if (hasOptionGroups) {
            const allOptions = flatten(groups.map(group => group.options));

            return {
                label: allOptions.find(el => el.value === defaultValue).label,
                value: defaultValue,
            };
        }

        return {
            label: options.find(el => el.value === defaultValue).label,
            value: defaultValue,
        };
    }

    handleFieldChange(value) {
        const { handleFieldChange, field } = this.props;
        handleFieldChange(field, value.value);
    }

    render() {
        const { field } = this.props;
        const { options } = this.state;
        return (
            <ReactSelect name={field}
                         onChange={this.handleFieldChange}
                         defaultValue={this.getDefaultValue()}
                         options={options}
                         styles={{
                             container: (base) => ({
                                 ...base,
                                 flexGrow: 1,
                                 flexShrink: 1,
                                 flexBasis: 'auto',
                             }),
                             control: (base) => ({
                                 ...base,
                                 backgroundColor: '#f7f7f9',
                                 border: 'none',
                                 borderRadius: 'none',
                             }),
                             dropdownIndicator: (base) => ({
                                 ...base,
                                 paddingTop: 0,
                                 paddingBottom: 0,
                             }),
                             clearIndicator: (base) => ({
                                 ...base,
                                 paddingTop: 0,
                                 paddingBottom: 0,
                             }),
                             valueContainer: (base) => ({
                                 ...base,
                                 paddingLeft: '1rem',
                                 paddingRight: '1rem',
                             }),
                         }}
            />
        );
    }
}

Select.propTypes = {
    defaultValue: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.array,
        PropTypes.object,
        PropTypes.number,
    ]).isRequired,
    handleFieldChange: PropTypes.func.isRequired,
    field: PropTypes.string.isRequired,
    options: PropTypes.array,
};

export default Select;
