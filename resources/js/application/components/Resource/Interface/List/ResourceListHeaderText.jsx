import React, {Component} from 'react';
import PropTypes from 'prop-types';
import { Button, Input, InputGroup, InputGroupAddon } from 'reactstrap';
import {ucwords} from "locutus/php/strings";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

class ResourceListHeaderText extends Component {
    constructor(props) {
        super(props);

        this.state = {
            value: props.initialValue || '',
        };

        this.getFieldPlaceholderText = this.getFieldPlaceholderText.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleReset = this.handleReset.bind(this);
    }

    componentWillReceiveProps(nextProps, nextContext) {
        let value = nextProps.initialValue;

        if (nextProps.initialValue === undefined) {
            value = '';
        }

        this.setState(prevState => ({
            ...prevState,
            value,
        }));
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

    handleReset(event) {
        this.props.handleResourceListParamsUpdate(this.props.options, '', () => {
            this.props.handleResourceListSearch(event);
        });
    }

    getFieldPlaceholderText() {
        return this.props.options.sub_fields ?
            `${this.props.options.name.replace(/_at$/, '').replace(/[_]/g, " ")} OR ${this.props.options.sub_fields.join(' OR ')}`.toUpperCase() :
            this.props.options.name.replace(/_at$/, '').replace(/[_]/g, " ").toUpperCase();
    }

    render() {
        const { options } = this.props;
        const { value } = this.state;

        return (
            <th>
                <InputGroup size={'sm'}>
                    <InputGroupAddon addonType={'prepend'}>
                        <Button disabled>
                            <FontAwesomeIcon icon={['fas', 'search']} />
                        </Button>
                    </InputGroupAddon>
                    <Input
                        className={'my-auto'}
                        value={value}
                        name={`filter[${options.name}]`}
                        onKeyUp={this.handleChange}
                        onChange={this.handleChange}
                        placeholder={ucwords(this.getFieldPlaceholderText())}
                    />
                    {value &&
                    <InputGroupAddon addonType={'append'}>
                        <Button onClick={this.handleReset} color={'secondary'}>
                            <FontAwesomeIcon icon={['fas', 'times']} />
                        </Button>
                    </InputGroupAddon>
                    }
                </InputGroup>
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
