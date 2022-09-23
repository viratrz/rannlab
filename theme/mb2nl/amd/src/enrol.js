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
 */ define(["jquery"],function($){return{contentTabs:function(){$(".enrol-course-navitem").each(function(){$(this).find("button").click(function(){$(".enrol-course-navitem").removeClass("active"),$(".enrol-course-navcontent").removeClass("active"),$(this).closest(".enrol-course-navitem").addClass("active"),$("#"+$(this).attr("aria-controls")).addClass("active")})})},contentOutTabs:function(){$(".out-navitem").each(function(){$(this).click(function(a){a.preventDefault(),$(".enrol-course-navitem").removeClass("active"),$(".enrol-course-navcontent").removeClass("active"),$('button[aria-controls="'+$(this).attr("aria-controls")+'"]').closest(".enrol-course-navitem").addClass("active"),$("#"+$(this).attr("aria-controls")).addClass("active")})})}}})
