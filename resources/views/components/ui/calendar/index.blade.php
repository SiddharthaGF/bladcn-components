@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/calendar --}}

@props([
    'selected' => null,
    'showOutsideDays' => true,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'group/calendar w-fit rounded-md border bg-background p-3 [--cell-size:--spacing(8)]',
    );

    $presetAttributes = [
        'data-slot' => 'calendar',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnCalendar({
        selected: @js($selected),
        showOutsideDays: @js($showOutsideDays),
    })">
    <div class="relative flex w-full items-center justify-between gap-1 pb-4">
        <x-ui.button aria-label="Previous month"
            class="size-8"
            size="icon"
            type="button"
            variant="ghost"
            x-on:click="prevMonth()">
            <x-ui.icon aria-hidden="true"
                class="size-4"
                name="chevron-left" />
        </x-ui.button>

        <div class="text-sm font-medium"
            data-slot="calendar-caption"
            x-text="monthLabel"></div>

        <x-ui.button aria-label="Next month"
            class="size-8"
            size="icon"
            type="button"
            variant="ghost"
            x-on:click="nextMonth()">
            <x-ui.icon aria-hidden="true"
                class="size-4"
                name="chevron-right" />
        </x-ui.button>
    </div>

    <div class="grid grid-cols-7 gap-0">
        <template :key="weekday"
            x-for="weekday in weekdays">
            <div class="text-muted-foreground flex size-8 items-center justify-center text-[0.8rem] font-normal"
                data-slot="calendar-weekday"
                x-text="weekday"></div>
        </template>
    </div>

    <template :key="weekIndex"
        x-for="(week, weekIndex) in weeks">
        <div class="mt-2 flex w-full"
            data-slot="calendar-week">
            <template :key="day ? day.key : `empty-${weekIndex}-${dayIndex}`"
                x-for="(day, dayIndex) in week">
                <div class="relative aspect-square size-8 p-0 text-center"
                    data-slot="calendar-day">
                    <template x-if="day">
                        <button
                            class="hover:bg-accent hover:text-accent-foreground focus-visible:ring-ring focus-visible:outline-hidden inline-flex size-8 items-center justify-center rounded-md p-0 text-sm font-normal transition-colors focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-50"
                            type="button"
                            x-bind:class="{
                                'text-muted-foreground opacity-50': day.outside,
                                'bg-accent text-accent-foreground': isToday(
                                    day) && !isSelected(day),
                                'bg-primary text-primary-foreground': isSelected(
                                    day),
                            }"
                            x-bind:data-outside="day.outside ? '' : null"
                            x-bind:data-selected="isSelected(day) ? '' : null"
                            x-bind:data-today="isToday(day) ? '' : null"
                            x-bind:disabled="day.disabled"
                            x-on:click="selectDay(day)"
                            x-text="day.label"></button>
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnCalendar', (config = {}) => ({
                month: config.month ?? new Date().getMonth(),
                year: config.year ?? new Date().getFullYear(),
                selected: config.selected ?? null,
                showOutsideDays: config.showOutsideDays ?? true,
                weekdays: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr',
                    'Sa'
                ],

                get monthLabel() {
                    return new Date(this.year, this.month,
                        1).toLocaleString('default', {
                        month: 'long',
                        year: 'numeric',
                    });
                },

                get weeks() {
                    const firstDay = new Date(this.year,
                        this.month, 1);
                    const daysInMonth = new Date(this.year,
                        this.month + 1, 0).getDate();
                    const startPadding = firstDay.getDay();
                    const cells = [];

                    if (this.showOutsideDays) {
                        const previousMonthDays = new Date(
                                this.year, this.month, 0)
                            .getDate();

                        for (let index = startPadding -
                                1; index >= 0; index -= 1) {
                            const day = previousMonthDays -
                                index;

                            cells.push(this.createDay(this
                                .year, this.month -
                                1, day, true));
                        }
                    } else {
                        for (let index = 0; index <
                            startPadding; index += 1) {
                            cells.push(null);
                        }
                    }

                    for (let day = 1; day <=
                        daysInMonth; day += 1) {
                        cells.push(this.createDay(this.year,
                            this.month, day, false));
                    }

                    if (this.showOutsideDays) {
                        let nextDay = 1;

                        while (cells.length % 7 !== 0) {
                            cells.push(this.createDay(this
                                .year, this.month +
                                1, nextDay, true));
                            nextDay += 1;
                        }
                    } else {
                        while (cells.length % 7 !== 0) {
                            cells.push(null);
                        }
                    }

                    const weeks = [];

                    for (let index = 0; index < cells
                        .length; index += 7) {
                        weeks.push(cells.slice(index,
                            index + 7));
                    }

                    return weeks;
                },

                createDay(year, month, day, outside) {
                    const date = new Date(year, month, day);

                    return {
                        key: `${date.toISOString()}-${outside ? 'outside' : 'inside'}`,
                        date,
                        outside,
                        label: day,
                        disabled: outside && !this
                            .showOutsideDays,
                    };
                },

                prevMonth() {
                    if (this.month === 0) {
                        this.month = 11;
                        this.year -= 1;

                        return;
                    }

                    this.month -= 1;
                },

                nextMonth() {
                    if (this.month === 11) {
                        this.month = 0;
                        this.year += 1;

                        return;
                    }

                    this.month += 1;
                },

                formatDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1)
                        .padStart(2, '0');
                    const day = String(date.getDate()).padStart(
                        2, '0');

                    return `${year}-${month}-${day}`;
                },

                selectDay(day) {
                    if (!day || day.disabled) {
                        return;
                    }

                    this.selected = this.formatDate(day.date);

                    if (!day.outside) {
                        this.month = day.date.getMonth();
                        this.year = day.date.getFullYear();
                    }

                    this.$dispatch('calendar-select', {
                        date: this.selected
                    });
                },

                isSelected(day) {
                    return Boolean(day) && this.selected ===
                        this.formatDate(day.date);
                },

                isToday(day) {
                    if (!day) {
                        return false;
                    }

                    const today = new Date();

                    return day.date.toDateString() === today
                        .toDateString();
                },
            }));
        });
    </script>
@endPushOnce
