import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Input} from "reactstrap";

class File extends Component {
    render() {
        return (
            <div className="custom-file">
                <Input className={'custom-file-input'} bsSize={'sm'} type={'file'} />
                <label className="custom-file-label" htmlFor="customFile">Choose file</label>
            </div>
        );
    }
}

File.propTypes = {};

export default File;
