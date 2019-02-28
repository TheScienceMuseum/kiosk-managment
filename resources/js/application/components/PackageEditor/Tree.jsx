import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Button} from 'reactstrap';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import Types from "./PropTypes";

class Tree extends Component {
    _types = {
        video: 'Video',
        mixed: 'Mixed',
        title: 'Title',
        textImage: 'Image Text',
        image: 'Image',
    };
    render() {
        return (
            <div>
                <div className={'mb-3 font-weight-bold mx-1'}>
                    Package Structure
                    <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleAddElement('page', null)}>
                        Add Page
                    </Button>
                </div>
                <div className="alert alert-primary p-3 mb-0 tree">
                    <div>
                        Package Configuration
                        <Button size={'xs'}
                                color={'primary'}
                                className={'float-right'}
                                onClick={this.props.handleViewElement('title', this.props.data.titles)}
                        >
                            <FontAwesomeIcon icon={['fal', 'angle-double-right']}/>
                        </Button>
                    </div>
                    <div className={'tree-list'}>
                        {this.props.data.contents.map((page, pageIndex) =>
                            (page.type === 'mixed' &&
                                <details key={`page-${pageIndex}`}>
                                    <summary>
                                        {page.title && page.title.substring(0, 20)} ({this._types[page.type]})
                                        <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleViewElement('page', page, pageIndex)}>
                                            <FontAwesomeIcon icon={['fal', 'angle-double-right']}/>
                                        </Button>
                                        <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleAddElement('section', pageIndex)}>
                                            <FontAwesomeIcon icon={['fal', 'plus']}/>
                                        </Button>
                                        <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleRemoveElement('page', pageIndex)}>
                                            <FontAwesomeIcon icon={['fal', 'minus']}/>
                                        </Button>
                                    </summary>
                                    {page.subpages && page.subpages.map((section, sectionIndex) =>
                                        <div key={`page-${pageIndex}-section-${sectionIndex}`}>
                                            {section.title && section.title.substring(0, 20)} ({this._types[section.type]})
                                            <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleViewElement('section', section, pageIndex, sectionIndex)}>
                                                <FontAwesomeIcon icon={['fal', 'angle-double-right']}/>
                                            </Button>
                                            <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleRemoveElement('section', pageIndex, sectionIndex)}>
                                                <FontAwesomeIcon icon={['fal', 'minus']}/>
                                            </Button>
                                        </div>
                                    )}
                                </details>
                            ) || (page.type === 'video' &&
                                <div key={`page-${pageIndex}`}>
                                    {page.title && page.title.substring(0, 20)} ({this._types[page.type]})
                                    <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleViewElement('page', page, pageIndex)}>
                                        <FontAwesomeIcon icon={['fal', 'angle-double-right']}/>
                                    </Button>
                                    <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleRemoveElement('page', pageIndex)}>
                                        <FontAwesomeIcon icon={['fal', 'minus']}/>
                                    </Button>
                                </div>
                            )
                        )}
                    </div>
                </div>
            </div>
        );
    }
}

Tree.propTypes = {
    handleAddElement: PropTypes.func.isRequired,
    handleRemoveElement: PropTypes.func.isRequired,
    handleViewElement: PropTypes.func.isRequired,
    data: PropTypes.shape({
        titles: PropTypes.shape({
            galleryName: PropTypes.string.isRequired,
            image: Types.asset,
            title: PropTypes.string.isRequired,
            type: PropTypes.oneOf(["text"]).isRequired,
        }).isRequired,
        contents: PropTypes.arrayOf(PropTypes.shape({
            articleID: PropTypes.string,
            subpages: PropTypes.arrayOf(PropTypes.shape({
                image: Types.image,
                pageID: PropTypes.string,
                subtitle: PropTypes.string,
                title: PropTypes.string,
                type: PropTypes.oneOf(["title", "textImage", "image"]),
                layout: PropTypes.oneOf(["left", "right"]),
            })),
            title: PropTypes.string,
            titleImage: Types.asset,
            type: PropTypes.oneOf(["mixed", "video"]),
            videoSrc: Types.asset,
        })),
    }),
};

export default Tree;
