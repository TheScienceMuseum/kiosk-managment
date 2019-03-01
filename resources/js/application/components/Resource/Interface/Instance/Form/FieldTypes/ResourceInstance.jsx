import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {InputGroup, InputGroupText} from "reactstrap";
import Select from "./Select";

export default class ResourceInstance extends Component {
    static propTypes = {
        defaultValue: PropTypes.oneOfType([PropTypes.object]),
        handleFieldChange: PropTypes.func.isRequired,
        field: PropTypes.object.isRequired,
        stateful: PropTypes.bool,
    };

    static defaultProps = {
        defaultValue: {label: 'None', value: ''},
        stateful: false,
    };

    render() {
        return (
            <InputGroup>
                {(this.props.field.readonly &&
                    <InputGroupText className={'flex-fill'}>
                        {this.props.defaultValue ?
                            this.props.field.label_key.map(section =>
                                _.has(this.props.defaultValue, section) ?
                                    `${_.get(this.props.defaultValue, section)}` :
                                    `${section}`
                            ) :
                            `No ${this.props.field.name} currently set`
                        }
                    </InputGroupText>
                ) || (
                    <Select defaultValue={this.props.defaultValue}
                            field={this.props.field}
                            handleFieldChange={this.props.handleFieldChange}
                            stateful={this.props.stateful}
                    />
                )}
            </InputGroup>
        );
    }
}
