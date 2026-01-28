<script data-navigate-once>
    function datepickerInitialize() {
        document.querySelectorAll(".datepicker").forEach((el) => {
            if (el.classList.contains("datepicker-initialized")) return;

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
                    minDate = new Date(startFrom);
                }
            }


            // NEW DEV
            const startFrom = el.dataset.startFrom;
            if (startFrom) {
                minDate = new Date(startFrom);
            }

            const startYear = el.dataset.startYear ? parseInt(el.dataset.startYear) : null;
            let defaultDate = el.value || null;
            if (!defaultDate && startYear) {
                defaultDate = new Date(startYear, 0, 1);
            }


            const instance = flatpickr(el, {
                dateFormat,
                allowInput: true,
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

            // start → end (END = START + 1 day)
            if (role === "start" && dateStr) {
                const minEndDate = new Date(dateStr);
                minEndDate.setDate(minEndDate.getDate() + 1);

                endPicker.set("minDate", minEndDate);

                if (endEl.value && new Date(endEl.value) < minEndDate) {
                    endPicker.clear();
                }
            }

            // end → start (START = END - 1 day)
            if (role === "end" && dateStr) {
                const maxStartDate = new Date(dateStr);
                maxStartDate.setDate(maxStartDate.getDate() - 1);

                startPicker.set("maxDate", maxStartDate);

                if (startEl.value && new Date(startEl.value) > maxStartDate) {
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

    document.addEventListener("livewire:init", () => {
        setTimeout(() => datepickerInitialize(), 50);
    });

    document.addEventListener("livewire:navigated", () => {
        setTimeout(() => datepickerInitialize(), 50);
    });

    window.addEventListener('open-new-item-modal', function() {
        setTimeout(() => datepickerInitialize(), 50);
    });
</script>
