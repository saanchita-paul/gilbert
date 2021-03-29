export default class Pagination {
    constructor(
        {
            currentPage,
            hasMorePages,
            nextPage,
            perPage,
            previousPage,
            total,
            pageCount
        } = {}
    ) {
        this.pageCount = pageCount;
        this.currentPage = currentPage || 1;
        this.hasMorePages = hasMorePages || false;
        this.nextPage = nextPage || null;
        this.perPage = perPage || 15;
        this.previousPage = previousPage || null;
        this.total = total || 0;
    }
}
