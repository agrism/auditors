import './bootstrap';
import './accounting.js';

// Ensure only one dropdown can be active/open at any time & configure Popper for table menus
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('show.bs.dropdown', (event) => {
        // Close other open dropdowns
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

        // Ensure table action dropdowns use fixed Popper strategy to avoid overflow clipping
        const toggle = event.target;
        if (toggle && (toggle.classList.contains('eds-action-btn') || toggle.closest('.table-responsive') || toggle.closest('.card-modern'))) {
            if (window.bootstrap && window.bootstrap.Dropdown) {
                bootstrap.Dropdown.getOrCreateInstance(toggle, {
                    boundary: document.body,
                    popperConfig(defaultBsPopperConfig) {
                        return {
                            ...defaultBsPopperConfig,
                            strategy: 'fixed',
                            modifiers: [
                                ...(defaultBsPopperConfig.modifiers || []),
                                {
                                    name: 'preventOverflow',
                                    options: {
                                        boundary: document.body,
                                        padding: 8
                                    }
                                },
                                {
                                    name: 'flip',
                                    options: {
                                        boundary: document.body,
                                        fallbackPlacements: ['top-end', 'bottom-end', 'top-start', 'bottom-start']
                                    }
                                }
                            ]
                        };
                    }
                });
            }
        }
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
