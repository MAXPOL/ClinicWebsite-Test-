// Автоматическая отправка формы при вводе 10 символов
document.addEventListener('DOMContentLoaded', function() {
    const accessCodeInput = document.getElementById('access_code');
    if (accessCodeInput) {
        accessCodeInput.addEventListener('input', function() {
            if (this.value.length === 10) {
                this.form.submit();
            }
        });
    }    
    // Инициализация модальных окон Bootstrap
    var modalEl = document.getElementById('editModal');
    if (modalEl) {
        var modal = new bootstrap.Modal(modalEl);
    }
});
