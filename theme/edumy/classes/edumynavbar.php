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

namespace theme_edumy;

use core\navigation\views\view;
use moodle_page;
use navigation_node;
use moodle_url;
use action_link;
use lang_string;

/**
 * Creates a navbar for edumy that allows easy control of the navbar items.
 *
 * @package    theme_edumy
 * @copyright  2021 Adrian Greeve <adrian@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class edumynavbar implements \renderable {

    /** @var array The individual items of the navbar. */
    protected $items = [];
    /** @var moodle_page The current moodle page. */
    protected $page;

    /**
     * Takes a navbar object and picks the necessary parts for display.
     *
     * @param \moodle_page $page The current moodle page.
     */
    public function __construct(\moodle_page $page) {
        $this->page = $page;
        foreach ($this->page->navbar->get_items() as $item) {
            $this->items[] = $item;
        }
        $this->prepare_nodes_for_edumy();
    }

    /**
     * Prepares the navigation nodes for use with edumy.
     */
    protected function prepare_nodes_for_edumy(): void {
        $url = new moodle_url('/my/courses.php');
        $this->update_action('courses',$url);
    }

    /**
     * Get all the edumynavbaritem elements.
     *
     * @return edumynavbaritem[] edumy navbar items.
     */
    public function get_items(): array {
        return $this->items;
    }

    /**
     * Retrieve a single navbar item.
     *
     * @param  string|int $key The identifier of the navbar item to return.
     * @return \breadcrumb_navigation_node|null The navbar item.
     */
    protected function get_item($key): ?\breadcrumb_navigation_node {
        foreach ($this->items as $item) {
            if ($key === $item->key) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Update a edumynavbaritem action in the edumy navbar.
     *
     * @param  string|int $itemkey An identifier for the edumynavbaritem
     * @param moodle_url|null $url An additional type identifier for the edumynavbaritem (optional)
     */
    protected function update_action($itemkey, $url): void {

        foreach ($this->items as $item) {
            if ($item->key === $itemkey) {
                // If a type identifier is also specified, check whether the type of the breadcrumb item matches the
                // specified type. Skip if types to not match.
                $item->action = $url;
                break;
            }
        }
    }

}
