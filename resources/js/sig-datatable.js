/**
 * SIG DataTable — Reusable Alpine.js mixin
 * 
 * Provides client-side pagination, sorting, and entries-per-page selection
 * for any table in the SIG LMS application.
 *
 * Usage:
 *   In your Alpine component, spread this mixin and configure:
 *
 *   x-data="{
 *       ...sigDataTable(),
 *       items: [...],           // your raw data array
 *       get filteredItems() {   // your custom filtering logic
 *           return this.items.filter(...)
 *       }
 *   }"
 *
 *   Then use `paginatedItems` for rendering and the helper methods
 *   for pagination controls and sort headers.
 */
function sigDataTable(defaultPerPage = 5) {
    return {
        // Pagination state
        currentPage: 1,
        perPage: defaultPerPage,
        perPageOptions: [5, 10, 25, 50],

        // Sorting state
        sortColumn: '',
        sortDirection: 'asc', // 'asc' or 'desc'

        /**
         * The items to paginate. Override `filteredItems` in your component
         * to provide filtered data. Falls back to `items` if not defined.
         */
        get _sourceItems() {
            return this.filteredItems ?? this.items ?? [];
        },

        /**
         * Sorted items based on the current sort column and direction.
         */
        get sortedItems() {
            const data = [...this._sourceItems];
            if (!this.sortColumn) return data;

            return data.sort((a, b) => {
                let valA = a[this.sortColumn] ?? '';
                let valB = b[this.sortColumn] ?? '';

                // Normalize for comparison
                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();

                if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
                if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        },

        /**
         * Total number of pages based on current filtered + sorted data.
         */
        get totalPages() {
            return Math.ceil(this.sortedItems.length / this.perPage) || 1;
        },

        /**
         * The final slice of data to render in the table.
         */
        get paginatedItems() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.sortedItems.slice(start, start + this.perPage);
        },

        /**
         * Range text, e.g. "1-5 dari 20"
         */
        get rangeText() {
            const total = this.sortedItems.length;
            if (total === 0) return '0 dari 0';
            const start = (this.currentPage - 1) * this.perPage + 1;
            const end = Math.min(this.currentPage * this.perPage, total);
            return `${start}-${end} dari ${total}`;
        },

        /**
         * Array of page numbers for pagination buttons.
         * Shows max 5 pages with ellipsis logic.
         */
        get pageNumbers() {
            const total = this.totalPages;
            const current = this.currentPage;
            const pages = [];

            if (total <= 7) {
                for (let i = 1; i <= total; i++) pages.push(i);
            } else {
                pages.push(1);
                if (current > 3) pages.push('...');
                
                const start = Math.max(2, current - 1);
                const end = Math.min(total - 1, current + 1);
                for (let i = start; i <= end; i++) pages.push(i);
                
                if (current < total - 2) pages.push('...');
                pages.push(total);
            }
            return pages;
        },

        // --- Actions ---

        /**
         * Toggle sorting on a column. Click once = asc, again = desc, again = clear.
         */
        toggleSort(column) {
            if (this.sortColumn === column) {
                if (this.sortDirection === 'asc') {
                    this.sortDirection = 'desc';
                } else {
                    // Reset sort
                    this.sortColumn = '';
                    this.sortDirection = 'asc';
                }
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
            this.currentPage = 1;
        },

        /**
         * Get the sort icon state for a column header.
         * Returns: 'asc', 'desc', or 'none'
         */
        sortState(column) {
            if (this.sortColumn !== column) return 'none';
            return this.sortDirection;
        },

        goToPage(page) {
            if (page === '...' || page < 1 || page > this.totalPages) return;
            this.currentPage = page;
        },

        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },

        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },

        changePerPage(val) {
            this.perPage = parseInt(val);
            this.currentPage = 1;
        },

        /**
         * Watch: reset to page 1 whenever filters change.
         * Call this in your init() or x-effect if needed.
         */
        resetPage() {
            this.currentPage = 1;
        }
    };
}

// Make it globally available
window.sigDataTable = sigDataTable;
