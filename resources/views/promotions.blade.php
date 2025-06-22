@extends('layouts.app')

@section('styles')
<style>
    .about-company {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px 15px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .about-company h1 {
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        font-size: 24px;
        color: #02111b;
        margin-bottom: 1.5rem;
    }
    
    .about-company-intro {
        margin-bottom: 3rem;
    }
    
    .about-company-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.1rem;
        line-height: 1.6;
        color: #3d3d3d;
        margin-bottom: 1rem;
    }
    
    .about-company-history {
        margin-bottom: 3rem;
    }
    
    .about-company-history h2 {
        font-family: 'Roboto', sans-serif;
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #02111b;
    }
    
    .history-timeline {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        overflow-x: auto;
        padding-bottom: 1rem;
    }
    
    .history-item {
        min-width: 180px;
        text-align: center;
        position: relative;
        padding: 1rem;
    }
    
    .history-item::after {
        content: '';
        position: absolute;
        top: 30px;
        right: -50%;
        width: 100%;
        height: 1px;
        background-color: #ddd;
        z-index: 0;
    }
    
    .history-item:last-child::after {
        display: none;
    }
    
    .history-year {
        font-family: 'Roboto', sans-serif;
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
        background-color: white;
        display: inline-block;
        padding: 0 10px;
        border-radius: 15px;
    }
    
    .history-event {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
        color: #3d3d3d;
    }
    
    .about-company-stats {
        background-color: #000;
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 3rem;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        box-shadow: 0 3px 3px -1px rgba(0,0,0,0.25);
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem;
    }
    
    .stat-number {
        font-family: 'Roboto', sans-serif;
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-description {
        font-family: 'Roboto', sans-serif;
        font-size: 0.9rem;
    }
    
    .about-company-values {
        margin-bottom: 3rem;
    }
    
    .about-company-values h2 {
        font-family: 'Roboto', sans-serif;
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #02111b;
    }
    
    .values-container {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
    }
    
    .value-group {
        flex: 1;
        min-width: 300px;
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .value-group:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .value-group h3 {
        font-family: 'Roboto', sans-serif;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        color: #02111b;
    }
    
    .value-group h3 i {
        margin-right: 0.5rem;
        color: #000;
    }
    
    .value-group ul {
        list-style: none;
        padding-left: 0;
    }
    
    .value-group ul li {
        font-family: 'Roboto', sans-serif;
        margin-bottom: 0.8rem;
        position: relative;
        padding-left: 1.5rem;
        color: #3d3d3d;
    }
    
    .value-group ul li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #000;
        font-weight: bold;
    }
    
    .catalog-showcase {
        background-color: #fff;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 3rem;
        box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .catalog-showcase:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .catalog-showcase h2 {
        font-family: 'Roboto', sans-serif;
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #02111b;
    }
    
    .catalog-showcase p {
        font-family: 'Roboto', sans-serif;
        margin-bottom: 1.5rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        color: #3d3d3d;
    }
    
    .catalog-button {
        display: inline-block;
        background-color: #000;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-family: 'Roboto', sans-serif;
        transition: background-color 0.3s;
    }
    
    .catalog-button:hover {
        background-color: #333;
        color: white;
    }
    
    @media (max-width: 768px) {
        .history-timeline {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .history-item {
            width: 100%;
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .history-item::after {
            display: none;
        }
        
        .about-company-stats {
            flex-direction: column;
        }
        
        .stat-item {
            width: 100%;
            margin-bottom: 1.5rem;
        }
        
        .value-group {
            min-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4 about-company">
    <h1 class="about__title mb-4">О магазине комплектующих для ПК</h1>

    <div class="about-company-intro">
        <p>DualShop — ваш надежный партнер в мире сборки и модернизации персональных компьютеров. Мы специализируемся на предоставлении широкого ассортимента высококачественных комплектующих от ведущих мировых брендов, помогая как опытным энтузиастам, так и новичкам собрать ПК своей мечты.</p>
        <p>Наша миссия — сделать процесс выбора и покупки комплектующих максимально простым, удобным и доступным, предлагая конкурентные цены, квалифицированную поддержку и быструю доставку.</p>
    </div>

    <div class="about-company-history">
        <h2>Наша история</h2>
        <div class="history-timeline">
            <div class="history-item">
                <div class="history-year">2010 г.</div>
                <div class="history-event">Основание компании, начало пути в мире электроники.</div>
            </div>
            <div class="history-item">
                <div class="history-year">2012 г.</div>
                <div class="history-event">Запуск онлайн-платформы, расширение ассортимента.</div>
            </div>
            <div class="history-item">
                <div class="history-year">2015 г.</div>
                <div class="history-event">Фокус на комплектующих для ПК, налаживание прямых поставок.</div>
            </div>
            <div class="history-item">
                <div class="history-year">2018 г.</div>
                <div class="history-event">Расширение партнерской сети, увеличение ассортимента эксклюзивных брендов.</div>
            </div>
            <div class="history-item">
                <div class="history-year">2022 г.</div>
                <div class="history-event">Внедрение сервисов подбора и сборки ПК на заказ.</div>
            </div>
        </div>
    </div>

    <div class="about-company-stats">
        <div class="stat-item">
            <div class="stat-number">10 000+</div>
            <div class="stat-description">Довольных сборщиков ПК</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">5 000+</div>
            <div class="stat-description">Успешных заказов комплектующих</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">300+</div>
            <div class="stat-description">Производителей в каталоге</div>
        </div>
    </div>

    <div class="about-company-values">
        <h2>Наши ценности</h2>
        <div class="values-container">
            <div class="value-group">
                <h3><i class="bi bi-gem"></i> Для клиентов</h3>
                <ul>
                    <li>Индивидуальный подход к каждому запросу</li>
                    <li>Только качественные и проверенные компоненты</li>
                    <li>Прозрачные условия покупки и гарантии</li>
                    <li>Оперативная доставка и поддержка</li>
                </ul>
            </div>
            <div class="value-group">
                <h3><i class="bi bi-people"></i> Для партнеров</h3>
                <ul>
                    <li>Взаимовыгодное долгосрочное сотрудничество</li>
                    <li>Честные и прозрачные условия работы</li>
                    <li>Оперативные расчеты и предсказуемость</li>
                    <li>Совместное развитие и рост</li>
                </ul>
            </div>
            <div class="value-group">
                <h3><i class="bi bi-building"></i> Для сотрудников</h3>
                <ul>
                    <li>Комфортные условия для работы и развития</li>
                    <li>Корпоративное обучение и рост</li>
                    <li>Конкурентная заработная плата</li>
                    <li>Дружный коллектив энтузиастов</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="catalog-showcase">
        <h2>Соберите или обновите свой ПК!</h2>
        <p>В нашем каталоге вы найдете все необходимое для создания мощного и надежного компьютера: процессоры, материнские платы, видеокарты, оперативную память, накопители, блоки питания, корпуса и системы охлаждения. Наши специалисты всегда готовы помочь с выбором и ответить на ваши вопросы.</p>
        <a href="{{ route('catalog.categories') }}" class="catalog-button">Перейти в каталог комплектующих</a>
    </div>
</div>
@endsection 