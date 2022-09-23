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
 */ define(["jquery"],function($){return{tocInit:function(){$(".theme-toc").closest(".generalbox").find("h3,h4,h5,h6").each(function(){var a=$(this).html().replace(/[^a-z0-9]/gi,"_");$(this).attr("id",a.trim())})},courseToc:function(){$(".coursetoc-section-toggle").click(function(b){var a=$(this).closest(".coursetoc-section");a.hasClass("active")?a.removeClass("active"):a.addClass("active")})},courseTocScroll:function(){var a=$(".coursetoc-sectionlist").find("li.active");if(!a.length)return null;var d=a.offset().top,b=$(window).height(),c=Math.ceil(d-b+b/4);c>0&&$(".fsmod-sections-wrap").scrollTop(c)}}})
