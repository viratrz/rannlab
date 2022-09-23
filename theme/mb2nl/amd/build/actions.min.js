// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2022 Mariusz Boloz (https://mb2themes.com)
 * @license   Commercial https://themeforest.net/licenses
 */
 define(["jquery"],function(e){return{Init:function(){e(".menu-extra-controls-btn").each(function(){e(this).click(function(n){var s=e(this),o=e(".menu-searchcontainer"),a=e(".menu-staticontentcontainer");s.hasClass("menu-extra-controls-search")?s.hasClass("open")?(o.removeClass("open"),e(".menu-extra-controls-btn").removeClass("open")):(a.removeClass("open"),e(".menu-extra-controls-content").removeClass("open"),o.addClass("open"),e(".menu-extra-controls-search").addClass("open")):s.hasClass("menu-extra-controls-content")&&(s.hasClass("open")?(a.removeClass("open"),e(".menu-extra-controls-btn").removeClass("open")):(o.removeClass("open"),e(".menu-extra-controls-search").removeClass("open"),a.addClass("open"),e(".menu-extra-controls-content").addClass("open")))})})}}});
