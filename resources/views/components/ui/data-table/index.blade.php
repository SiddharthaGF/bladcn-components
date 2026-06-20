@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/data-table --}}

@props([
    'rows' => [],
    'pageSize' => 10,
    'defaultSortColumn' => null,
    'defaultSortDirection' => 'asc',
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'data-table',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class(['flex w-full flex-col gap-4', $class]) }}
    x-data="bladcnDataTable({
        rows: @js($rows),
        pageSize: @js($pageSize),
        defaultSortColumn: @js($defaultSortColumn),
        defaultSortDirection: @js($defaultSortDirection),
    })">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnDataTable', (config = {}) => ({
                rows: config.rows ?? [],
                search: '',
                page: 1,
                pageSize: config.pageSize ?? 10,
                sortColumn: config.defaultSortColumn ?? null,
                sortDirection: config.defaultSortDirection ??
                    'asc',

                get filteredRows() {
                    let rows = [...this.rows];

                    if (this.search.trim()) {
                        const query = this.search.trim()
                            .toLowerCase();

                        rows = rows.filter((row) =>
                            Object.values(row).some((
                                    value) =>
                                String(value ?? '')
                                .toLowerCase()
                                .includes(query),
                            ),
                        );
                    }

                    if (this.sortColumn) {
                        rows.sort((left, right) => {
                            const a = left[this
                                .sortColumn];
                            const b = right[this
                                .sortColumn];

                            if (a === b) {
                                return 0;
                            }

                            if (a === null || a ===
                                undefined) {
                                return 1;
                            }

                            if (b === null || b ===
                                undefined) {
                                return -1;
                            }

                            const result = String(a)
                                .localeCompare(
                                    String(b),
                                    undefined, {
                                        numeric: true,
                                    });

                            return this
                                .sortDirection ===
                                'asc' ? result : -
                                result;
                        });
                    }

                    return rows;
                },

                get pageCount() {
                    return Math.max(1, Math.ceil(this
                        .filteredRows.length / this
                        .pageSize));
                },

                get paginatedRows() {
                    const start = (this.page - 1) * this
                        .pageSize;

                    return this.filteredRows.slice(start,
                        start + this.pageSize);
                },

                get showingFrom() {
                    if (this.filteredRows.length === 0) {
                        return 0;
                    }

                    return (this.page - 1) * this.pageSize +
                        1;
                },

                get showingTo() {
                    return Math.min(this.page * this
                        .pageSize, this.filteredRows
                        .length);
                },

                toggleSort(column) {
                    if (this.sortColumn === column) {
                        this.sortDirection = this
                            .sortDirection === 'asc' ? 'desc' :
                            'asc';

                        return;
                    }

                    this.sortColumn = column;
                    this.sortDirection = 'asc';
                },

                sortIcon(column) {
                    if (this.sortColumn !== column) {
                        return '↕';
                    }

                    return this.sortDirection === 'asc' ? '↑' :
                        '↓';
                },

                goToPage(page) {
                    this.page = Math.min(Math.max(1, page), this
                        .pageCount);
                },

                nextPage() {
                    this.goToPage(this.page + 1);
                },

                previousPage() {
                    this.goToPage(this.page - 1);
                },

                init() {
                    this.$watch('search', () => {
                        this.page = 1;
                    });
                },
            }));
        });
    </script>
@endPushOnce
