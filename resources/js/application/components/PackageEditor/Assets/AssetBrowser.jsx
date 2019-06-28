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
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";

class AssetBrowser extends Component {
    constructor(props) {
        super(props);

        this.state = {
            assets: [],
            filter: {
                mime_type: get(this.props.filter, 'mime_type', ''),
                file_name: get(this.props.filter, 'file_name', ''),
            },
            loading: false,
        };

        this.handleAssetSelected = this.handleAssetSelected.bind(this);
        this.handleAssetUploaded = this.handleAssetUploaded.bind(this);
        this.searchAssets        = this.searchAssets.bind(this);
    }

    componentWillReceiveProps(nextProps) {
        const { chooseAssetFor } = nextProps;
        let mime_type = this.state.filter.mime_type;

        if (chooseAssetFor === 'subtitleAsset') {
            mime_type = 'text/vtt';
        }

        this.setState(prevState => ({
            ...prevState,
            filter: {
                ...prevState.filter,
                mime_type,
            }
        }));

        if (nextProps.showModal) {
            this.searchAssets();
        }
    }

    searchAssets() {
        this.setState(prevState => ({
            ...prevState,
            assets: [],
            loading: true,
        }), () => {
            const filters = {};
            each(this.state.filter, (value, name) => {
                filters[`filter[${name}]`] = value;
            });

            axios.get(`/api/package/${this.props.packageId}/version/${this.props.packageVersionId}/asset`, { params: filters })
                .then(response => {
                    this.setState(prevState => ({
                        ...prevState,
                        assets: response.data.data,
                        loading: false,
                    }));
                })
        });
    }

    handleAssetUploaded() {
        this.searchAssets();
    }

    handleAssetSelected(asset) {
        return () => {
            this.props.onAssetChosen(asset);
        };
    }

    componentDidUpdate(prevProps, prevState, snapshot) {

    }

    render() {
        return (
            <Modal isOpen={this.props.showModal} toggle={this.props.onToggleModal(this.props.chooseAssetFor)} className={this.props.className}
                   size={'lg'}>
                <ModalHeader toggle={this.props.onToggleModal(this.props.chooseAssetFor)}>
                    Asset Browser
                </ModalHeader>
                <ModalBody style={{
                    maxHeight: '75vh',
                    overflow: 'scroll',
                }}>
                    {(this.state.loading &&
                    <div className={'text-center'}>
                        <FontAwesomeIcon icon={['fal', 'sync-alt']} spin size={'4x'} />
                    </div>
                    ) || (this.state.assets.length === 0 &&
                        <div className={'text-center'}>
                            <em>No assets found, try uploading one below.</em>
                        </div>
                    )}
                    {this.state.assets.map(asset =>
                        <Card className={'mb-3'} key={`asset-item-${asset.id}`}>
                            <CardBody className={'p-0'}>
                                <Media>
                                    <img src={`/asset/${asset.id}/thumb`} alt={''} className={'img-square'} />
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
                    {!this.state.loading &&
                    <Button size={'sm'} color={'link'} block onClick={this.searchAssets}>Refresh</Button>
                    }
                </ModalBody>
                <ModalFooter>
                    <FileUpload handleAssetUploaded={this.handleAssetUploaded}
                                packageId={this.props.packageId}
                                packageVersionId={this.props.packageVersionId}
                                assetTypes={get(this.state, 'filter.mime_type') ? [get(this.state, 'filter.mime_type').replace('/', '')] : null}
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
    chooseAssetFor: PropTypes.oneOf(['main', 'bslAsset', 'subtitleAsset']),
    onToggleModal: PropTypes.func.isRequired,
    onAssetChosen: PropTypes.func.isRequired,
    filter: PropTypes.shape({
        mime_type: PropTypes.string,
        filename: PropTypes.string,
    })
};

export default AssetBrowser;
