//日付選択->時間選択->人数選択の反映・入力データの即時反映
document.addEventListener('DOMContentLoaded', () => {
    const urlParts = window.location.pathname.split('/');
    const shopId = urlParts[2];
    const selectDate = document.getElementById('selectDate');
    const outputDate = document.getElementById('selectedDate');
    const selectTime = document.getElementById('selectTime');
    const outputTime = document.getElementById('selectedTime');
    const selectNumber = document.getElementById('selectNumber');
    const outputNumber = document.getElementById('selectedNumber');

    //日付選択
    selectDate.addEventListener('change', function () {
        const selectedDate = selectDate.value;
        outputDate.textContent = selectedDate;

        //初期化設定
        selectTime.innerHTML = '<option value="" hidden>時間を選択してください</option>';
        selectNumber.innerHTML = '<option value="" hidden>人数を選択してください</option>';
        outputTime.textContent = '';
        outputNumber.textContent = '';
        if (!selectedDate) return;

        //選択日付の空き時間取得
        fetch(`/detail/slots/${shopId}?date=${selectedDate}`)
            .then(response => response.json())
            .then(slots => {
                //予約枠が上限に達した場合
                if (slots.length === 0 || slots.every(slot => slot.remaining_number <= 0)) {
                    selectTime.innerHTML = '<option value="" hidden>満席です</option>';
                    selectNumber.innerHTML = '';
                    outputTime.textContent = '';
                    outputNumber.textContent = '';
                    return;
                }
                //時間スロット作成
                slots.forEach(slot => {
                    if (slot.remaining_number > 0) {
                        const option = document.createElement('option');
                        option.value = slot.time;
                        option.textContent = `${slot.time}`;
                        option.dataset.remaining = slot.remaining_number;
                        selectTime.appendChild(option);
                    }
                });
            });
    });

    //時間選択時
    selectTime.addEventListener('change', function () {
        const selectedOption = selectTime.options[selectTime.selectedIndex];
        const remaining = selectedOption.dataset.remaining;

        outputTime.textContent = selectedOption.value || '';

        //人数セレクト初期化
        selectNumber.innerHTML = '<option value="" hidden>人数を選択してください</option>';
        outputNumber.textContent = '';

        //予約枠の上限に達した場合
        if (!remaining || remaining == 0) {
            selectNumber.innerHTML = '<option value="" hidden>満席です</option>';
            return;
        }

        if (remaining) {
            const max = parseInt(remaining, 10);
            for (let i = 1; i <= max; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${i}人`;
                selectNumber.appendChild(option);
            }
        }
    });

    // 人数選択時
    selectNumber.addEventListener('change', function () {
        outputNumber.textContent = selectNumber.value;
    });
});