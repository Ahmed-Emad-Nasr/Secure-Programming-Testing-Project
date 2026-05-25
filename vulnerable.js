const runButton = document.getElementById('js-run');
const inputField = document.getElementById('js-input');
const outputField = document.getElementById('js-output');

runButton.addEventListener('click', () => {
    const userInput = inputField.value;

    try {
        const result = eval(userInput);
        outputField.innerHTML = result === undefined ? 'Result: undefined' : result;
    } catch (error) {
        outputField.innerHTML = 'Error: ' + error.message;
    }
});
