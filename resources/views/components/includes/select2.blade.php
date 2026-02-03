<script data-navigate-once>
    function select2Initialize() {
        $("select.select2").each(function() {
            const $this = $(this);
            const selectId = $this.attr("id");
            const placeholder = $this.attr("placeholder") || "Select an option";
 
            if ($this.hasClass("select2-initialized")) return;
 
            $this.select2({
                placeholder
            }).on("change", function() {
                const value = $(this).val();
                const compId = $(this).closest("[wire\\:id]").attr("wire:id");
                const component = Livewire.find(compId);
                if (component) component.set(selectId, value);
            });
 
            if ($this.hasClass("is-invalid")) {
                $this.next(".select2-container").find(".select2-selection").addClass("is-invalid");
            }
            addClearButton($this); // ✅ HERE
            $this.addClass("select2-initialized");
        });
    }
    function addClearButton($select) {
        if (!$select.data('clearable')) return;

        const $container = $select.next('.select2-container');
        const $selection = $container.find('.select2-selection');

        if ($selection.find('.select2-clear-btn').length) return;

        const $clear = $(`
            <span class="select2-clear-btn"
                style="
                    position:absolute;
                    right:25px;
                    top:50%;
                    transform:translateY(-50%);
                    cursor:pointer;
                    font-size:12px;
                    color:red;
                    display:none;
                    z-index:3;
                ">×</span>
        `);

        $clear.on('click', function (e) {
            e.stopPropagation();

            $select.val(null).trigger('change.select2');

            const compId = $select.closest('[wire\\:id]').attr('wire:id');
            const component = Livewire.find(compId);
            if (component) component.set($select.attr('id'), null);

            $clear.hide();
        });

        // make room for ❌
        $selection.css({
            position: 'relative',
            paddingRight: '22px'
        });

        $selection.append($clear);

        // toggle visibility on value change
        $select.on('change.select2', function () {
            const hasValue = !!$(this).val();
            $clear.toggle(hasValue);
        });

        // initial state
        if ($select.val()) {
            $clear.show();
        }
    }
        // NEW THING 
    $(document).on('select2:open', function(e) {
        const $select = $(e.target);
        const label = $select.data('show-add');
        const callback = $select.data('add-callback');

        if (!label || $('#add-new-item').length) return;

        setTimeout(() => {
            const $btn = $(`
                <div id="add-new-item"
                    style="padding:8px;text-align:center;cursor:pointer;color:#007bff;border-top:1px solid #eee;">
                    ${label}
                </div>
            `);

            $('.select2-results').append($btn);

            $btn.on('click', () => {
                const compId = $select.closest('[wire\\:id]').attr('wire:id');
                const component = Livewire.find(compId);
                $select.select2('close');
                if (component && callback) {
                    component.call(callback); // ✅ This calls the Livewire method!
                } else {
                    console.warn('Livewire component or callback not found');
                }
            });
        }, 0);
    });
 
    document.addEventListener("livewire:init", select2Initialize);
    document.addEventListener("livewire:navigated", select2Initialize);
    Livewire.hook("morphed", () => select2Initialize());
    
     window.addEventListener('open-new-item-modal', () => {
        select2Initialize()
    });
    
    // NEW DEV
    $(document).on('select2:open', () => {
        setTimeout(() => {
            document.querySelector('.select2-search__field')?.focus();
        }, 0);
    });
    
    
</script>