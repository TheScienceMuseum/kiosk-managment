import PropTypes from 'prop-types';

export const assetType = PropTypes.shape({
    boundingBox: PropTypes.shape({
        x: PropTypes.number,
        y: PropTypes.number,
        height: PropTypes.number,
        width: PropTypes.number,
    }),
    assetId: PropTypes.number.isRequired,
    assetMime: PropTypes.string.isRequired,
    assetType: PropTypes.oneOf(['audio', 'image', 'video']).isRequired,
    nameText: PropTypes.string,
    sourceText: PropTypes.string,
});

export const contentSectionType = PropTypes.shape({
    image: assetType,
    subtitle: PropTypes.string,
    title: PropTypes.string,
    type: PropTypes.oneOf(["title", "textImage", "image", "video", "hotspot", "textVideo", "textAudio"]).isRequired,
    layout: PropTypes.oneOf(["left", "right"]),
});

export const contentPageType = PropTypes.shape({
    type: PropTypes.oneOf(['mixed', 'video', 'timeline', 'model']).isRequired,
    title: PropTypes.string.isRequired,
    subpages: PropTypes.arrayOf(contentSectionType),
});
