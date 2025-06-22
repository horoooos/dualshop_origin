document.addEventListener('DOMContentLoaded', function() {
    const catalogButton = document.getElementById('catalog-dropdown-button');
    const catalogDropdown = document.getElementById('catalog-dropdown');
    const categoryItems = document.querySelectorAll('.category-item');
    const categoryContents = document.querySelectorAll('.category-content');
    
    // Установка первой категории как активной по умолчанию
    if (categoryItems.length > 0 && categoryContents.length > 0) {
        categoryItems[0].classList.add('active', 'border-l-4', 'border-blue-500');
        categoryContents[0].classList.remove('hidden');
        categoryContents[0].classList.add('block');
    }
    
    // Переключение категорий при наведении
    categoryItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const categoryId = this.getAttribute('data-category-id');
            
            // Устанавливаем активный класс для текущего элемента
            categoryItems.forEach(cat => {
                cat.classList.remove('active', 'border-l-4', 'border-blue-500');
            });
            this.classList.add('active', 'border-l-4', 'border-blue-500');
            
            // Показываем соответствующий контент
            categoryContents.forEach(content => {
                if (content.getAttribute('data-category-id') === categoryId) {
                    content.classList.remove('hidden');
                    content.classList.add('block');
                } else {
                    content.classList.add('hidden');
                    content.classList.remove('block');
                }
            });
        });
    });
    
    // Показать/скрыть выпадающее меню при клике на кнопку Каталог
    if (catalogButton) {
        catalogButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            catalogDropdown.classList.toggle('hidden');
        });

        // Предотвращение закрытия меню при клике внутри него
        catalogDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Закрытие меню при клике вне его
        document.addEventListener('click', function(e) {
            if (!catalogButton.contains(e.target) && !catalogDropdown.contains(e.target)) {
                catalogDropdown.classList.add('hidden');
            }
        });
    }
    
    // Мобильное поведение
    const isMobile = window.innerWidth < 768;
    if (isMobile) {
        // На мобильных устройствах меняем поведение при клике на категорию
        categoryItems.forEach(item => {
            item.addEventListener('click', function(e) {
                const categoryId = this.getAttribute('data-category-id');
                
                // Устанавливаем активный класс
                categoryItems.forEach(cat => {
                    cat.classList.remove('active', 'border-l-4', 'border-blue-500');
                });
                this.classList.add('active', 'border-l-4', 'border-blue-500');
                
                // Показываем контент
                categoryContents.forEach(content => {
                    if (content.getAttribute('data-category-id') === categoryId) {
                        content.classList.remove('hidden');
                        content.classList.add('block');
                    } else {
                        content.classList.add('hidden');
                        content.classList.remove('block');
                    }
                });
            });
        });
    }
}); 