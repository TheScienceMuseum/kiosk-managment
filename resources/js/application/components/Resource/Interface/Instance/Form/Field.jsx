import React, {Component} from 'react';
import PropTypes from 'prop-types';

import {FormGroup, FormText, Input, Label} from "reactstrap";
import {ucwords} from "locutus/php/strings";

import Text from "./FieldTypes/Text";
import Select from "./FieldTypes/Select";
import TimeAgo from "./FieldTypes/TimeAgo";
import ResourceInstance from "./FieldTypes/ResourceInstance";
import ResourceCollection from "./FieldTypes/ResourceCollection";

class Field extends Component {
    fieldMappings = {
        text: Text,
        select: Select,
        time_ago: TimeAgo,
        resource_instance: ResourceInstance,
        resource_collection: ResourceCollection,
    };

    largeFields = [
        'resource_collection',
    ];

    getFieldComponent() {
        if (this.fieldMappings.hasOwnProperty(this.props.field.type)) {
            let Component = this.fieldMappings[this.props.field.type];
            return (
                <Component defaultValue={this.props.value}
                           disabled={this.props.field.readonly}
                           field={this.props.field}
                           fieldErrors={this.props.fieldErrors}
                           handleFieldChange={this.props.handleFieldChange}
                           history={this.props.history}
                           isCreate={this.props.isCreate}
                           key={this.props.field.name}
                           location={this.props.location}
                           stateful={this.props.stateful}
                />
            );
        }
    }

    render() {
        return (
            <FormGroup className={'row'}>
                <Label className={`col-sm-2 col-form-label text-right ${this.largeFields.includes(this.props.field.type) ? 'mt-2' : 'my-auto'}`}>
                    {this.props.field.label ||
                        ucwords(this.props.field.name.replace(/_at$/, '').replace(/_/g, " "))
                    }
                </Label>
                <div className={'col-sm-10'}>
                    {(this.fieldMappings.hasOwnProperty(this.props.field.type) &&
                        this.getFieldComponent()
                    ) || (
                        <Input invalid
                               readOnly
                               value={`No ResourceInstanceField of type ${this.props.field.type} exists.`}
                        />
                    )}
                </div>

                {this.props.fieldErrors &&
                    this.props.fieldErrors.map((error, index) =>
                        <FormText key={`errors-${this.props.field.name}-${index}`}
                                  className={'offset-sm-2 col-sm-10'}
                                  color={'danger'}
                        >
                            {error}
                        </FormText>
                )}

                {this.props.field.help &&
                    <FormText className={'offset-sm-2 col-sm-10'}>
                        {this.props.field.help}
                    </FormText>
                }
            </FormGroup>
        )
    }
}

Field.propTypes = {
    field: PropTypes.object.isRequired,
    fieldErrors: PropTypes.array,
    value: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.array,
        PropTypes.object,
        PropTypes.number,
    ]),
    handleFieldChange: PropTypes.func.isRequired,
    isCreate: PropTypes.bool,
    stateful: PropTypes.bool,
};

export default Field;
