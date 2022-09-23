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
 define(["jquery","core/ajax","core/notification"],function(e,r,o){var t=function(e){this.registerEventListeners()};return t.prototype._getCourse=function(t){var s=e(t.currentTarget).hasClass("course-popover")?e(t.currentTarget):e(t.currentTarget).parent(),a=e(t.currentTarget).hasClass("course-popover")?e(t.currentTarget):e(".popover-"+s.data("course"));if(this._createPopover(s),this._setPosition(s),this._showPopover(s),a.html()||s.hasClass("course-popover"))return null;r.call([{methodname:"theme_mb2nl_course_quickview",args:{course:s.data("course")},done:this._handleResponse.bind(this),fail:o.exception}])},t.prototype._hideCourse=function(r){var o=e(r.currentTarget).hasClass("course-popover")?e(r.currentTarget):e(r.currentTarget).parent();if(o.hasClass("course-popover"))return o.removeClass("open"),null;e(".popover-"+o.data("course")).removeClass("open")},t.prototype._handleResponse=function(r){var o=e(".popover-"+r.courseid+" .course-popover-inner");setTimeout(function(){o.append(r.course),o.removeClass("loading")},200)},t.prototype._createPopover=function(r){if(e(".popover-"+r.attr("data-course")).length||r.hasClass("course-popover"))return null;var o='<div class="course-popover popover-'+r.attr("data-course")+'"><div class="course-popover-inner loading"><div class="span1"></div><div class="span2"></div></div><div class="popover-arrow"></div></div>';e("body").append(o)},t.prototype._setPosition=function(r){if(r.hasClass("course-popover"))return null;var o=e(window),t=e(".popover-"+r.attr("data-course")),s=15;e("body").hasClass("dir-rtl");r.parent().hasClass(".gutter-thin")?s=5:r.parent().hasClass(".gutter-none")&&(s=0),t.removeClass("arrtop"),t.removeClass("arrleft"),t.removeClass("arrright");var a=r.offset().left+r.width()+s,n=r.offset().top;if(r.width()+t.width()+s>o.width()){var i=s+r.offset().left;i+t.width()>o.width()&&(i=o.width()-t.width()-s),t.css({left:i,top:n+r.height()}),t.addClass("arrtop")}else a+t.width()>o.width()?(t.css({left:r.offset().left+s-t.width(),top:n}),t.addClass("arrright")):(t.css({left:a,top:n}),t.addClass("arrleft"))},t.prototype._showPopover=function(r){(r.hasClass("course-popover")?r:e(".popover-"+r.attr("data-course"))).addClass("open")},t.prototype.registerEventListeners=function(){var r=e(document);r.on("mouseenter",".theme-course-item-inner,.course-popover",this._getCourse.bind(this)),r.on("mouseleave",".theme-course-item-inner,.course-popover",this._hideCourse.bind(this))},t});
