import ApplicationSchema from '../../../../../../application-schema';
import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Input} from "reactstrap";
import {ucwords} from "locutus/php/strings";
import Select from "react-select";
import {get, has, keys, last, sortBy} from "lodash";
import ReactSelect from "../Instance/Form/FieldTypes/Select";

class ResourceListHeaderSelect extends Component {
    constructor(props) {
        super(props);

        this.state = {
            resource: [],
        };

        this.handleChange           = this.handleChange.bind(this);
        this.requestResource        = this.requestResource.bind(this);
        this.mapOptionToSelect      = this.mapOptionToSelect.bind(this);
        this.getOptionsFromResponse = this.getOptionsFromResponse.bind(this);
    }

    componentDidMount() {
        if (this.props.options.resource !== undefined) {
            this.requestResource()
        } else {
            if (this.props.options.options !== undefined) {
                this.setState(prevState => ({
                    ...prevState,
                    resource: this.props.options.options,
                }));
            }
        }
    }

    handleChange(value) {
        this.props.handleResourceListParamsUpdate(this.props.options, value.value, () => {
            this.props.handleResourceListSearch();
        });
    }

    requestResource() {
        const resource = ApplicationSchema.resources[this.props.options.resource];

        axios.get(resource.actions.index.path)
            .then(response => {
                this.setState(prevState => ({
                    ...prevState,
                    resource: this.getOptionsFromResponse(response.data.data),
                }));
            })
    }

    mapOptionToSelect(option) {
        let label = 'none';
        let value = '';

        if (JSON.stringify(sortBy(keys(option))) === JSON.stringify(['label', 'value'])) {
            return option;
        }

        if (option) {
            if (option.constructor === Object) {
                let valueKey = this.props.options.id_key;
                if (this.props.options.id_key.constructor === Array) {
                    valueKey = last(this.props.options.id_key);
                }

                value = option[valueKey] ? option[valueKey] : "";

                label = this.props.options.label_key.map(key => has(option, key) ? get(option, key) : key).join(' ');
            }

            if (option.constructor === String) {
                value = option;
                label = option
            }
        }

        return {
            label: ucwords(label),
            value: value,
        };
    }

    getOptionsFromResponse(data) {
        const keys = this.props.options.id_key;

        if (keys && keys.constructor === Array && keys.length > 1) {
            let optionsData = [];

            data.forEach(datum => {
                keys.forEach((key, index, list) => {
                    if (index === list.length - 1) {
                        return;
                    }

                    const prop = get(datum, key);

                    if (! prop) {
                        return;
                    }

                    if (prop.constructor) {
                        if (prop.constructor === Array) {
                            optionsData = optionsData.concat(prop);
                        }

                        if (prop.constructor === Object || prop.constructor === String) {
                            optionsData.push(prop);
                        }
                    }
                });
            });

            data = optionsData;
        }

        data.unshift({
            label: 'None',
            value: '',
        });

        return data;
    }

    render() {
        return (
            <th>
                <Select name={this.props.options.name}
                        onChange={this.handleChange}
                        defaultValue={this.mapOptionToSelect(this.props.initialValue)}
                        options={this.state.resource.map(this.mapOptionToSelect)}
                        isMulti={false}
                        placeholder={ucwords(this.props.options.name)}
                        styles={{
                            control: (base) => ({
                                ...base,
                                minHeight: 34,
                                minWidth: 80,
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
                        }}
                />
            </th>
        );
    }
}

ResourceListHeaderSelect.propTypes = {
    handleResourceListParamsUpdate: PropTypes.func.isRequired,
    handleResourceListSearch: PropTypes.func.isRequired,
    initialValue: PropTypes.string,
    options: PropTypes.object.isRequired,
};

export default ResourceListHeaderSelect;
