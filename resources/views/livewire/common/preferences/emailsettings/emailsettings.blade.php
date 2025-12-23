{{-- <div class="container my-4">

    <form wire:submit.prevent="save" class="radius12 bg-white settingforms">
        <div class="row g-4">

            <div class="mb-1">
                <label for="title" class="form-label">Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('subject') is-invalid @enderror" wire:model="subject"
                    placeholder="Email Subject">
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-1">
                <label class="form-label">Message <span class="text-danger">*</span></label>
                <textarea class="form-control @error('message') is-invalid @enderror" wire:model="message" rows="8"></textarea>
                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <label class="form-label">Placeholder</label>
                <div class="col-md-10">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($placeholders as $placeholder)
                            <span onclick="insertAtCursor('[{{ $placeholder }}]')"
                                class="badge bg-light text-primary border border-primary px-3 py-2"
                                style="cursor: pointer;">
                                {{ $placeholder }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>



            <div class="col-12 mt-4 d-flex justify-content-end">
                <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                    Save Changes
                    <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"
                        wire:loading.delay></span>
                </button>
            </div>
        </div>
    </form>
</div>
@push('scripts')
    <script>
        let activeInput = null;

        function setupEmailEditorListeners() {
            const subjectInput = document.querySelector('input[wire\\:model="subject"]');
            const messageTextarea = document.querySelector('textarea[wire\\:model="message"]');

            if (subjectInput) {
                subjectInput.addEventListener('focus', () => {
                    activeInput = subjectInput;
                });
            }

            if (messageTextarea) {
                messageTextarea.addEventListener('focus', () => {
                    activeInput = messageTextarea;
                });
            }

            window.insertAtCursor = function(placeholder) {
                if (!activeInput) return;

                const start = activeInput.selectionStart;
                const end = activeInput.selectionEnd;

                const textBefore = activeInput.value.substring(0, start);
                const textAfter = activeInput.value.substring(end);

                activeInput.value = textBefore + placeholder + textAfter;

                const cursorPosition = start + placeholder.length;
                activeInput.setSelectionRange(cursorPosition, cursorPosition);
                activeInput.focus();

                activeInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            };
        }

        window.addEventListener('email-settings-loaded', () => {
            setupEmailEditorListeners();
        });
        document.addEventListener('DOMContentLoaded', () => {
            setupEmailEditorListeners();
        });
    </script>
@endpush --}}
<div class="container my-4">

    <form wire:submit.prevent="save" class="radius12 bg-white settingforms">
        <div class="row g-4">

            <!-- Subject Input -->
            <div class="mb-1">
                <label for="title" class="form-label">Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('subject') is-invalid @enderror" wire:model="subject"
                    placeholder="Email Subject">
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Message Textarea (TinyMCE) -->
            <div class="mb-1" wire:ignore>
                <label class="form-label">Message <span class="text-danger">*</span></label>
                <!-- TinyMCE Editor -->
                <textarea id="message" class="form-control @error('message') is-invalid @enderror" wire:model="message"></textarea>
                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Placeholder Section -->
            <div class="mb-1">
                <label class="form-label">Placeholder</label>
                <div class="col-md-10">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($placeholders as $placeholder)
                            <span onclick="copyToClipboard('[{{ $placeholder }}]', this)"
                                class="badge bg-light text-primary border border-primary px-3 py-2 copy-placeholder"
                                style="cursor: pointer;">
                                {{ $placeholder }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-12 mt-4 d-flex justify-content-end">
                <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                    Save Changes
                    <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"
                        wire:loading.delay></span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/703e60ik4bbf0tgpid8nx2ir9yzwu22hdo6ab11waghkcofx/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        function initializeTinyMCE() {
            tinymce.init({
                selector: '#message',
                menubar: false,
                toolbar: 'undo redo | bold italic underline | link | bullist numlist | blockquote | alignleft aligncenter alignright',
                plugins: 'link lists',
                setup: function(editor) {
                    editor.on('init', function() {});
                }
            });
        }

        window.copyToClipboard = function(placeholder, el) {
            const input = document.createElement('input');
            input.value = placeholder;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);

            const originalText = el.textContent;
            el.textContent = "Copied!";
            el.classList.add('bg-success', 'text-black');

            setTimeout(() => {
                el.textContent = originalText;
                el.classList.remove('bg-success', 'text-black');
            }, 2000);
        };

        window.addEventListener('email-settings-loaded', function() {
            tinymce.remove('#message');
            initializeTinyMCE();
        });

        document.addEventListener('DOMContentLoaded', function() {
            initializeTinyMCE();
        });
    </script>
@endpush
