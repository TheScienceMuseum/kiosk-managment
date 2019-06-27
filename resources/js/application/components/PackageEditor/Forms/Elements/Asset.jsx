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

    onToggleAssetBrowser() {
        this.setState(prevState => ({
            ...prevState,
            showAssetBrowser: !this.state.showAssetBrowser,
        }))
    }

    onAssetChosen(asset) {
        this.onClearChosenAsset();

        const assetData = {
            assetId: asset.id,
            assetMime: asset.mime_type,
            assetType: asset.mime_type.split('/')[0],
            assetFilename: asset.file_name,
        };

        this.props.onChange(this.props.name, {
            ...this.props.value,
            ...assetData,
        });

        this.onToggleAssetBrowser();
        setTimeout(this.createCropper);
    }

    handleTextChange(event) {
        const name = event.target.name;
        const value = event.target.value;

        this.props.onChange(this.props.name, {
            ...this.props.value,
            [name]: value,
        });
    }

    onClearChosenAsset() {
        this.props.onChange(this.props.name, null);
    }

    renderChosenAssetText() {
        return has(this.props, 'value.assetFilename') ?
            get(this.props, 'value.assetFilename') :
            `None`;
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
        return (
            <Alert color={'primary'} className={'mb-0 border-0'}>
                <AssetBrowser packageId={this.props.packageId}
                              packageVersionId={this.props.packageVersionId}
                              showModal={this.state.showAssetBrowser}
                              onToggleModal={this.onToggleAssetBrowser}
                              onAssetChosen={this.onAssetChosen}
                              filter={{
                                  mime_type: Asset._assetTypes[this.props.assetType].mimeType
                              }}
                />

                <InputGroup size={'sm'}>
                    <Input readOnly value={this.renderChosenAssetText()}/>
                    <InputGroupAddon addonType="append">
                        <Button color={'primary'} onClick={this.onToggleAssetBrowser}>Choose Asset</Button>
                        <Button color={'secondary'} onClick={this.onClearChosenAsset}>Clear</Button>
                    </InputGroupAddon>
                </InputGroup>

                {this.props.value &&
                <>
                    {Asset._assetTypes[this.props.assetType].hasName &&
                    <FormGroup className={'mt-3'}>
                        <InputGroup size={'sm'}>
                            <InputGroupAddon addonType="prepend">
                                Title
                            </InputGroupAddon>
                            <Input value={this.props.value.nameText}
                                   name={'nameText'}
                                   onChange={this.handleTextChange}
                                   maxLength={Asset._assetTypes[this.props.assetType].maxTitle}
                            />
                        </InputGroup>
                    </FormGroup>
                    }

                    {Asset._assetTypes[this.props.assetType].hasSource &&
                    <FormGroup className={'mb-0'}>
                        <InputGroup size={'sm'}>
                            <InputGroupAddon addonType="prepend">
                                Source
                            </InputGroupAddon>
                            <Input value={this.props.value.sourceText}
                                   name={'sourceText'}
                                   onChange={this.handleTextChange}
                                   maxLength={Asset._assetTypes[this.props.assetType].maxSource}
                            />
                        </InputGroup>
                    </FormGroup>
                    }

                    {this.props.value.assetType === 'image' && this.props.value.assetId &&
                    <div className={'mt-3'}>
                        <img id={`asset-image-cropper-${this.props.name}`}
                             className={'img-fluid'}
                             src={`/asset/${this.props.value.assetId}`}
                             alt={'Cropping Preview of image'}
                        />
                        {this.cropper &&
                        <FormGroup>
                            <Button color={this.state.cropperEnabled ? 'success' : 'primary'}
                                    onClick={this.toggleCropper}
                                    size={'sm'}
                            >
                                Change the image crop
                            </Button>
                        </FormGroup>
                        }
                    </div>
                    }

                    {this.props.value.assetType === 'video' && this.props.value.assetId &&
                    <div className={'mt-3 embed-responsive embed-responsive-16by9'}>
                        <video controls poster={this.props.value.assetThumb}>
                            <source src={`/asset/${this.props.value.assetId}`}
                                    type={this.props.value.assetMime}/>
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    }

                    {this.props.value.assetType === 'audio' && this.props.value.assetId &&
                    <>
                        <div className={'mt-3 embed-responsive'}>
                            <audio controls style={{ width: '100%' }}>
                                <source src={`/asset/${this.props.value.assetId}`}
                                        type={this.props.value.assetMime}/>
                                Your browser does not support the audio tag.
                            </audio>
                        </div>

                        <FormGroup className={'mb-0 mt-3'}>
                            <InputGroup size={'sm'}>
                                <InputGroupAddon addonType="prepend">
                                    Transcript
                                </InputGroupAddon>
                                <Input value={this.props.value.transcript}
                                       name={'transcript'}
                                       onChange={this.handleTextChange}
                                       type={'textarea'}
                                       rows={10}
                                />
                            </InputGroup>
                        </FormGroup>
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
