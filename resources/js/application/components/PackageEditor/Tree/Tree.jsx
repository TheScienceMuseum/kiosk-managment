import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { extend } from 'lodash';
import { Button, ButtonGroup } from 'reactstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { assetType } from '../PropTypes';
import CONSTANTS from '../Constants';
import Branch from './Branch';

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
        } = this.props;

        return (
            <>
                <div className={'mb-3'}>
                    <span className={'text-muted text-sm my-auto'}>Note: Amend location, title and attractor</span>
                    <Button
                        color={'primary'}
                        onClick={handleViewElement('title', extend(data.content.titles, { aspect_ratio: data.aspect_ratio }))}
                        size={'sm'}
                        className={'float-right'}
                    >
                        Package Setup
                    </Button>
                    <hr />
                </div>
                <div className={'Tree'}>
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
                title: PropTypes.string.isRequired,
                type: PropTypes.oneOf(['text']).isRequired,
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
                type: PropTypes.oneOf(['mixed', 'video', 'model']),
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
