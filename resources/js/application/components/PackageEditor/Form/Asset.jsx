import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Alert, Button, FormGroup, Input, InputGroup, InputGroupAddon} from "reactstrap";
import {debounce, keys} from 'lodash';
import Cropper from 'cropperjs';
import AssetBrowser from "../Assets/AssetBrowser";
import Types from '../PropTypes';

class Asset extends Component {
    static _assetTypes = {
        titleImage: {
            aspectRatio: 1 / 1,
            mimeType: 'image/',
            hasName: true,
            hasSource: true,
            hasCrop: true,
        },
        attractorImageLandscape: {
            aspectRatio: 16 / 9,
            mimeType: 'image/',
            hasName: false,
            hasSource: false,
            hasCrop: true,
        },
        attractorVideoLandscape: {
            aspectRatio: 16 / 9,
            mimeType: 'video/',
            hasName: false,
            hasSource: false,
            hasCrop: false,
        },
        contentImageLandscape: {
            aspectRatio: 16 / 9,
            mimeType: 'image/',
            hasName: true,
            hasSource: true,
            hasCrop: true,
        },
        contentVideo: {
            aspectRatio: 16 / 9,
            mimeType: 'video/',
            hasName: true,
            hasSource: true,
        },
    };

    constructor(props) {
        super(props);

        this.state = {
            showAssetBrowser: false,
        };

        this.createCropper = this.createCropper.bind(this);
        this.handleTextChange = this.handleTextChange.bind(this);
        this.onAssetChosen = this.onAssetChosen.bind(this);
        this.onClearChosenAsset = this.onClearChosenAsset.bind(this);
        this.onToggleAssetBrowser = this.onToggleAssetBrowser.bind(this);
        this.renderChosenAssetText = this.renderChosenAssetText.bind(this);
    }

    componentDidMount() {
        if (this.props.value) {
            this.createCropper();
        }
    }

    createCropper() {
        const imageElement = document.getElementById(`asset-image-cropper-${this.props.name}`);

        if (!imageElement || !Asset._assetTypes[this.props.assetType].hasCrop) {
            console.log(`did not create cropper`);
            console.log(imageElement);
            console.log(Asset._assetTypes[this.props.assetType]);
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
            aspectRatio: Asset._assetTypes[this.props.assetType].aspectRatio,
            crop(event) {
                updateBoundingBox(event);
            },
        });
    }

    onToggleAssetBrowser() {
        this.setState(prevState => ({
            ...prevState,
            showAssetBrowser: !this.state.showAssetBrowser,
        }))
    }

    onAssetChosen(asset) {
        const assetData = {
            assetId: asset.id,
            assetMime: asset.mime_type,
            assetType: asset.mime_type.indexOf('image/') !== -1 ? 'image' : 'video',
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
        return this.props.value ?
            `Chosen` :
            `None`;
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
                <div>
                    {Asset._assetTypes[this.props.assetType].hasName &&
                    <FormGroup className={'mt-3'}>
                        <InputGroup size={'sm'}>
                            <InputGroupAddon addonType="prepend">
                                Asset Name
                            </InputGroupAddon>
                            <Input value={this.props.value.nameText} name={'nameText'}
                                   onChange={this.handleTextChange}/>
                        </InputGroup>
                    </FormGroup>
                    }

                    {Asset._assetTypes[this.props.assetType].hasSource &&
                    <FormGroup className={'mb-0'}>
                        <InputGroup size={'sm'}>
                            <InputGroupAddon addonType="prepend">
                                Source Text
                            </InputGroupAddon>
                            <Input value={this.props.value.sourceText} name={'sourceText'}
                                   onChange={this.handleTextChange}/>
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
                    </div>
                    }

                    {this.props.value.assetType === 'video' && this.props.value.assetId &&
                    <div className={'mt-3 embed-responsive embed-responsive-16by9'}>
                        <video controls loop autoPlay poster={this.props.value.assetThumb}>
                            <source src={`/asset/${this.props.value.assetId}`} type={this.props.value.assetMime} />
                                Your browser does not support the video tag.
                        </video>
                    </div>
                    }
                </div>
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
};

export default Asset;
