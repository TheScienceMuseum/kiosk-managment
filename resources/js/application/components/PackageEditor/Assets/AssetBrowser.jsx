import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {
    Button,
    Card,
    CardBody,
    Input,
    InputGroup,
    InputGroupAddon,
    Media,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader
} from "reactstrap";
import FileUpload from "./FileUpload";
import {each, get} from 'lodash';

class AssetBrowser extends Component {
    constructor(props) {
        super(props);

        this.state = {
            assets: [],
            filter: {
                mime_type: get(this.props.filter, 'mime_type', ''),
                file_name: get(this.props.filter, 'file_name', ''),
            },
        };

        this.handleAssetSelected = this.handleAssetSelected.bind(this);
        this.handleAssetUploaded = this.handleAssetUploaded.bind(this);
        this.searchAssets        = this.searchAssets.bind(this);
    }

    componentDidMount() {
        this.searchAssets();
    }

    searchAssets() {
        const filters = {};
        each(this.state.filter, (value, name) => {
            filters[`filter[${name}]`] = value;
        });

        axios.get(`/api/package/${this.props.packageId}/version/${this.props.packageVersionId}/asset`, { params: filters })
            .then(response => {
                this.setState(prevState => ({
                    ...prevState,
                    assets: response.data.data,
                }));
            })
    }

    handleAssetUploaded() {
        this.searchAssets();
    }

    handleAssetSelected(asset) {
        return () => {
            this.props.onAssetChosen(asset);
        };
    }

    render() {
        return (
            <Modal isOpen={this.props.showModal} toggle={this.props.onToggleModal} className={this.props.className}
                   size={'lg'}>
                <ModalHeader toggle={this.props.onToggleModal}>Asset Browser</ModalHeader>
                <ModalBody style={{
                    maxHeight: '75vh',
                    overflow: 'scroll',
                }}>
                    {this.state.assets.map(asset =>
                        <Card className={'mb-3'} key={`asset-item-${asset.id}`}>
                            <CardBody className={'p-0'}>
                                <Media>
                                    <img src={asset.url_thumb} alt={''} className={'img-square'} />
                                    <div className={'media-body p-3'}>
                                        <InputGroup size={'sm'} className={'mb-1'}>
                                            <Input readOnly value={asset.file_name} />
                                        </InputGroup>
                                        <InputGroup size={'sm'} className={'mb-1'}>
                                            <InputGroupAddon addonType="prepend">Type</InputGroupAddon>
                                            <Input readOnly value={asset.mime_type} />
                                        </InputGroup>
                                        <Button color={'primary'} size={'sm'} className={'mb-1 mt-2 float-right'} block onClick={this.handleAssetSelected(asset)}>select</Button>
                                    </div>
                                </Media>
                            </CardBody>
                        </Card>
                    )}
                </ModalBody>
                <ModalFooter>
                    <FileUpload handleAssetUploaded={this.handleAssetUploaded}
                                packageId={this.props.packageId}
                                packageVersionId={this.props.packageVersionId}
                    />
                </ModalFooter>
            </Modal>
        );
    }
}

AssetBrowser.propTypes = {
    packageId: PropTypes.string.isRequired,
    packageVersionId: PropTypes.string.isRequired,
    showModal: PropTypes.bool.isRequired,
    onToggleModal: PropTypes.func.isRequired,
    onAssetChosen: PropTypes.func.isRequired,
    filter: PropTypes.shape({
        type: PropTypes.string,
        filename: PropTypes.string,
    })
};

export default AssetBrowser;
