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
 * H5P player class.
 *
 * @package    core_h5p
 * @copyright  2019 Sara Arjona <sara@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_h5p;

defined('MOODLE_INTERNAL') || die();

use core_h5p\local\library\autoloader;
use core_xapi\local\statement\item_activity;

/**
 * H5P player class, for displaying any local H5P content.
 *
 * @package    core_h5p
 * @copyright  2019 Sara Arjona <sara@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class player {

    /**
     * @var string The local H5P URL containing the .h5p file to display.
     */
    private $url;

    /**
     * @var core The H5PCore object.
     */
    private $core;

    /**
     * @var int H5P DB id.
     */
    private $h5pid;

    /**
     * @var array JavaScript requirements for this H5P.
     */
    private $jsrequires = [];

    /**
     * @var array CSS requirements for this H5P.
     */
    private $cssrequires = [];

    /**
     * @var array H5P content to display.
     */
    private $content;

    /**
     * @var string optional component name to send xAPI statements.
     */
    private $component;

    /**
     * @var string Type of embed object, div or iframe.
     */
    private $embedtype;

    /**
     * @var context The context object where the .h5p belongs.
     */
    private $context;

    /**
     * @var factory The \core_h5p\factory object.
     */
    private $factory;

    /**
     * @var stdClass The error, exception and info messages, raised while preparing and running the player.
     */
    private $messages;

    /**
     * @var bool Set to true in scripts that can not redirect (CLI, RSS feeds, etc.), throws exceptions.
     */
    private $preventredirect;

    /**
     * Inits the H5P player for rendering the content.
     *
     * @param string $url Local URL of the H5P file to display.
     * @param stdClass $config Configuration for H5P buttons.
     * @param bool $preventredirect Set to true in scripts that can not redirect (CLI, RSS feeds, etc.), throws exceptions
     * @param string $component optional moodle component to sent xAPI tracking
     * @param bool $skipcapcheck Whether capabilities should be checked or not to get the pluginfile URL because sometimes they
     *     might be controlled before calling this method.
     */
    public function __construct(string $url, \stdClass $config, bool $preventredirect = true, string $component = '',
            bool $skipcapcheck = false) {
        if (empty($url)) {
            throw new \moodle_exception('h5pinvalidurl', 'core_h5p');
        }
        $this->url = new \moodle_url($url);
        $this->preventredirect = $preventredirect;

        $this->factory = new \core_h5p\factory();

        $this->messages = new \stdClass();

        $this->component = $component;

        // Create \core_h5p\core instance.
        $this->core = $this->factory->get_core();

        // Get the H5P identifier linked to this URL.
        list($file, $this->h5pid) = api::create_content_from_pluginfile_url(
            $url,
            $config,
            $this->factory,
            $this->messages,
            $this->preventredirect,
            $skipcapcheck
        );
        if ($file) {
            $this->context = \context::instance_by_id($file->get_contextid());
            if ($this->h5pid) {
                // Load the content of the H5P content associated to this $url.
                $this->content = $this->core->loadContent($this->h5pid);

                // Get the embedtype to use for displaying the H5P content.
                $this->embedtype = core::determineEmbedType($this->content['embedType'], $this->content['library']['embedTypes']);
            }
        }
    }

    /**
     * Get the encoded URL for embeding this H5P content.
     *
     * @param string $url Local URL of the H5P file to display.
     * @param stdClass $config Configuration for H5P buttons.
     * @param bool $preventredirect Set to true in scripts that can not redirect (CLI, RSS feeds, etc.), throws exceptions
     * @param string $component optional moodle component to sent xAPI tracking
     * @param bool $displayedit Whether the edit button should be displayed below the H5P content.
     *
     * @return string The embedable code to display a H5P file.
     */
    public static function display(string $url, \stdClass $config, bool $preventredirect = true,
            string $component = '', bool $displayedit = false): string {
        global $OUTPUT, $CFG;

        $params = [
                'url' => $url,
                'preventredirect' => $preventredirect,
                'component' => $component,
            ];

        $optparams = ['frame', 'export', 'embed', 'copyright'];
        foreach ($optparams as $optparam) {
            if (!empty($config->$optparam)) {
                $params[$optparam] = $config->$optparam;
            }
        }
        $fileurl = new \moodle_url('/h5p/embed.php', $params);

        $template = new \stdClass();
        $template->embedurl = $fileurl->out(false);

        if ($displayedit) {
            list($originalfile, $h5p) = api::get_original_content_from_pluginfile_url($url, $preventredirect, true);
            if ($originalfile) {
                // Check if the user can edit this content.
                if (api::can_edit_content($originalfile)) {
                    $template->editurl = $CFG->wwwroot . '/h5p/edit.php?url=' . $url;
                }
            }
        }

        $result = $OUTPUT->render_from_template('core_h5p/h5pembed', $template);
        $result .= self::get_resize_code();
        return $result;
    }

    /**
     * Get the error messages stored in our H5P framework.
     *
     * @return stdClass with framework error messages.
     */
    public function get_messages(): \stdClass {
        return helper::get_messages($this->messages, $this->factory);
    }

    /**
     * Create the H5PIntegration variable that will be included in the page. This variable is used as the
     * main H5P config variable.
     */
    public function add_assets_to_page() {
        global $PAGE;

        $cid = $this->get_cid();
        $systemcontext = \context_system::instance();

        $disable = array_key_exists('disable', $this->content) ? $this->content['disable'] : core::DISABLE_NONE;
        $displayoptions = $this->core->getDisplayOptionsForView($disable, $this->h5pid);

        $contenturl = \moodle_url::make_pluginfile_url($systemcontext->id, \core_h5p\file_storage::COMPONENT,
            \core_h5p\file_storage::CONTENT_FILEAREA, $this->h5pid, null, null);
        $exporturl = $this->get_export_settings($displayoptions[ core::DISPLAY_OPTION_DOWNLOAD ]);
        $xapiobject = item_activity::create_from_id($this->context->id);
        $contentsettings = [
            'library'         => core::libraryToString($this->content['library']),
            'fullScreen'      => $this->content['library']['fullscreen'],
            'exportUrl'       => ($exporturl instanceof \moodle_url) ? $exporturl->out(false) : '',
            'embedCode'       => $this->get_embed_code($this->url->out(),
                $displayoptions[ core::DISPLAY_OPTION_EMBED ]),
            'resizeCode'      => self::get_resize_code(),
            'title'           => $this->content['slug'],
            'displayOptions'  => $displayoptions,
            'url'             => $xapiobject->get_data()->id,
       