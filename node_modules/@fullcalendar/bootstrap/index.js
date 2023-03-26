import { createPlugin } from '@fullcalendar/core';
import { BootstrapTheme } from './internal.js';
import { injectStyles } from '@fullcalendar/core/internal';

var css_248z = ".fc-theme-bootstrap a:not([href]){color:inherit}.fc-theme-bootstrap .fc-more-link:hover{text-decoration:none}";
injectStyles(css_248z);

var index = createPlugin({
    name: '@fullcalendar/bootstrap',
    themeClasses: {
        bootstrap: BootstrapTheme,
    },
});

export { index as default };
