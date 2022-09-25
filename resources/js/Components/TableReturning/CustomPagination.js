import React from "react";
import Pagination from "react-js-pagination";
export default function CustomPagination({
    total, page, handlePageChange
}) {
    return (
        <>
            {total > 1 ? (
                <Pagination
                    activePage={page}
                    itemsCountPerPage={20}
                    totalItemsCount={total}
                    pageRangeDisplayed={3}
                    prevPageText="Previous"
                    nextPageText="Next"
                    itemClass="page-item"
                    linkClass="page-link"
                    linkClassPrev="page-prev"
                    linkClassNext="page-next"
                    activeLinkClass="pagination-active"
                    hideFirstLastPages={true}
                    onChange={(page) => handlePageChange(page)}
                />
            ) : (
                ""
            )}
        </>
    );
}