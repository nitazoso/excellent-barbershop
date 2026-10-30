const adminMenuToggle = document.querySelector('.admin-menu-toggle');
const adminMenuClose = document.querySelector('.admin-menu-close');
const adminShell = document.querySelector('.admin-shell');

adminMenuToggle?.setAttribute(
    'aria-expanded',
    String(!window.matchMedia('(max-width: 520px)').matches),
);

function toggleAdminSidebar(forceOpen = null) {
    const isMobile = window.matchMedia('(max-width: 520px)').matches;
    const className = isMobile ? 'sidebar-open' : 'sidebar-collapsed';
    const isOpen = forceOpen ?? (isMobile
        ? !adminShell.classList.contains(className)
        : adminShell.classList.contains(className));

    adminShell.classList.toggle(className, isMobile ? isOpen : !isOpen);
    adminMenuToggle?.setAttribute('aria-expanded', String(isOpen));
}

adminMenuToggle?.addEventListener('click', () => toggleAdminSidebar());
adminMenuClose?.addEventListener('click', () => toggleAdminSidebar(false));
