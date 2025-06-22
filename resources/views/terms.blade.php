@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="terms-container">
        <h1 class="terms-title">Правила сайта</h1>
        
        <div class="terms-section">
            <h2>1. Общие положения</h2>
            <p>1.1. Настоящие Правила определяют условия использования сайта и являются соглашением между пользователем и администрацией сайта.</p>
            <p>1.2. Используя сайт, вы соглашаетесь с данными правилами в полном объеме.</p>
        </div>

        <div class="terms-section">
            <h2>2. Регистрация на сайте</h2>
            <p>2.1. При регистрации на сайте пользователь обязуется предоставить достоверную информацию о себе.</p>
            <p>2.2. Пользователь несет ответственность за сохранность своего логина и пароля.</p>
            <p>2.3. Запрещается передавать свои учетные данные третьим лицам.</p>
        </div>

        <div class="terms-section">
            <h2>3. Правила поведения</h2>
            <p>3.1. Пользователь обязуется соблюдать законодательство РФ при использовании сайта.</p>
            <p>3.2. Запрещается:</p>
            <ul>
                <li>Размещать недостоверную информацию</li>
                <li>Использовать нецензурную лексику</li>
                <li>Размещать спам и рекламу без согласования с администрацией</li>
                <li>Нарушать права других пользователей</li>
            </ul>
        </div>

        <div class="terms-section">
            <h2>4. Заказы и оплата</h2>
            <p>4.1. Пользователь обязуется указывать достоверную информацию при оформлении заказов.</p>
            <p>4.2. Оплата производится способами, указанными на сайте.</p>
            <p>4.3. Администрация сайта оставляет за собой право отменить заказ в случае нарушения правил.</p>
        </div>

        <div class="terms-section">
            <h2>5. Конфиденциальность</h2>
            <p>5.1. Администрация сайта обязуется не разглашать персональные данные пользователей.</p>
            <p>5.2. Обработка персональных данных осуществляется в соответствии с законодательством РФ.</p>
        </div>

        <div class="terms-section">
            <h2>6. Ответственность</h2>
            <p>6.1. Администрация сайта не несет ответственности за:</p>
            <ul>
                <li>Действия пользователей на сайте</li>
                <li>Содержание размещаемой пользователями информации</li>
                <li>Технические сбои и перерывы в работе сайта</li>
            </ul>
        </div>
    </div>
</div>

<style>
.terms-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}

.terms-title {
    font-size: 2rem;
    font-weight: 600;
    color: #000;
    margin-bottom: 2rem;
    text-align: center;
}

.terms-section {
    margin-bottom: 2rem;
}

.terms-section h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #000;
    margin-bottom: 1rem;
}

.terms-section p {
    color: #4B5563;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.terms-section ul {
    list-style-type: disc;
    margin-left: 1.5rem;
    color: #4B5563;
}

.terms-section li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .terms-container {
        padding: 1.5rem;
        margin: 0 1rem;
    }

    .terms-title {
        font-size: 1.75rem;
    }
}
</style>
@endsection 