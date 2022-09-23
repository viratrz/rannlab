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
 define(["jquery","theme_mb2nl/inview"],function(n,t){return{animnumInit:function(){n(".pbanimnum-item,.mb2-pb-animnum_item").each(function(){var t=n(this),i=!0;t.on("inview",function(n,e){e&&i&&(i=!1,setTimeout(function(){var n=t.find(".pbanimnum-number"),i=t.closest(".mb2-pb-animnum").attr("data-aspeed");i=Number(i),t.prop("Counter",0).stop().animate({Counter:t.attr("data-number")},{duration:i,easing:"swing",step:function(t){n.text(this.Counter.toFixed())}})},600))})})}}});
