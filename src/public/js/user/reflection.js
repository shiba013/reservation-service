//入力データの即時反映
const selectDate = document.getElementById('selectDate');
const outputDate = document.getElementById('selectedDate');

selectDate.addEventListener('change', function () {
    const selectedDate = selectDate.value;
    outputDate.textContent = selectedDate;
});

const selectTime = document.getElementById('selectTime');
const outputTime = document.getElementById('selectedTime');

const selectNumber = document.getElementById('selectNumber');
const outputNumber = document.getElementById('selectedNumber');

//即時反映しつつ選択可能人数調整
selectTime.addEventListener('change', function () {
    const selectedOption = selectTime.options[selectTime.selectedIndex];
    const selectedTime = selectedOption.text;
    outputTime.textContent = selectedTime;

    const remaining = selectedOption.dataset.remaining;
    selectNumber.innerHTML = '<option value="" hidden>人数を選択してください</option>';

    if (remaining) {
        const max = parseInt(remaining, 10);
        for (let i = 1; i <= max; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i + '人';
            selectNumber.appendChild(option);
        }
    }
});

selectNumber.addEventListener('change', function () {
    const selectedNumber = selectNumber.options[selectNumber.selectedIndex].text;
    outputNumber.textContent = selectedNumber;
});