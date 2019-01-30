import React, {Component} from 'react';
import PropTypes from 'prop-types';
import async from 'async';
import {Button, Col, CustomInput, InputGroup, Row} from "reactstrap";

class FileUpload extends Component {
    constructor(props) {
        super(props);

        this.state = {
            canUpload: false,
            filesForUpload: [],
        };

        this.actionUpload  = this.actionUpload.bind(this);
        this.chooseFiles   = this.chooseFiles.bind(this);
        this.uploadAssets  = this.uploadAssets.bind(this);
    }

    chooseFiles(event) {
        const files = event.target.files;

        this.setState(prevState => ({
            ...prevState,
            filesForUpload: files,
            canUpload: files.length > 0,
        }))
    }

    uploadAssets() {
        async.map(
            Array.from(this.state.filesForUpload),
            async.asyncify(this.actionUpload),
            this.props.handleAssetUploaded
        );
    }

    actionUpload(file) {
        const formData = new FormData();
        formData.append('file', file);

        return axios.post(
            `/api/package/${this.props.packageId}/version/${this.props.packageVersionId}/asset`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        );
    }

    render() {
        return (
            <div className={'w-100'}>
                <InputGroup>
                    <CustomInput id={'assetUploadInput'} type={'file'} onChange={this.chooseFiles} multiple/>
                    <Button color="primary" onClick={this.uploadAssets} disabled={!this.state.canUpload}>Upload Asset(s)</Button>
                </InputGroup>
            </div>
        );
    }
}

FileUpload.propTypes = {
    handleAssetUploaded: PropTypes.func.isRequired,
    packageId: PropTypes.string.isRequired,
    packageVersionId: PropTypes.string.isRequired,
};

export default FileUpload;
