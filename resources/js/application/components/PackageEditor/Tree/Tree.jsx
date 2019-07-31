import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { extend, has } from 'lodash';
import { Alert, Button, Col, Row } from 'reactstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { assetType } from '../PropTypes';
import CONSTANTS from '../Constants';
import Branch from './Branch';
import Validation from '../../../../helpers/PackageDataValidation';

class Tree extends Component {
    _types = {
        // Pages
        video: CONSTANTS.LABELS.PAGE.video,
        mixed: CONSTANTS.LABELS.PAGE.mixed,
        model: CONSTANTS.LABELS.PAGE.model,

        // Sections
        title: CONSTANTS.LABELS.SECTION.title,
        textImage: CONSTANTS.LABELS.SECTION.textImage,
        textVideo: CONSTANTS.LABELS.SECTION.textVideo,
        textAudio: CONSTANTS.LABELS.SECTION.textAudio,
        image: CONSTANTS.LABELS.SECTION.image,
    };

    render() {
        const {
            data,
            handleViewElement,
            handleAddElement,
            handleRemoveElement,
            handleMoveElement,
            currentViewing,
            validationErrors
        } = this.props;

        const validation = Validation(validationErrors);

        return (
            <>
                {validationErrors &&
                <Row>
                    <Col>
                        <Alert color={'danger'}>
                            There are errors in this package, please resolve them and click "Validate Package"
                        </Alert>
                    </Col>
                </Row>
                }
                <div className={'d-flex justify-content-between'}>
                    <div></div>
                    <Button
                        color={validation.has('content.titles') ? 'danger' : 'primary'}
                        onClick={handleViewElement('title', extend(data.content.titles))}
                        size={'sm'}
                        className={'float-right'}
                    >
                        Package &amp; Attractor Setup
                    </Button>
                </div>
                <hr />
                <div className={'Tree'}>
                    {data.content.contents.length === 0 &&
                        <Alert color={'danger'} className={'text-center'}>
                            You must add at least one page to the kiosk
                        </Alert>
                    }
                    {data.content.contents.map((page, pageIndex) =>
                        <Branch
                            key={`tree-page-${pageIndex}`}
                            index={pageIndex}
                            page={page}
                            currentlyViewing={currentViewing}
                            canMoveUp={pageIndex !== 0}
                            canMoveDown={pageIndex !== data.content.contents.length - 1}
                            handleAddElement={handleAddElement}
                            handleViewElement={handleViewElement}
                            handleRemoveElement={handleRemoveElement}
                            handleMoveElement={handleMoveElement}
                            validation={validation}
                        />
                    )}
                </div>
                <Button
                    color={'primary'}
                    onClick={handleAddElement('page', null)}
                    size={'sm'}
                    className={'float-right'}
                >
                    <FontAwesomeIcon icon={['fal', 'plus']} />&nbsp;Add Page
                </Button>
            </>
        );
    }
}

Tree.propTypes = {
    currentViewing: PropTypes.shape({
        pageIndex: PropTypes.number,
        sectionIndex: PropTypes.number,
    }),
    data: PropTypes.shape({
        content: PropTypes.shape({
            titles: PropTypes.shape({
                image: assetType,
                title: PropTypes.string,
                type: PropTypes.oneOf(['text']),
            }).isRequired,
            contents: PropTypes.arrayOf(PropTypes.shape({
                articleID: PropTypes.string,
                subpages: PropTypes.arrayOf(PropTypes.shape({
                    image: assetType,
                    pageID: PropTypes.string,
                    subtitle: PropTypes.string,
                    title: PropTypes.string,
                    type: PropTypes.oneOf(['title', 'textImage', 'image', 'video', 'hotspot', 'textVideo', 'textAudio']).isRequired,
                    layout: PropTypes.oneOf(['left', 'right']),
                })),
                titleImage: assetType,
                type: PropTypes.oneOf(['mixed', 'video', 'timeline', 'model']),
                videoSrc: assetType,
            })),
        }),
    }),
    handleAddElement: PropTypes.func.isRequired,
    handleRemoveElement: PropTypes.func.isRequired,
    handleViewElement: PropTypes.func.isRequired,
    handleMoveElement: PropTypes.func.isRequired,
};

export default Tree;
