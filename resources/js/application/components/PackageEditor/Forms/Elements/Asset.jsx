import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Alert, Button, FormGroup, Input, InputGroup, InputGroupAddon} from "reactstrap";
import {debounce, get, has, keys} from 'lodash';
import Cropper from 'cropperjs';
import AssetBrowser from "../../Assets/AssetBrowser";
import Types from '../../PropTypes';

class Asset extends Component {
    static _cropRatios = {
        '16:9': {
            attractor: 16 / 9,
            titleImage: 16 / 9,
            contentVideoImage: 16 / 9,
            sectionImage: 16 / 9,
            sectionTextImage: 91 / 108,
        },
        '9:16': {
            attractor: 9 / 16,
            titleImage: 9 / 16,
            sectionImage: 9 / 16,
            sectionTextImage: 9 / 8,
        },
    };

    static _assetTypes = {
        titleImage: {
            mimeType: 'image/',
            hasName: true,
            hasSource: true,
            hasCrop: true,
            maxSource: 100,
            maxTitle: 100,
        },
        attractor: {
            hasName: false,
            hasSource: false,
            hasCrop: true,
        },
        attractorVideoLandscape: {
            mimeType: 'video/',
            hasName: false,
            hasSource: false,
            hasCrop: false,
        },
        contentImageLandscape: {
            mimeType: 'image/',
            hasName: true,
            hasSource: true,
            hasCrop: true,
        },
        contentVideo: {
            mimeType: 'video/',
            hasName: true,
            hasSource: true,
        },
        contentVideoImage: {
            mimeType: 'image/',
            hasCrop: true,
        },
        sectionImage: {
            mimeType: 'image/',
            hasCrop: true,
            hasName: true,
            hasSource: true,
            maxSource: 100,
            maxTitle: 100,
        },
        sectionTextImage: {
            mimeType: 'image/',
            hasCrop: true,
            hasName: true,
            hasSource: true,
            maxSource: 100,
            maxTitle: 100,
        },
        audio: {
            mimeType: 'audio/',
            hasTranscript: true,
        },
    };

    constructor(props) {
        super(props);

        this.state = {
            showAssetBrowser: false,
            browsingForAsset: 'main',
            cropperEnabled: false,
        };

        this.createCropper = this.createCropper.bind(this);
        this.handleTextChange = this.handleTextChange.bind(this);
        this.onAssetChosen = this.onAssetChosen.bind(this);
        this.onClearChosenAsset = this.onClearChosenAsset.bind(this);
        this.onToggleAssetBrowser = this.onToggleAssetBrowser.bind(this);
        this.renderChosenAssetText = this.renderChosenAssetText.bind(this);
        this.toggleCropper = this.toggleCropper.bind(this);
    }

    componentDidMount() {
        if (this.props.value) {
            this.createCropper();
        }
    }

    componentDidUpdate(prevProps) {
        if(prevProps.value && get(prevProps, 'value.assetId', true) !== get(this.props, 'value.assetId', false)) {
            this.createCropper();
        }
    }

    createCropper() {
        const imageElement = document.getElementById(`asset-image-cropper-${this.props.name}`);

        if (!imageElement || !Asset._assetTypes[this.props.assetType].hasCrop) {
            return;
        }

        if (this.cropper) this.cropper.destroy();

        const updateBoundingBox = debounce((event) => {
            if (event.detail) {
                this.props.onChange(this.props.name, {
                    ...this.props.value,
                    boundingBox: {
                        x: event.detail.x,
                        y: event.detail.y,
                        width: event.detail.width,
                        height: event.detail.height,
                    },
                });
            }
        }, 200);

        const disableCropper = () => {
            this.toggleCropper(false);
        };

        let boundingData = {};

        if (this.props.value && this.props.value.boundingBox) {
            boundingData = {
                x: this.props.value.boundingBox.x,
                y: this.props.value.boundingBox.y,
                width: this.props.value.boundingBox.width,
                height: this.props.value.boundingBox.height,
            };
        }

        this.cropper = new Cropper(imageElement, {
            background: false,
            movable: false,
            rotatable: false,
            scalable: false,
            zoomable: false,
            data: boundingData,
            aspectRatio: Asset._cropRatios[this.props.aspectRatio][this.props.assetType],
            crop(event) {
                updateBoundingBox(event);
            },
            ready() {
                disableCropper();
            }
        });
    }

    onToggleAssetBrowser(browseFor) {
        return () => {
            this.setState(prevState => ({
                ...prevState,
                showAssetBrowser: !this.state.showAssetBrowser,
                browsingForAsset: browseFor,
            }));
        }
    }

    onAssetChosen(chosenFor) {
        return (asset) => {
            let assetData = {};

            if (chosenFor === 'main') {
                assetData = {
                    assetId: asset.id,
                    assetMime: asset.mime_type,
                    assetType: asset.mime_type.split('/')[0],
                    assetFilename: asset.file_name,
                };
            }

            if (chosenFor === 'bslAsset') {
                assetData = {
                    bslAssetId: asset.id,
                    bslAssetMime: asset.mime_type,
                    bslAssetType: asset.mime_type.split('/')[0],
                    bslAssetFilename: asset.file_name,
                };
            }

            this.props.onChange(this.props.name, {
                ...this.props.value,
                ...assetData,
            });

            this.onToggleAssetBrowser(chosenFor)();
            setTimeout(this.createCropper);
        };
    }

    handleTextChange(event) {
        const name = event.target.name;
        const value = event.target.value;

        this.props.onChange(this.props.name, {
            ...this.props.value,
            [name]: value,
        });
    }

    onClearChosenAsset(clearFor = 'main') {
        return () => {
            if (clearFor === 'main') {
                this.props.onChange(this.props.name, null);
            }

            if (clearFor === 'bslAsset') {
                const asset = {...this.props.value};

                if (asset.bslAssetId) delete asset.bslAssetId;
                if (asset.bslAssetMime) delete asset.bslAssetMime;
                if (asset.bslAssetType) delete asset.bslAssetType;
                if (asset.bslAssetFilename) delete asset.bslAssetFilename;

                this.props.onChange(this.props.name, asset);
            }
        }
    }

    renderChosenAssetText(section = 'main') {
        const path = `value.${section === 'main' ? 'asset' : section}Filename`;

        return has(this.props, path) ? get(this.props, path) : 'None';
    }

    toggleCropper(action) {
        this.setState(prevState => ({
            ...prevState,
            cropperEnabled: (action !== undefined && action.constructor === Boolean) ? action : !prevState.cropperEnabled,
        }), () => {
            this.cropper[this.state.cropperEnabled ? 'enable' : 'disable']();
        })
    }

    render() {
        const { packageId,  packageVersionId, assetType, name, value } = this.props;
        const { showAssetBrowser, browsingForAsset, cropperEnabled } = this.state;

        return (
            <Alert color={'primary'} className={'mb-0 border-0'}>
                <AssetBrowser packageId={packageId}
                              packageVersionId={packageVersionId}
                              showModal={showAssetBrowser}
                              chooseAssetFor={browsingForAsset}
                              onToggleModal={this.onToggleAssetBrowser}
                              onAssetChosen={this.onAssetChosen(browsingForAsset)}
                              filter={{
                                  mime_type: Asset._assetTypes[assetType].mimeType
                              }}
                />

                {value &&
                <>
                    {Asset._assetTypes[assetType].hasName &&
                    <FormGroup className={'mb-3'}>
                        <InputGroup size={'sm'}>
                            <InputGroupAddon addonType="prepend">Title</InputGroupAddon>
                            <Input value={value.nameText}
                                   name={'nameText'}
                                   onChange={this.handleTextChange}
                                   maxLength={Asset._assetTypes[assetType].maxTitle}
                            />
                        </InputGroup>
                    </FormGroup>
                    }

                    {Asset._assetTypes[assetType].hasSource &&
                    <FormGroup className={'mb-3'}>
                        <InputGroup size={'sm'}>
                            <InputGroupAddon addonType="prepend">Source</InputGroupAddon>
                            <Input value={value.sourceText}
                                   name={'sourceText'}
                                   onChange={this.handleTextChange}
                                   maxLength={Asset._assetTypes[assetType].maxSource}
                            />
                        </InputGroup>
                    </FormGroup>
                    }
                </>
                }

                <InputGroup size={'sm'}>
                    <InputGroupAddon addonType="prepend">Main</InputGroupAddon>
                    <Input readOnly value={this.renderChosenAssetText()}/>
                    <InputGroupAddon addonType="append">
                        <Button color={'primary'} onClick={this.onToggleAssetBrowser('main')}>Choose Asset</Button>
                        <Button color={'secondary'} onClick={this.onClearChosenAsset('main')}>Clear</Button>
                    </InputGroupAddon>
                </InputGroup>



                {value &&
                <>
                    {value.assetType === 'image' && value.assetId &&
                    <div className={'mt-3'}>
                        <img id={`asset-image-cropper-${name}`}
                             className={'img-fluid'}
                             src={`/asset/${value.assetId}`}
                             alt={'Cropping Preview of image'}
                        />
                        {this.cropper &&
                        <FormGroup>
                            <Button color={cropperEnabled ? 'success' : 'primary'}
                                    onClick={this.toggleCropper}
                                    size={'sm'}
                            >
                                Change the image crop
                            </Button>
                        </FormGroup>
                        }
                    </div>
                    }

                    {value.assetType === 'audio' && value.assetId &&
                    <>
                        <div className={'mt-3 embed-responsive'}>
                            <audio controls style={{ width: '100%' }}>
                                <source src={`/asset/${value.assetId}`}
                                        type={value.assetMime}
                                />
                                Your browser does not support the audio tag.
                            </audio>
                        </div>

                        <FormGroup className={'mb-0 mt-3'}>
                            <InputGroup size={'sm'}>
                                <InputGroupAddon addonType="prepend">Transcript</InputGroupAddon>
                                <Input value={value.transcript}
                                       name={'transcript'}
                                       onChange={this.handleTextChange}
                                       type={'textarea'}
                                       rows={10}
                                />
                            </InputGroup>
                        </FormGroup>
                    </>
                    }

                    {value.assetType === 'video' && value.assetId &&
                        <>
                            <div className={`embed-responsive embed-responsive-16by9`}>
                                <video controls poster={value.assetThumb}>
                                    <source src={`/asset/${value.assetId}`}
                                            type={value.assetMime}
                                    />
                                    Your browser does not support the video tag.
                                </video>
                            </div>

                            <InputGroup size={'sm mt-3'}>
                                <InputGroupAddon addonType="prepend">BSL Version</InputGroupAddon>
                                <Input readOnly value={this.renderChosenAssetText('bslAsset')}/>
                                <InputGroupAddon addonType="append">
                                    <Button color={'primary'} onClick={this.onToggleAssetBrowser('bslAsset')}>Choose Asset</Button>
                                    <Button color={'secondary'} onClick={this.onClearChosenAsset('bslAsset')}>Clear</Button>
                                </InputGroupAddon>
                            </InputGroup>

                            {value.bslAssetId &&
                            <div className={`embed-responsive embed-responsive-16by9`}>
                                <video controls>
                                    <source src={`/asset/${value.bslAssetId}`}
                                            type={value.bslAssetMime}
                                    />
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            }
                        </>
                    }
                </>
                }
            </Alert>
        );
    }
}

Asset.propTypes = {
    packageId: PropTypes.string.isRequired,
    packageVersionId: PropTypes.string.isRequired,
    name: PropTypes.string.isRequired,
    value: Types.asset,
    onChange: PropTypes.func.isRequired,
    assetType: PropTypes.oneOf(keys(Asset._assetTypes)).isRequired,
    aspectRatio: PropTypes.oneOf(keys(Asset._cropRatios)).isRequired,
};

export default Asset;
