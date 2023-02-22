<?php
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
 *
 */

defined('MOODLE_INTERNAL') || die();

$sitemenu = theme_mb2nl_theme_setting($PAGE,'showsitemnu');
$adminediting = optional_param('adminedit', -1, PARAM_BOOL);

if ( $sitemenu && isloggedin() && ! isguestuser() && ! theme_mb2nl_is_login(true) ) : ?>
<?php if ( isset( $vars['div'] ) && ! $vars['div'] ) : ?>
	<?php echo theme_mb2nl_site_menu(); ?>
<?php else : ?>
<div class="site-menu">
	<div class="container-fluid">
    	<div class="row">
        	<div class="col-md-12" style="min-height:0;">
            	<?php echo theme_mb2nl_site_menu(); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
