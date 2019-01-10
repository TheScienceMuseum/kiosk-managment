import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Pagination, PaginationItem, PaginationLink} from "reactstrap";

class ResourceListPagination extends Component {
    constructor(props) {
        super(props);

        this.state = {
            pages: this.calculatePages(props.last),
        };
    }

    componentWillReceiveProps(nextProps, nextContext) {
        this.setState(prevState => ({
            ...prevState,
            pages: this.calculatePages(nextProps.last),
        }));
    }

    calculatePages(total_pages) {
        const pages = [];

        for (let i = 0; i < total_pages; i++) {
            pages.push(i + 1);
        }

        return pages;
    }

    render() {
        return (
            <Pagination size={'sm'}>
                <PaginationItem disabled={this.props.current === 1}>
                    <PaginationLink previous onClick={this.props.handleResourceListPagination('previous')} />
                </PaginationItem>
                {this.state.pages.map(page =>
                    <PaginationItem active={this.props.current === page}
                                    key={`pagination-page-${page}`}
                    >
                        <PaginationLink onClick={this.props.handleResourceListPagination(page)}>
                            {page}
                        </PaginationLink>
                    </PaginationItem>
                )}
                <PaginationItem disabled={this.props.current === this.props.last}>
                    <PaginationLink next onClick={this.props.handleResourceListPagination('next')} />
                </PaginationItem>
            </Pagination>
        );
    }
}

ResourceListPagination.propTypes = {
    current: PropTypes.number.isRequired,
    last: PropTypes.number.isRequired,
    handleResourceListPagination: PropTypes.func.isRequired,
};

export default ResourceListPagination;
