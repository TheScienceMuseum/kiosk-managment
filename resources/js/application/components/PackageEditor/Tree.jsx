import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Button} from 'reactstrap';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";

class Tree extends Component {

    render() {
        return (
            <div>
                <div className={'mb-3 font-weight-bold mx-1'}>
                    Package Structure
                    <Button size={'xs'} color={'primary'} className={'float-right'}>
                        Add Page
                    </Button>
                </div>
                <div className="alert alert-primary p-3 mb-0 tree">
                    <div>
                        Title Page
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
                                        Page {pageIndex + 1} ({page.type})
                                        <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleViewElement('page', page)}>
                                            <FontAwesomeIcon icon={['fal', 'angle-double-right']}/>
                                        </Button>
                                        <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleAddElement('section', page)}>
                                            <FontAwesomeIcon icon={['fal', 'plus']}/>
                                        </Button>
                                    </summary>
                                    {page.subpages && page.subpages.map((section, sectionIndex) =>
                                        <div key={`page-${pageIndex}-section-${sectionIndex}`}>
                                            Section {sectionIndex + 1}
                                            <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleViewElement('section', section)}>
                                                <FontAwesomeIcon icon={['fal', 'angle-double-right']}/>
                                            </Button>
                                        </div>
                                    )}
                                </details>
                            ) || (page.type === 'video' &&
                                <div key={`page-${pageIndex}`}>
                                    Page {pageIndex + 1} ({page.type})
                                    <Button size={'xs'} color={'primary'} className={'float-right'} onClick={this.props.handleViewElement('page', page)}>
                                        <FontAwesomeIcon icon={['fal', 'angle-double-right']}/>
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
    handleViewElement: PropTypes.func.isRequired,
    data: PropTypes.shape({
        contents: PropTypes.arrayOf(PropTypes.shape({
            articleID: PropTypes.string,
            subpages: PropTypes.arrayOf(PropTypes.shape({
                image: PropTypes.oneOfType([
                    PropTypes.string,
                    PropTypes.shape({
                        imageLandscape: PropTypes.string,
                        imagePortrait: PropTypes.string,
                        imageSource: PropTypes.string,
                        imageThumbnail: PropTypes.string,
                        nameText: PropTypes.string,
                        sourceText: PropTypes.string,
                    }),
                ]),
                pageID: PropTypes.string,
                subtitle: PropTypes.string,
                title: PropTypes.string,
                type: PropTypes.oneOf(["title", "textImage", "image"]),
                layout: PropTypes.oneOf(["left", "right"]),
            })),
            title: PropTypes.string,
            titleImage: PropTypes.string,
            type: PropTypes.oneOf(["mixed", "video"]),
            videoSrc: PropTypes.string,
        })),
        titles: PropTypes.shape({
            galleryName: PropTypes.string.isRequired,
            title: PropTypes.string.isRequired,
            type: PropTypes.oneOf(['image', 'text']).isRequired,
        }).isRequired
    }),
};

export default Tree;
