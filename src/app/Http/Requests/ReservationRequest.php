<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;
use App\Models\Reservation;
use App\Models\ReservationSlot;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|after_or_equal:today',
            'time' => 'required',
            'number' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => '日付を選択してください',
            'date.after_or_equal' => '本日以降の日付を入力してください',
            'time.required' => '時間を選択してください',
            'number.required' => '人数を選択してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $date = $this->input('date');
            $time = $this->input('time');
            $number = $this->input('number');

            if (strlen($time) === 5) {
                $time .= ':00';
            }

            $reservationId = $this->route('reservation_id');
            $reservation = Reservation::with('slot')
            ->find($reservationId);

            $shopId = $this->route('shop_id') ?? $reservation->shop_id;

            $today = Carbon::today();
            $inputDate = Carbon::parse($date);

            $now = Carbon::now();
            $inputDateTime = Carbon::parse("{$date} {$time}");

            if (empty($date) || $inputDate->lt($today) || empty($time)) {
                return;
            }

            if ($inputDateTime->lt($now)) {
                $validator->errors()->add('time', '現在時刻以降の時間を選択してください');
            }

            $slot = ReservationSlot::where('shop_id', $shopId)
            ->where('date', $date)
            ->where('reserve_start', $time)
            ->first();

            if ($slot->is_active === false) {
                $validator->errors()->add('date', '当日の予約受付は終了しました');
            }

            $reservedNumber = $slot->reservedNumber();
            $remaining = max(0, $slot->max_number - $reservedNumber);

            if ($number > $remaining) {
                $validator->errors()->add('number', '予約人数が上限を超えています');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('reservation_error_id', $this->route('reservation_id'));
        throw new HttpResponseException(
            redirect()->back()->withErrors($validator)->withInput()
        );
    }
}