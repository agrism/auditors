import './bootstrap';
import './accounting.js';

// Ensure only one dropdown can be active/open at any time
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('show.bs.dropdown', (event) => {
        document.querySelectorAll('.dropdown-menu.show').forEach((openMenu) => {
            const toggle = openMenu.closest('.dropdown')?.querySelector('[data-bs-toggle="dropdown"]');
            if (toggle && toggle !== event.target) {
                if (window.bootstrap && window.bootstrap.Dropdown) {
                    const instance = bootstrap.Dropdown.getInstance(toggle);
                    if (instance) {
                        instance.hide();
                    }
                }
                openMenu.classList.remove('show');
                toggle.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    document.addEventListener('click', (event) => {
        const item = event.target.closest('.dropdown-item');
        if (item) {
            const dropdown = item.closest('.dropdown');
            const toggle = dropdown?.querySelector('[data-bs-toggle="dropdown"]');
            if (toggle && window.bootstrap && window.bootstrap.Dropdown) {
                const instance = bootstrap.Dropdown.getInstance(toggle);
                if (instance) {
                    instance.hide();
                }
            }
        }
    });
});
