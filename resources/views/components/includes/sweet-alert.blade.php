{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script data-navigate-once>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    document.addEventListener('swal:confirm', event => {

        //console.log(window.Livewire);

        // Destructure the event detail correctly
        const {
            action,
            cancelAction,
            title,
            text,
            icon,
            showCancelButton,
            confirmButtonText
        } = event.detail[0];

        // Show the confirmation Swal dialog
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: showCancelButton,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch(action)
            } else if (result.isDismissed) {
                if (cancelAction) {
                    Livewire.dispatch(cancelAction);
                }
            }
        });
    });


    window.addEventListener('swal:toast', event => {
        Toast.fire({
            title: event.detail[0].title,
            text: event.detail[0].message,
            icon: event.detail[0].type,
        });
    });

    window.addEventListener('bs:openmodal', event => {
        jQuery("#" + event.detail.modal).modal("show");
    });

    window.addEventListener('bs:hidemodal', event => {
        jQuery("#" + event.detail.modal).modal("hide");
    });

    @if (Session::has('success'))
        Toast.fire({
            title: '{!! Session::get('success') !!}',
            icon: "success",
            showCloseButton: true,
        });
    @endif
    @if (Session::has('error'))
        Toast.fire({
            title: '{!! Session::get('error') !!}',
            icon: "error",
            showCloseButton: true,
        });
    @endif
    @if (Session::has('warning'))
        Toast.fire({
            title: '{!! Session::get('warning') !!}',
            icon: "warning",
            showCloseButton: true,
        });
    @endif
    @if (Session::has('info'))
        Toast.fire({
            title: '{!! Session::get('info') !!}',
            icon: "info",
            showCloseButton: true,
        });
    @endif
</script> --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script data-navigate-once>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    document.addEventListener('swal:confirm', event => {

        const {
            action,
            cancelAction,
            title,
            text,
            icon,
            showCancelButton,
            confirmButtonText,
            confirmText // new: word to type
        } = event.detail[0];

        let swalOptions = {
            title: title,
            text: text,
            icon: icon,
            showCancelButton: showCancelButton,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
        };

        if (confirmText) {
            swalOptions.input = 'text';
            swalOptions.inputPlaceholder = `Type "${confirmText}" to confirm`;
            swalOptions.inputValidator = (value) => {
                return value.toLowerCase() !== confirmText.toLowerCase() && `You need to type "${confirmText}" to confirm!`;
            }
        }

        Swal.fire(swalOptions).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch(action)
            } else if (result.isDismissed) {
                if (cancelAction) {
                    Livewire.dispatch(cancelAction);
                }
            }
        });
    });



    window.addEventListener('swal:toast', event => {
        Toast.fire({
            title: event.detail[0].title,
            text: event.detail[0].message,
            icon: event.detail[0].type,
        });
    });

    window.addEventListener('bs:openmodal', event => {
        jQuery("#" + event.detail.modal).modal("show");
    });

    window.addEventListener('bs:hidemodal', event => {
        jQuery("#" + event.detail.modal).modal("hide");
    });

    @if (Session::has('success'))
        Toast.fire({
            title: '{!! Session::get('success') !!}',
            icon: "success",
            showCloseButton: true,
        });
    @endif
    @if (Session::has('error'))
        Toast.fire({
            title: '{!! Session::get('error') !!}',
            icon: "error",
            showCloseButton: true,
        });
    @endif
    @if (Session::has('warning'))
        Toast.fire({
            title: '{!! Session::get('warning') !!}',
            icon: "warning",
            showCloseButton: true,
        });
    @endif
    @if (Session::has('info'))
        Toast.fire({
            title: '{!! Session::get('info') !!}',
            icon: "info",
            showCloseButton: true,
        });
    @endif
</script>

