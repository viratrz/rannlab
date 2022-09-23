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
 */ define(["jquery","core/ajax","core/notification","theme_mb2nl/carousel"],function($,b,c,d){var a=function(a){this.registerEventListeners()};return a.prototype._getCourses=function(e){e.preventDefault(),$(".mb2-pb-coursetabs").removeClass("coursetabs-active");var d=$(e.currentTarget),a=$(e.currentTarget).closest(".mb2-pb-coursetabs");a.addClass("coursetabs-active");var f=$(".coursetabs-active #"+d.attr("data-uniqid")+"_category-content-"+d.attr("data-category"));if($(".coursetabs-active .coursetabs-catitem").removeClass("active"),d.addClass("active"),$(".coursetabs-active .coursetabs-content").removeClass("active"),f.addClass("active"),f.hasClass("fillin"))return null;f.addClass("loading"),b.call([{methodname:"theme_mb2nl_coursetabs",args:{category:d.attr("data-category"),filtertype:a.attr("data-filtertype"),limit:a.attr("data-limit"),carousel:a.attr("data-carousel"),columns:a.attr("data-columns"),gutter:a.attr("data-gutter"),catdesc:a.attr("data-catdesc")},done:this._handleResponse.bind(this),fail:c.exception}])},a.prototype._handleResponse=function(c){var a=$(".coursetabs-active .coursetabs-content.active");a.html(c.courses),a.addClass("fillin");var b=a.find(".swiper");b.length&&d.carouselInit(b.attr("id")),a.removeClass("loading")},a.prototype.registerEventListeners=function(){$(document).on("click",".coursetabs-catitem",this._getCourses.bind(this))},a})
