document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('photoFileInput');
    if (!input) return;

    const preview = document.getElementById('photoPreview');

    input.addEventListener('change', function (e) {
        const file = input.files && input.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            if (preview) {
                preview.src = ev.target.result;
            }
        };
        reader.readAsDataURL(file);
    });
});
