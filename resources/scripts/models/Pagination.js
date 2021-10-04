export default class Pagination {
    constructor(
        {
            page,
            hasMorePages,
            nextPage,
            perPage,
            previousPage,
            total,
            pageCount
        } = {}
    ) {
        this.page = page;
        this.page_count = pageCount || 1;
        this.hasMore_pages = hasMorePages || false;
        this.next_page = nextPage || null;
        this.per_page = perPage || 15;
        this.previous_page = previousPage || null;
        this.total = total || 0;
    }
}
