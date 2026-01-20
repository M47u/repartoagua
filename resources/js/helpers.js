/**
 * Aguas del Litoral - Helpers JavaScript
 * Sistema de Gestión de Repartos
 */

// ========================================
// Toast Notifications Helper
// ========================================

/**
 * Muestra una notificación toast
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo: success, error, warning, info
 * @param {number} duration - Duración en ms (default: 3000)
 */
window.showToast = function(message, type = 'success', duration = 3000) {
    window.dispatchEvent(new CustomEvent('toast', {
        detail: { message, type, duration }
    }));
};

// ========================================
// Confirmación de Eliminación
// ========================================

/**
 * Solicita confirmación antes de eliminar
 * @param {Event} event - Evento del formulario
 * @param {string} message - Mensaje personalizado
 */
window.confirmDelete = function(event, message = '¿Estás seguro de eliminar este elemento?') {
    if (!confirm(message)) {
        event.preventDefault();
        return false;
    }
    return true;
};

// ========================================
// Formateo de Números
// ========================================

/**
 * Formatea un número como moneda
 * @param {number} amount - Cantidad a formatear
 * @param {string} currency - Moneda (default: MXN)
 */
window.formatCurrency = function(amount, currency = 'MXN') {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: currency
    }).format(amount);
};

/**
 * Formatea un número con separadores de miles
 * @param {number} number - Número a formatear
 */
window.formatNumber = function(number) {
    return new Intl.NumberFormat('es-MX').format(number);
};

// ========================================
// Formateo de Fechas
// ========================================

/**
 * Formatea una fecha de manera legible
 * @param {string|Date} date - Fecha a formatear
 * @param {string} format - Formato: short, medium, long
 */
window.formatDate = function(date, format = 'medium') {
    const dateObj = typeof date === 'string' ? new Date(date) : date;
    
    const formats = {
        short: { day: '2-digit', month: '2-digit', year: 'numeric' },
        medium: { day: '2-digit', month: 'short', year: 'numeric' },
        long: { day: '2-digit', month: 'long', year: 'numeric' }
    };
    
    return new Intl.DateTimeFormat('es-MX', formats[format]).format(dateObj);
};

/**
 * Calcula tiempo relativo (hace X minutos/horas/días)
 * @param {string|Date} date - Fecha a comparar
 */
window.timeAgo = function(date) {
    const dateObj = typeof date === 'string' ? new Date(date) : date;
    const seconds = Math.floor((new Date() - dateObj) / 1000);
    
    const intervals = {
        año: 31536000,
        mes: 2592000,
        semana: 604800,
        día: 86400,
        hora: 3600,
        minuto: 60,
        segundo: 1
    };
    
    for (let [name, secondsInInterval] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / secondsInInterval);
        if (interval >= 1) {
            return interval === 1 
                ? `Hace 1 ${name}`
                : `Hace ${interval} ${name}${interval > 1 && name !== 'mes' ? 's' : name === 'mes' ? 'es' : ''}`;
        }
    }
    
    return 'Justo ahora';
};

// ========================================
// Validación de Formularios
// ========================================

/**
 * Valida un campo de teléfono
 * @param {string} phone - Número de teléfono
 */
window.validatePhone = function(phone) {
    const regex = /^[\d\s\-\(\)]+$/;
    return regex.test(phone) && phone.replace(/\D/g, '').length >= 10;
};

/**
 * Valida un email
 * @param {string} email - Email a validar
 */
window.validateEmail = function(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
};

/**
 * Valida que un campo no esté vacío
 * @param {string} value - Valor a validar
 */
window.validateRequired = function(value) {
    return value !== null && value !== undefined && value.toString().trim().length > 0;
};

// ========================================
// Debounce y Throttle
// ========================================

/**
 * Debounce - Ejecuta una función después de X ms de inactividad
 * @param {Function} func - Función a ejecutar
 * @param {number} wait - Tiempo de espera en ms
 */
window.debounce = function(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

/**
 * Throttle - Limita la ejecución de una función a una vez cada X ms
 * @param {Function} func - Función a ejecutar
 * @param {number} limit - Tiempo límite en ms
 */
window.throttle = function(func, limit = 300) {
    let inThrottle;
    return function executedFunction(...args) {
        if (!inThrottle) {
            func(...args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
};

// ========================================
// Local Storage Helpers
// ========================================

/**
 * Guarda datos en localStorage
 * @param {string} key - Clave
 * @param {any} value - Valor (será convertido a JSON)
 */
window.saveToStorage = function(key, value) {
    try {
        localStorage.setItem(key, JSON.stringify(value));
        return true;
    } catch (e) {
        console.error('Error guardando en localStorage:', e);
        return false;
    }
};

/**
 * Obtiene datos de localStorage
 * @param {string} key - Clave
 * @param {any} defaultValue - Valor por defecto si no existe
 */
window.getFromStorage = function(key, defaultValue = null) {
    try {
        const item = localStorage.getItem(key);
        return item ? JSON.parse(item) : defaultValue;
    } catch (e) {
        console.error('Error leyendo localStorage:', e);
        return defaultValue;
    }
};

/**
 * Elimina un item de localStorage
 * @param {string} key - Clave a eliminar
 */
window.removeFromStorage = function(key) {
    try {
        localStorage.removeItem(key);
        return true;
    } catch (e) {
        console.error('Error eliminando de localStorage:', e);
        return false;
    }
};

// ========================================
// Clipboard Helper
// ========================================

/**
 * Copia texto al portapapeles
 * @param {string} text - Texto a copiar
 */
window.copyToClipboard = async function(text) {
    try {
        await navigator.clipboard.writeText(text);
        showToast('Copiado al portapapeles', 'success', 2000);
        return true;
    } catch (e) {
        console.error('Error copiando al portapapeles:', e);
        showToast('Error al copiar', 'error', 2000);
        return false;
    }
};

// ========================================
// URL Helpers
// ========================================

/**
 * Obtiene parámetros de la URL
 * @param {string} param - Nombre del parámetro
 */
window.getUrlParam = function(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
};

/**
 * Actualiza un parámetro de la URL sin recargar
 * @param {string} param - Nombre del parámetro
 * @param {string} value - Valor del parámetro
 */
window.updateUrlParam = function(param, value) {
    const url = new URL(window.location);
    url.searchParams.set(param, value);
    window.history.pushState({}, '', url);
};

// ========================================
// Alpine.js Custom Directives
// ========================================

document.addEventListener('alpine:init', () => {
    // Directiva para auto-focus
    Alpine.directive('autofocus', (el) => {
        setTimeout(() => el.focus(), 100);
    });
    
    // Directiva para click outside
    Alpine.directive('click-outside', (el, { expression }, { evaluateLater, effect }) => {
        const onClick = evaluateLater(expression);
        
        const clickHandler = (e) => {
            if (!el.contains(e.target)) {
                onClick();
            }
        };
        
        document.addEventListener('click', clickHandler);
        
        // Cleanup
        el._x_clickOutside = clickHandler;
    });
});

// ========================================
// Print Helper
// ========================================

/**
 * Imprime un elemento específico
 * @param {string} elementId - ID del elemento a imprimir
 */
window.printElement = function(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error('Elemento no encontrado:', elementId);
        return;
    }
    
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Imprimir</title>');
    printWindow.document.write('<link rel="stylesheet" href="/css/app.css">');
    printWindow.document.write('</head><body>');
    printWindow.document.write(element.innerHTML);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
};

// ========================================
// Búsqueda en Tablas
// ========================================

/**
 * Filtra filas de una tabla basado en búsqueda
 * @param {string} searchTerm - Término de búsqueda
 * @param {string} tableId - ID de la tabla
 */
window.searchTable = function(searchTerm, tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    const term = searchTerm.toLowerCase();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
};

// ========================================
// Inicialización
// ========================================

console.log('✅ Aguas del Litoral Helpers cargados correctamente');

// Event listener para mostrar toasts desde Laravel flash messages
document.addEventListener('DOMContentLoaded', () => {
    // Verificar si hay mensajes flash de Laravel
    const flashMessage = document.querySelector('[data-flash-message]');
    if (flashMessage) {
        const message = flashMessage.dataset.flashMessage;
        const type = flashMessage.dataset.flashType || 'info';
        showToast(message, type);
    }
});
