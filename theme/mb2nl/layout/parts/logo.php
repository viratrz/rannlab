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

// Import File And Variables********
global $USER, $DB, $CFG, $SESSION;

$customlogin = theme_mb2nl_is_login( true );
$logos = array('logo-light', 'logo-dark', 'logo-small', 'logo-dark-small');

?>
<div class="logo-wrap">
	<div class="main-logo">
		<a href="<?php echo new moodle_url('/'); ?>" title="<?php echo get_site()->fullname; ?>">
			<?php
				$university = $DB->get_record_sql("SELECT mu.university_id FROM mdl_universityadmin mu WHERE mu.userid = $USER->id UNION SELECT muu.university_id FROM mdl_university_user muu WHERE muu.userid =$USER->id");
				if (isset($SESSION->university_id))
				{
					if($SESSION->university_id)
					{
						$logo_path= $DB->get_record('school',array('id' => $SESSION->university_id));
						if($logo_path->logo_path)
						{
							echo "<img src='$_SESSION[logo_path]' style='min-width: 110px; max-height: 60px;'>";
						}
						else 
						{
							$role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
							if ($role->roleid == 9) 
							{
								echo "<a href='$CFG->wwwroot/local/changelogo/index.php'><img src='' alt='Upload Logo' style='min-width: 110px; max-height: 60px;'></a>";
							}
						}
					}
				}	
				
				else
				{
					if (is_siteadmin()) 
					{
						foreach ($logos as $l)
						{
							$src = $l === 'logo-light' ? theme_mb2nl_logo_url() : theme_mb2nl_logo_url( false, $l );
							$svgcls = theme_mb2nl_is_svg($src) ? ' is_svg' : ' no_svg';
							echo '<img class="' . $l . $svgcls . '" src="' . $src . '" alt="' . get_site()->fullname . '">';						
						}
					}
					else 
					{
						echo "<img src='$_SESSION[logo_path]' style='min-width: 110px; max-height: 60px;'>";
					}
					

				}
			?>
		</a>
	</div>
</div>
