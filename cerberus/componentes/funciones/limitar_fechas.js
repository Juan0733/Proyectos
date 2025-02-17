const desdeInput = document.getElementById('desde_calendario');
const hastaInput = document.getElementById('hasta_calendario');

desdeInput.addEventListener('change', function () {
    hastaInput.min = this.value;
});

hastaInput.addEventListener('change', function() {
    desdeInput.max = this.value;
});
