import React, {Component} from 'react';
import PropTypes from 'prop-types';
import async from 'async';
import {truncate} from 'lodash';
import {Button, CustomInput, InputGroup} from "reactstrap";

class FileUpload extends Component {
    _acceptedFileTypes = {
        image: ['.jpg', '.jpeg', '.png'],
        video: ['.mp4'],
        audio: ['.mp3'],
    };

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
                    <CustomInput id={'assetUploadInput'}
                                 type={'file'}
                                 onChange={this.chooseFiles}
                                 accept={[].concat(this.props.assetTypes ?
                                     this.props.assetTypes.map(type => this._acceptedFileTypes[type]) :
                                     Object.values(this._acceptedFileTypes)
                                 )}
                                 label={truncate(Array.from(this.state.filesForUpload).map(file => file.name).join(', '), {length: 50})}
                                 multiple
                    />
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
    assetTypes: PropTypes.array,
};

export default FileUpload;
