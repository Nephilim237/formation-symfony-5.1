/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../styles/app.css';
import '../css/main.scss';
import 'bootstrap';
import 'popper.js';
require('./bootstrap-datepicker-1.9.0.min');
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

global.$ = $;
