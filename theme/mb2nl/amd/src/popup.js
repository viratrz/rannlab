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
define(["jquery","theme_mb2nl/magnific"],function($,a){return{popupImage:function(){$(".popup-image").magnificPopup({type:"image"})},popupIframe:function(){$(".popup-iframe").magnificPopup({type:"iframe"})},popupInline:function(){$(".popup-html_video").each(function(){var a=$(this).attr("href");$(this).magnificPopup({items:{src:'<div class="popup-html-video"><video controls="true"><source src="'+a+'">'+a+"</video></div>"},type:"inline",callbacks:{open:function(){var a=$(this.content);setTimeout(function(){a.find("video")[0].play()},800)},close:function(){$(this.content).find("video")[0].load()}}})})}}})
