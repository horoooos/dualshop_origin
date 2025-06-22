@extends('layouts.app')
@section('content')
@php
    $shopLat = 54.378940;
    $shopLon = 48.588676;
    $address = request('address');
    $userLat = null;
    $userLon = null;
    $userAddress = null;
    $distance = null;
    $deliveryType = null;
    $deliveryPrice = null;
    $error = null;
    if ($address) {
        $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($address);
        $opts = [
            'http' => [
                'header' => "User-Agent: MyDeliveryApp/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $json = @file_get_contents($url, false, $context);
        $data = json_decode($json, true);
        if ($data && count($data) > 0) {
            $userLat = $data[0]['lat'];
            $userLon = $data[0]['lon'];
            $userAddress = $data[0]['display_name'] ?? null;
            $earthRadius = 6371;
            $dLat = deg2rad($userLat - $shopLat);
            $dLon = deg2rad($userLon - $shopLon);
            $a = sin($dLat/2) * sin($dLat/2) +
                cos(deg2rad($shopLat)) * cos(deg2rad($userLat)) *
                sin($dLon/2) * sin($dLon/2);
            $c = 2 * atan2(sqrt($a), sqrt(1-$a));
            $distance = $earthRadius * $c;
            if ($distance <= 40) {
                $deliveryType = 'Курьерская доставка';
                $deliveryPrice = 999 + round($distance * 30);
            } else {
                $deliveryType = 'Доставка СДЭК';
                $deliveryPrice = 1500 + round(($distance - 40) * 20);
            }
        } else {
            $error = 'Адрес не найден. Попробуйте указать подробнее.';
        }
    }
    $mapUrl = 'https://static-maps.yandex.ru/1.x/?l=map&size=650,220&z=11';
    $mapUrl .= '&pt=' . $shopLon . ',' . $shopLat . ',pm2rdm';
    if ($userLat && $userLon) {
        $mapUrl .= '~' . $userLon . ',' . $userLat . ',pm2blm';
    }
@endphp
<div class="container py-4 custom-delivery-page">
    <h1 class="about__title mb-4">Доставка товаров</h1>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="get" class="mb-4">
                        <label for="address" class="form-label card-title text-black">Введите ваш адрес:</label>
                        <div class="input-group mb-3">
                            <input type="text" name="address" id="address" class="form-control" placeholder="Например: Ульяновск, Гончарова 1" value="{{ old('address', $address) }}">
                            <button type="submit" class="btn btn-dark">Рассчитать</button>
                        </div>
                    </form>
                    @if($error)
                        <div class="alert alert-danger mb-3 text-black" style="background:#f8f9fa; border:1px solid #e3e3e3;">{{ $error }}</div>
                    @endif
                    @if($userLat && $userLon && !$error)
                        @php
                            $shortAddress = $userAddress;
                            if ($userAddress) {
                                $parts = explode(',', $userAddress);
                                $parts = array_map('trim', $parts);
                                // Ищем дом (цифра), улицу и город
                                $house = null; $street = null; $city = null;
                                foreach ($parts as $part) {
                                    if (!$house && preg_match('/^\d+[A-Za-zА-Яа-я]?$/u', $part)) $house = $part;
                                    if (!$street && (stripos($part, 'ул') !== false || stripos($part, 'улица') !== false)) $street = $part;
                                    if (!$city && (stripos($part, 'г.') !== false || stripos($part, 'город') !== false || preg_match('/[А-Яа-яA-Za-z\- ]+$/u', $part))) $city = $part;
                                }
                                // Если не нашли город, берём второй элемент (обычно город)
                                if (!$city && isset($parts[2])) $city = $parts[2];
                                // Собираем короткий адрес
                                $shortAddress = trim(implode(', ', array_filter([$city, $street, $house])));
                            }
                        @endphp
                        <div class="card mb-3" style="background: #f8f9fa; border: 1px solid #e3e3e3;">
                            <div class="card-body">
                                <div class="card-title text-black" style="font-size: 1.1rem; color: #28a745;">
                                    <i class="bi bi-truck"></i> {{ $deliveryType }}
                                </div>
                                <div class="card-text mb-2 text-black">
                                    <span>Расстояние до магазина:</span> <b>{{ round($distance, 2) }} км</b>
                                </div>
                                <div class="card-text mb-2 text-black">
                                    <span>Стоимость:</span> <b style="color:#28a745; font-size:1.2rem;">{{ $deliveryPrice }} ₽</b>
                                </div>
                                @if($shortAddress)
                                    <div class="card-text mb-2 text-black">
                                        <span>Вы:</span> <span class="user-address">{{ $shortAddress }}</span>
                                    </div>
                                @endif
                                <div class="card-text text-muted" style="font-size:0.95rem;">
                                    @if($deliveryType === 'Курьерская доставка')
                                        Базовая стоимость 999 ₽ + 30 ₽ за каждый км
                                    @else
                                        Базовая стоимость 1500 ₽ + 20 ₽ за каждый км сверх 40 км
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="custom-map-card">
                        <img src="{{ $mapUrl }}" alt="Карта доставки" class="custom-map-img">
                        <div class="map-legend">
                            <span class="map-dot map-dot-red"></span> <b>Здесь находится dualshop</b><br>
                            @if($userLat && $userLon && $shortAddress)
                                <span class="map-dot map-dot-blue"></span> <b>Вы</b>: <span class="user-address">{{ $shortAddress }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card" style="background: #fafafa; border: 1px solid #eee;">
                <div class="card-body">
                    <h4 class="fw-bold text-black mb-3" style="font-size:1.2rem;">Дополнительная информация</h4>
                    <div class="accordion" id="deliveryAccordion">
                        <div class="accordion-item" style="background: #f7f7f7; border: none;">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed text-black" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="background: #f7f7f7;">
                                    Экспресс-доставка «День в день»
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#deliveryAccordion">
                                <div class="accordion-body text-black">
                                    Быстрая доставка в день заказа при наличии товара на складе.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" style="background: #f7f7f7; border: none;">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed text-black" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="background: #f7f7f7;">
                                    Обычная доставка
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#deliveryAccordion">
                                <div class="accordion-body text-black">
                                    Доставка осуществляется в течение 1-3 дней после подтверждения заказа.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" style="background: #f7f7f7; border: none;">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed text-black" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="background: #f7f7f7;">
                                    Условия доставки
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#deliveryAccordion">
                                <div class="accordion-body text-black">
                                    Доставка осуществляется по городу и области. Подробности уточняйте у оператора.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" style="background: #f7f7f7; border: none;">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed text-black" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="background: #f7f7f7;">
                                    Прием товара от курьера
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#deliveryAccordion">
                                <div class="accordion-body text-black">
                                    Проверьте комплектность и внешний вид товара при получении.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" style="background: #f7f7f7; border: none;">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed text-black" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" style="background: #f7f7f7;">
                                    Доставка юридическим лицам
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#deliveryAccordion">
                                <div class="accordion-body text-black">
                                    Возможна доставка по безналичному расчету для организаций.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item" style="background: #f7f7f7; border: none;">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed text-black" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix" style="background: #f7f7f7;">
                                    Наши контакты
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#deliveryAccordion">
                                <div class="accordion-body text-black">
                                    Телефон: +7 (962) 635-26-94<br>Email: dualshop@gmail.com
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.custom-map-card {
    background: #fff;
    border: 1px solid #e3e3e3;
    border-radius: 8px;
    margin-top: 24px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.10);
    position: relative;
    overflow: hidden;
}
.custom-map-img {
    width: 100%;
    object-fit: cover;
    border-radius: 0 0 8px 8px;
    display: block;
}
.map-legend {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(255,255,255,0.95);
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 1rem;
    color: #000;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    font-weight: 500;
}
.map-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 6px;
    vertical-align: middle;
}
.map-dot-red { background: #d00; }
.map-dot-blue { background: #007bff; }
</style>
@endsection 