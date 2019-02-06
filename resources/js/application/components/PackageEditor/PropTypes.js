import PropTypes from 'prop-types';

const assetType = PropTypes.shape({
    boundingBox: PropTypes.shape({
        x: PropTypes.number,
        y: PropTypes.number,
        height: PropTypes.number,
        width: PropTypes.number,
    }),
    assetId: PropTypes.number.isRequired,
    assetThumb: PropTypes.string.isRequired,
    assetFull: PropTypes.string.isRequired,
    assetMime: PropTypes.string.isRequired,
    assetType: PropTypes.oneOf(['image', 'video']).isRequired,
    nameText: PropTypes.string,
    sourceText: PropTypes.string,
});


export default {
    asset: assetType,
}