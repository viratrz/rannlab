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
 */ define(["jquery"],function($){var a=function(a){var c=a.keyCode?a.keyCode:a.which,b='a,button,[type="submit"],input,.nav-link';$(""+b).focus(function(){"true"!==$("body").attr("data-keydown")||$("body").hasClass("pagelayout-mb2builder")||9!=c||$(this).addClass("themefocused"),$("body").attr("data-keydown","false")}),$(""+b).focusout(function(){$(this).removeClass("themefocused")})};return{focusClass:function(){$(window).keydown(function(b){$("body").attr("data-keydown","true"),a(b)})},accessibilityTools:function(){$(".accessibility-tools > li").each(function(){$(this).find("button").click(function(c){var b=$(this),a=b.attr("data-id");b.hasClass("contrast")&&($("body").hasClass(a)?($("body").removeClass(a),b.removeClass("active"),M.util.set_user_preference("theme_"+a,0)):($("body").removeClass("contrast1"),$("body").removeClass("contrast2"),$("body").removeClass("contrast3"),$("body").removeClass("contrast4"),$("button.contrast").removeClass("active"),M.util.set_user_preference("theme_contrast1",0),M.util.set_user_preference("theme_contrast2",0),M.util.set_user_preference("theme_contrast3",0),M.util.set_user_preference("theme_contrast4",0),$("body").addClass(a),b.addClass("active"),M.util.set_user_preference("theme_"+a,1)))})})}}})
