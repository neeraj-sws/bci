<script>
    document.addEventListener("livewire:init", () => {
        const rightCanvas = document.getElementById("rightCanvas");
        const backdrop = document.getElementById("canvasBackdrop");

        const openCanvas = () => {
            rightCanvas.classList.add("active");
            backdrop.style.display = "block";
        };

        const closeCanvas = () => {
            rightCanvas.classList.remove("active");
            backdrop.style.display = "none";
        };

        document.getElementById("closeCanvas").onclick = closeCanvas;
        backdrop.onclick = closeCanvas;

        // Livewire event to open offcanvas
        window.addEventListener("open-offcanvas", () => {
            openCanvas();
        });
        window.addEventListener("close-offcanvas", () => {
            closeCanvas();
        });
    });
</script>
