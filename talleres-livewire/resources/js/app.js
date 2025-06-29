import './bootstrap';
import Alpine from 'alpinejs';
import '../css/app.css';

window.Alpine = Alpine;

Alpine.start();

// Dark mode persistence
document.addEventListener('alpine:init', () => {
    Alpine.store('darkMode', {
        on: JSON.parse(localStorage.getItem('darkMode')) || false,
        
        toggle() {
            this.on = !this.on;
            localStorage.setItem('darkMode', JSON.stringify(this.on));
        }
    });
});

// Smooth transitions for Livewire
document.addEventListener('livewire:load', function () {
    // Add loading states
    Livewire.hook('message.sent', () => {
        document.body.classList.add('loading');
    });

    Livewire.hook('message.processed', () => {
        document.body.classList.remove('loading');
    });
});

// Auto-hide flash messages
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('[role="alert"]');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 500);
        }, 5000);
    });
});