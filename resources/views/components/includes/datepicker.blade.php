<script data-navigate-once>
    function datepickerInitialize() {
        document.querySelectorAll(".datepicker").forEach((el) => {
            const dateFormat = el.dataset.format || "Y-m-d";
            const safariDateAfter = parseInt(el.dataset.start || 0);
            const NoDateAfter = parseInt(el.dataset.nostart || 0);
            const restrictFuture = el.dataset.restrictFuture === "true";

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            let minDate = today;
            let maxDate = null;

            let existingDate = null;
            if (el.value) {
                existingDate = new Date(el.value);
                existingDate.setHours(0, 0, 0, 0);
            }

            // RULE 1: existing past date wins over everything
            if (existingDate && existingDate < today) {
                minDate = null;
            } else {
                // RULE 2: apply constraints only for new data
                const startFrom = el.dataset.startFrom;
                if (startFrom) {
                    const seasonStart = new Date(startFrom);
                    // minDate should be the LATER of today or seasonStart
                    minDate = seasonStart > today ? seasonStart : today;
                }
            }

            //  SAFE ADDITION (MAX DATE SUPPORT)
            const endTo = el.dataset.endTo;
            if (endTo) {
                maxDate = new Date(endTo + "T23:59:59");
            }

            const startYear = el.dataset.startYear ? parseInt(el.dataset.startYear) : null;
            let defaultDate = el.value || null;
            if (!defaultDate && startYear) {
                defaultDate = new Date(startYear, 0, 1);
            }

            // Check if Flatpickr already exists on this element
            if (el._flatpickr) {
                // UPDATE existing instance instead of re-initializing
                el._flatpickr.set({
                    minDate: minDate,
                    maxDate: maxDate,
                    defaultDate: defaultDate
                });

                // Clear invalid dates after updating constraints
                if (el.value) {
                    const currentDate = new Date(el.value);
                    currentDate.setHours(0, 0, 0, 0);

                    const isBeforeMin = minDate && currentDate < minDate;
                    const isAfterMax = maxDate && currentDate > maxDate;

                    if (isBeforeMin || isAfterMax) {
                        el._flatpickr.clear();
                    }
                }

                // Re-apply range logic
                if (el.value) {
                    setTimeout(() => applyRangeLogic(el, el.value), 30);
                }

                return; // Skip re-initialization
            }

            // Initialize NEW Flatpickr instance
            const instance = flatpickr(el, {
                dateFormat,
                allowInput: false,
                defaultDate: defaultDate,
                minDate,
                maxDate,
                onReady: function(selectedDates, dateStr) {
                    if (el.value) this.setDate(el.value, true);
                    setTimeout(() => applyRangeLogic(el, el.value), 30);
                },
                onChange: function(selectedDates, dateStr) {
                    applyRangeLogic(el, dateStr);
                },
                onMonthChange: function() {
                    const max = this.config.maxDate;
                    const min = this.config.minDate;

                    if (!max && !min) return;

                    const current = this.currentYear * 12 + this.currentMonth;

                    if (max) {
                        const maxMonth = max.getFullYear() * 12 + max.getMonth();
                        if (current > maxMonth) {
                            this.jumpToDate(max);
                        }
                    }

                    if (min) {
                        const minMonth = min.getFullYear() * 12 + min.getMonth();
                        if (current < minMonth) {
                            this.jumpToDate(min);
                        }
                    }
                },
            });

            el._flatpickr = instance;
            el.classList.add("datepicker-initialized");
        });
    }

    function applyRangeLogic(el, dateStr) {
        const role = el.dataset.role;
        const group = el.dataset.group;

        // 🔹 Keep start/end logic if needed
        if (role && group && el.dataset.range !== "proper") {
            const startEl = document.querySelector(`.datepicker[data-role="start"][data-group="${group}"]`);
            const endEl = document.querySelector(`.datepicker[data-role="end"][data-group="${group}"]`);

            if (startEl && endEl) {
                const startPicker = startEl._flatpickr;
                const endPicker = endEl._flatpickr;
                if (!startPicker || !endPicker) return;

                if (role === "start" && dateStr) {
                    // Follow-up should not be after Travel Date
                    endPicker.set("maxDate", dateStr);

                    // Clear follow-up if it's after travel date
                    if (endEl.value && new Date(endEl.value) > new Date(dateStr)) {
                        endPicker.clear();
                    }
                }
            }
        }

        // 🆕 PROPER RANGE LOGIC (OPT-IN)
        if (el.dataset.range === "proper") {
            const role = el.dataset.role;
            const group = el.dataset.group;
            if (!role || !group) return;

            const startEl = document.querySelector(
                `.datepicker[data-role="start"][data-group="${group}"][data-range="proper"]`
            );
            const endEl = document.querySelector(
                `.datepicker[data-role="end"][data-group="${group}"][data-range="proper"]`
            );

            if (!startEl || !endEl) return;

            const startPicker = startEl._flatpickr;
            const endPicker = endEl._flatpickr;
            if (!startPicker || !endPicker) return;

            // start → end (END = START + 1 day, but not after season end)
            if (role === "start" && dateStr) {
                const minEndDate = new Date(dateStr);
                minEndDate.setDate(minEndDate.getDate() + 1);

                const seasonEnd = endEl.dataset.endTo ?
                    new Date(endEl.dataset.endTo + "T23:59:59") :
                    null;

                // minDate for end picker is START + 1
                endPicker.set("minDate", minEndDate);

                // maxDate for end picker remains the season end
                if (seasonEnd) {
                    endPicker.set("maxDate", seasonEnd);
                }

                // Clear end date if it's before new minDate OR after season end
                if (endEl.value) {
                    const endDate = new Date(endEl.value);
                    endDate.setHours(0, 0, 0, 0);

                    const isBefore = endDate < minEndDate;
                    const isAfter = seasonEnd && endDate > seasonEnd;

                    if (isBefore || isAfter) {
                        endPicker.clear();
                    }
                }
            }

            // end → start (START = END - 1 day, but not before season start)
            if (role === "end" && dateStr) {
                const maxStartDate = new Date(dateStr);
                maxStartDate.setDate(maxStartDate.getDate() - 1);

                const seasonStart = startEl.dataset.startFrom ?
                    new Date(startEl.dataset.startFrom) :
                    null;

                const finalMaxStart = clampDate(maxStartDate, seasonStart, null);

                startPicker.set("maxDate", finalMaxStart);

                if (startEl.value && new Date(startEl.value) > finalMaxStart) {
                    startPicker.clear();
                }
            }
        }

        // 🔸 Handle data-max-from logic (travel_date)
        const maxFromField = el.dataset.maxFrom;
        if (maxFromField) {
            const sourceEl = document.querySelector(`[wire\\:model="${maxFromField}"], #${maxFromField}`);
            if (sourceEl && sourceEl._flatpickr) {
                const sourceDate = sourceEl._flatpickr.selectedDates[0];
                if (sourceDate) {
                    el._flatpickr.set("maxDate", sourceDate);

                    // If follow-up > travel date → clear
                    if (el.value && new Date(el.value) > sourceDate) {
                        el._flatpickr.clear();
                    }
                }
            }
        }
    }

    // document.addEventListener("livewire:init", () => {
    //     setTimeout(() => datepickerInitialize(), 50);
    //     registerLivewireHooks();
    // });

    document.addEventListener("livewire:navigated", () => {
        setTimeout(() => datepickerInitialize(), 50);
    });

    window.addEventListener('open-new-item-modal', function() {
        setTimeout(() => datepickerInitialize(), 50);
    });

    // Listen for Livewire event to update datepicker range dynamically
    document.addEventListener('livewire:init', () => {
        Livewire.on('update-datepicker-range', (data) => {
            const lowestStartDate = data[0]?.lowestStartDate || data.lowestStartDate;
            const highestEndDate = data[0]?.highestEndDate || data.highestEndDate;

            setTimeout(() => updateDatepickerRanges(lowestStartDate, highestEndDate), 50);
        });
    });

    function registerLivewireHooks() {
        if (!window.Livewire) return;

        // Livewire v2
        if (typeof Livewire.hook === 'function') {
            Livewire.hook('message.processed', () => {
                setTimeout(() => datepickerInitialize(), 50);
            });

            // Livewire v3
            Livewire.hook('morph.updated', () => {
                setTimeout(() => datepickerInitialize(), 50);
            });

            Livewire.hook('commit', () => {
                setTimeout(() => datepickerInitialize(), 50);
            });
        }
    }

    /**
     * Update existing Flatpickr instances with new min/max dates
     */
    function updateDatepickerRanges(lowestStartDate, highestEndDate) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        document.querySelectorAll('.datepicker').forEach((el) => {
            // Update data attributes first
            if (lowestStartDate) {
                el.dataset.startFrom = lowestStartDate;
            }
            if (highestEndDate) {
                el.dataset.endTo = highestEndDate;
            }

            // If Flatpickr exists, update it
            if (el._flatpickr) {
                const picker = el._flatpickr;

                // Calculate new min/max dates
                let newMinDate = null;
                let newMaxDate = null;

                // Check for existing date
                let existingDate = null;
                if (el.value) {
                    existingDate = new Date(el.value);
                    existingDate.setHours(0, 0, 0, 0);
                }

                // RULE 1: existing past date wins over everything
                if (existingDate && existingDate < today) {
                    newMinDate = null;
                } else {
                    // RULE 2: apply constraints for new data
                    if (lowestStartDate) {
                        const seasonStart = new Date(lowestStartDate);
                        newMinDate = seasonStart > today ? seasonStart : today;
                    } else {
                        newMinDate = today;
                    }
                }

                if (highestEndDate) {
                    newMaxDate = new Date(highestEndDate + 'T23:59:59');
                }

                // Update Flatpickr instance
                picker.set('minDate', newMinDate);
                picker.set('maxDate', newMaxDate);

                // Clear invalid dates
                if (el.value) {
                    const currentDate = new Date(el.value);
                    currentDate.setHours(0, 0, 0, 0);

                    const isBeforeMin = newMinDate && currentDate < newMinDate;
                    const isAfterMax = newMaxDate && currentDate > newMaxDate;

                    if (isBeforeMin || isAfterMax) {
                        picker.clear();
                    }
                }

                // Re-apply range logic for proper range mode
                const role = el.dataset.role;
                if (role && el.value) {
                    setTimeout(() => applyRangeLogic(el, el.value), 30);
                }
            } else {
                // If Flatpickr doesn't exist yet, initialize it
                datepickerInitialize();
            }
        });
    }

    function clampDate(date, min, max) {
        if (min && date < min) return min;
        if (max && date > max) return max;
        return date;
    }
</script>
