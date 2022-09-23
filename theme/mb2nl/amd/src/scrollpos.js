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
define(["jquery"],function($){return{panelLink:function(){$("body"),$(document).on("click",".save-location",function(){0==$(this).attr("data-scrollpos")?M.util.set_user_preference("theme-scrollpos",window.pageYOffset):M.util.set_user_preference("theme-scrollpos",0)}),$(".save-location").attr("data-scrollpos")>0&&(window.scrollTo(0,$(".save-location").attr("data-scrollpos")),M.util.set_user_preference("theme-scrollpos",0))}}})
