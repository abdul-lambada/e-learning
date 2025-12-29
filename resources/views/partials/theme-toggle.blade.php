{{--
Dark Mode Toggle Component
Include this in your navbar to enable theme switching
This version uses CSS variables override for dark mode since Sneat free doesn't include dark stylesheets
--}}

<li class="nav-item me-2 me-xl-0">
    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);" id="themeSwitcher"
        title="Toggle Dark Mode">
        <i class="bx bx-sun bx-sm" id="themeIconLight"></i>
        <i class="bx bx-moon bx-sm d-none" id="themeIconDark"></i>
    </a>
</li>

@once
    @push('styles')
        <style>
            /* Theme Toggle Button Styles */
            .style-switcher-toggle {
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 0.375rem;
                transition: all 0.2s ease;
            }

            .style-switcher-toggle:hover {
                background-color: rgba(105, 108, 255, 0.08);
            }

            /* Dark Mode CSS Variables Override */
            html.dark-mode {
                --bs-body-bg: #232333;
                --bs-body-color: #a3a4cc;
            }

            html.dark-mode body {
                background-color: #232333 !important;
                color: #a3a4cc !important;
            }

            /* Layout */
            html.dark-mode .layout-menu,
            html.dark-mode .bg-menu-theme {
                background-color: #2b2c40 !important;
                color: #a3a4cc !important;
            }

            html.dark-mode .layout-menu .menu-inner .menu-item a {
                color: #a3a4cc !important;
            }

            html.dark-mode .layout-menu .menu-inner .menu-item.active>a,
            html.dark-mode .layout-menu .menu-inner .menu-item a:hover {
                color: #696cff !important;
            }

            html.dark-mode .menu-inner-shadow {
                background: linear-gradient(#2b2c40 41%, rgba(43, 44, 64, 0.11) 95%, rgba(43, 44, 64, 0));
            }

            /* Navbar */
            html.dark-mode .layout-navbar,
            html.dark-mode .bg-navbar-theme {
                background-color: #2b2c40 !important;
            }

            html.dark-mode .layout-navbar .navbar-nav .nav-link {
                color: #a3a4cc !important;
            }

            /* Cards */
            html.dark-mode .card {
                background-color: #2b2c40 !important;
                color: #a3a4cc !important;
                border-color: #3b3c56 !important;
            }

            html.dark-mode .card-header,
            html.dark-mode .card-footer {
                border-color: #3b3c56 !important;
            }

            /* Tables */
            html.dark-mode .table {
                color: #a3a4cc !important;
            }

            html.dark-mode .table> :not(caption)>*>* {
                background-color: transparent !important;
                border-bottom-color: #3b3c56 !important;
                color: #a3a4cc !important;
            }

            html.dark-mode .table-hover>tbody>tr:hover>* {
                background-color: rgba(105, 108, 255, 0.08) !important;
            }

            /* Forms */
            html.dark-mode .form-control,
            html.dark-mode .form-select {
                background-color: #232333 !important;
                border-color: #3b3c56 !important;
                color: #a3a4cc !important;
            }

            html.dark-mode .form-control:focus,
            html.dark-mode .form-select:focus {
                border-color: #696cff !important;
            }

            html.dark-mode .form-control::placeholder {
                color: #6c6c89 !important;
            }

            /* Dropdowns */
            html.dark-mode .dropdown-menu {
                background-color: #2b2c40 !important;
                border-color: #3b3c56 !important;
            }

            html.dark-mode .dropdown-item {
                color: #a3a4cc !important;
            }

            html.dark-mode .dropdown-item:hover {
                background-color: rgba(105, 108, 255, 0.08) !important;
            }

            /* Ensure dropdown-user dropdown is always visible when active */
            .dropdown-user .dropdown-menu {
                min-width: 220px;
            }

            /* Footer */
            html.dark-mode .content-footer,
            html.dark-mode .bg-footer-theme {
                background-color: #232333 !important;
                color: #a3a4cc !important;
            }

            /* Badges - Preserve Label colors */
            html.dark-mode .bg-label-primary {
                background-color: rgba(105, 108, 255, 0.16) !important;
            }

            html.dark-mode .bg-label-secondary {
                background-color: rgba(133, 146, 163, 0.16) !important;
            }

            html.dark-mode .bg-label-success {
                background-color: rgba(113, 221, 55, 0.16) !important;
            }

            html.dark-mode .bg-label-danger {
                background-color: rgba(255, 62, 29, 0.16) !important;
            }

            html.dark-mode .bg-label-warning {
                background-color: rgba(255, 171, 0, 0.16) !important;
            }

            html.dark-mode .bg-label-info {
                background-color: rgba(3, 195, 236, 0.16) !important;
            }

            /* Text colors */
            html.dark-mode .text-muted {
                color: #6c6c89 !important;
            }

            html.dark-mode h1,
            html.dark-mode h2,
            html.dark-mode h3,
            html.dark-mode h4,
            html.dark-mode h5,
            html.dark-mode h6 {
                color: #cfd0e8 !important;
            }

            /* Alerts */
            html.dark-mode .alert {
                border-color: rgba(255, 255, 255, 0.1) !important;
            }

            /* Modal */
            html.dark-mode .modal-content {
                background-color: #2b2c40 !important;
                color: #a3a4cc !important;
            }

            /* Toast */
            html.dark-mode .toast {
                background-color: #2b2c40 !important;
            }

            html.dark-mode .toast-header {
                background-color: #2b2c40 !important;
                color: #a3a4cc !important;
                border-bottom-color: #3b3c56 !important;
            }

            /* Progress */
            html.dark-mode .progress {
                background-color: #3b3c56 !important;
            }

            /* Scrollbar */
            html.dark-mode ::-webkit-scrollbar-track {
                background: #232333;
            }

            html.dark-mode ::-webkit-scrollbar-thumb {
                background: #3b3c56;
            }

            /* Content Backdrop */
            html.dark-mode .content-backdrop {
                background-color: #1a1a27;
            }

            /* ApexCharts Dark Mode */
            html.dark-mode .apexcharts-text,
            html.dark-mode .apexcharts-legend-text {
                fill: #a3a4cc !important;
                color: #a3a4cc !important;
            }

            html.dark-mode .apexcharts-gridline {
                stroke: #3b3c56 !important;
            }

            /* Animation for toggle */
            .style-switcher-toggle i {
                transition: transform 0.3s ease;
            }

            .style-switcher-toggle:hover i {
                transform: rotate(15deg);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function() {
                const THEME_KEY = 'app_theme';

                // Get theme from localStorage or default to light
                function getStoredTheme() {
                    return localStorage.getItem(THEME_KEY) || 'light';
                }

                function setStoredTheme(theme) {
                    localStorage.setItem(THEME_KEY, theme);
                }

                // Apply theme to document
                function applyTheme(theme) {
                    const html = document.documentElement;
                    const lightIcon = document.getElementById('themeIconLight');
                    const darkIcon = document.getElementById('themeIconDark');

                    if (theme === 'dark') {
                        html.classList.add('dark-mode');
                        if (lightIcon) lightIcon.classList.add('d-none');
                        if (darkIcon) darkIcon.classList.remove('d-none');
                    } else {
                        html.classList.remove('dark-mode');
                        if (lightIcon) lightIcon.classList.remove('d-none');
                        if (darkIcon) darkIcon.classList.add('d-none');
                    }
                }

                // Apply stored theme immediately (before DOM ready)
                applyTheme(getStoredTheme());

                // Setup click handler after DOM is ready
                document.addEventListener('DOMContentLoaded', function() {
                    const switcher = document.getElementById('themeSwitcher');

                    if (switcher) {
                        // Re-apply to ensure icons are correct
                        applyTheme(getStoredTheme());

                        switcher.addEventListener('click', function(e) {
                            e.preventDefault();
                            const currentTheme = getStoredTheme();
                            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                            setStoredTheme(newTheme);
                            applyTheme(newTheme);
                        });
                    }
                });
            })
            ();
        </script>
    @endpush
@endonce
