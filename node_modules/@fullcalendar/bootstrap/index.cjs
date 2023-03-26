'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var index_cjs = require('@fullcalendar/core/index.cjs');
var internalCommon = require('./internal.cjs');
var internal_cjs = require('@fullcalendar/core/internal.cjs');

var css_248z = ".fc-theme-bootstrap a:not([href]){color:inherit}.fc-theme-bootstrap .fc-more-link:hover{text-decoration:none}";
internal_cjs.injectStyles(css_248z);

var index = index_cjs.createPlugin({
    name: '@fullcalendar/bootstrap',
    themeClasses: {
        bootstrap: internalCommon.BootstrapTheme,
    },
});

exports["default"] = index;
