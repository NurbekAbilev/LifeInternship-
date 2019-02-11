let input = document.getElementById('attachment');
input.addEventListener('change', function() {
    document.getElementsByClassName('custom-file-label')[0].textContent = input.value;
});