<p>{{ $reservation->user->name }}様</p>

<p>以下のご予約日は本日です。</p>

<ul>
    <li>店名：{{ $reservation->shop->name }}</li>
    <li>ご予約日：{{ $reservation->date->format('Y年n月j日(D)') }}</li>
    <li>時間：{{ $reservation->time->format('H:i') }}</li>
    <li>人数：{{ $reservation->number }}人</li>
</ul>

<p>ご来店時に以下QRコードをご提示ください</p>
{!! $qrCode !!}

<p>ご来店をお待ちしております。</p>