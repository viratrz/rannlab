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
 define(["jquery","theme_mb2nl/swiper"],function(e,t){var a=function(e){var t=e.closest(".mb2-pb-content"),a=e.closest(".mb2-pb-coursetabs");a.length&&(t=a);var r=Number(t.attr("data-columns")),i=1==t.attr("data-mobcolumns"),n=1==t.attr("data-sloop"),s=1==t.attr("data-autoplay"),o=Number(t.attr("data-pausetime")),l=Number(t.attr("data-animtime")),u=30;"thin"===t.attr("data-gutter")?u=10:"none"===t.attr("data-gutter")&&(u=0);var d=i?2:1,p=r>3?3:r,c=o;return n&&s||(c=36e5),{slidesPerView:r,spaceBetween:u,allowTouchMove:!0,loop:n,speed:l,navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},pagination:{el:".swiper-pagination",clickable:!0},autoplay:{delay:c,disableOnInteraction:!1},breakpoints:{0:{slidesPerView:d},480:{slidesPerView:d},600:{slidesPerView:i?p:r>2?2:r},768:{slidesPerView:p},1000:{slidesPerView:r>5?5:r}},watchSlidesProgress:!0}};return{carouselInit:function(r){r?new t("#"+r,a(e("#"+r))):e(".swiper").each(function(){new t("#"+e(this).attr("id"),a(e(this)))})}}});
