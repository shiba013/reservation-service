//入力データの即時反映
const selectDate = document.getElementById('selectDate');
const outputDate = document.getElementById('selectedDate');

selectDate.addEventListener('change', function () {
    const selectedDate = selectDate.value;
    outputDate.textContent = selectedDate;
});

const selectTime = document.getElementById('selectTime');
const outputTime = document.getElementById('selectedTime');

selectTime.addEventListener('change', function () {
    const selectedTime = selectTime.options[selectTime.selectedIndex].text;
    outputTime.textContent = selectedTime;
});

const selectNumber = document.getElementById('selectNumber');
const outputNumber = document.getElementById('selectedNumber');

selectNumber.addEventListener('change', function () {
    const selectedNumber = selectNumber.options[selectNumber.selectedIndex].text;
    outputNumber.textContent = selectedNumber;
});