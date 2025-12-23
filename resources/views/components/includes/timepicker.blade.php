<script data-navigate-once>
    function timepickerInitialize() {
        document.querySelectorAll(".timepicker").forEach((el) => {
            if (el.classList.contains("timepicker-initialized")) return;

            const timeFormat = el.dataset.format || "H:i"; // 24h format

            const instance = flatpickr(el, {
                enableTime: true,
                noCalendar: true,
                dateFormat: timeFormat,
                allowInput: true,
                defaultDate: el.value || null,
                time_24hr: false, // or false if you want 12-hour format
                onReady: function(selectedDates, timeStr) {
                    if (el.value) {
                        this.setDate(el.value, true);
                    }
                },
                onChange: function(selectedDates, timeStr) {
                    // Add any custom logic if needed on time change
                }
            });

            el._flatpickr = instance;
            el.classList.add("timepicker-initialized");
        });
    }

    document.addEventListener("livewire:init", () => {
        setTimeout(() => timepickerInitialize(), 50);
    });

    document.addEventListener("livewire:navigated", () => {
        setTimeout(() => timepickerInitialize(), 50);
    });

    Livewire.hook("morphed", () => {
        setTimeout(() => timepickerInitialize(), 50);
    });
</script>