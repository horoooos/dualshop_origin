import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Импорт всех картинок из папки images для Vite
const images = import.meta.glob('../media/images/*');
