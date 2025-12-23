<script data-navigate-once>
    function datepickerInitialize() {
        document.querySelectorAll(".datepicker").forEach((el) => {
            if (el.classList.contains("datepicker-initialized")) return;

            const dateFormat = el.dataset.format || "Y-m-d";
            const safariDateAfter = parseInt(el.dataset.start || 0);
            const NoDateAfter = parseInt(el.dataset.nostart || 0);
            const restrictFuture = el.dataset.restrictFuture === "true";

            let maxDate = null;
            let minDate = null;

            if (NoDateAfter == 0) {
                minDate = new Date(new Date().toDateString());
                if (safariDateAfter > 0) {
                    minDate.setDate(minDate.getDate() + safariDateAfter);
                }
            } else if (restrictFuture) {
                maxDate = new Date();
                minDate = null;
            } else {
                minDate = null;
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
                defaultDate:defaultDate,
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
        if (role && group) {
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
    
    window.addEventListener('open-new-item-modal', function () {
        setTimeout(() => datepickerInitialize(), 50);
    });
</script>