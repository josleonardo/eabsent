function dismissToast(type) {
    const toast = document.getElementById('toast_' + type);
    toast.style.display = 'none';
}

function toggleActive() {
    const checkbox = document.getElementById('active_checkbox');
    const hiddenInput = document.getElementById('active');
    const activeText = document.getElementById('active_text');

    if (checkbox.checked) {
        hiddenInput.value = "1";
        activeText.textContent = "Active";
    } else {
        hiddenInput.value = "0";
        activeText.textContent = "Inactive";
    }
}