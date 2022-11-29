/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
require('bootstrap');

import headerPath from './images/retro-wave.jpg';

let html = `<img src="${headerPath}" alt="image of retro wave">`;

import logoPath from './images/logo-wild-serie.png';

let logo = `<img src="${logoPath}" alt="image of retro wave">`;