import React, {Component} from 'react';
import PropTypes from 'prop-types';
import ReactSelect from "react-select";
import {ucwords} from "locutus/php/strings";
import {get, has, keys, last, sortBy, find} from "lodash";

class Select extends Component {
    constructor(props) {
        super(props);

        this.state = {
            options: props.options ? props.options : [],
        };

        this.handleFieldChange = this.handleFieldChange.bind(this);
    }

    handleFieldChange(value) {
        this.props.handleFieldChange(this.props.field, value.value);
    }

    render() {
        return (
            <ReactSelect name={'roles'}
                         onChange={this.handleFieldChange}
                         defaultValue={{
                             label: this.state.options.find(el => el.value === this.props.defaultValue).label,
                             value: this.props.defaultValue,
                         }}
                         options={this.state.options}
                         styles={{
                             container: (base) => ({
                                 ...base,
                                 flexGrow: 1,
                                 flexShrink: 1,
                                 flexBasis: 'auto',
                             }),
                             control: (base) => ({
                                 ...base,
                                 minHeight: 32,
                                 backgroundColor: '#f7f7f9',
                                 border: 'none',
                                 borderRadius: 'none',
                                 fontSize: '0.7875rem',
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
                             })
                         }}
            />
        );
    }
}

Select.propTypes = {
    defaultValue: PropTypes.oneOfType([PropTypes.string, PropTypes.array, PropTypes.object]).isRequired,
    handleFieldChange: PropTypes.func.isRequired,
    field: PropTypes.string.isRequired,
};

export default Select;
