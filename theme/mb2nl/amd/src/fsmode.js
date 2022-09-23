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
 */ define(["jquery"],function($){return{sidebarToggle:function(){var a=$(".fsmod-wrap");$(".fsmod-showhide-sidebar").each(function(){$(this).click(function(b){b.preventDefault(),a.hasClass("issidebar")?(a.removeClass("issidebar"),M.util.set_user_preference("fsmod-open-nav","false")):(a.addClass("issidebar"),M.util.set_user_preference("fsmod-open-nav","true")),a.hasClass("ismsidebar")?a.removeClass("ismsidebar"):a.addClass("ismsidebar")})}),$(".fsmod-toggle-sidebar>button").click(function(b){$(this).hasClass("toggle-blocks")?(a.removeClass("issection"),a.addClass("isblock"),M.util.set_user_preference("fsmod-toggle-sections","block")):(a.removeClass("isblock"),a.addClass("issection"),M.util.set_user_preference("fsmod-toggle-sections","section"))})}}})
