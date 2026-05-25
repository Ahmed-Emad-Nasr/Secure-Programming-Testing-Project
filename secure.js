const runButton = document.getElementById('js-run');
const inputField = document.getElementById('js-input');
const outputField = document.getElementById('js-output');

runButton.addEventListener('click', () => {
    const userInput = inputField.value;
    outputField.textContent = userInput.trim() || 'Please enter a message.';
});
