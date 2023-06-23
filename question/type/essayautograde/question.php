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
 * Essay question definition class.
 *
 * @package    qtype
 * @subpackage essayautograde
 * @copyright  2018 Gordon Bateson (gordon.bateson@gmail.com)
 * @copyright  based on work by 2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// prevent direct access to this script
defined('MOODLE_INTERNAL') || die();

// require the parent class
require_once($CFG->dirroot.'/question/type/essay/question.php');

/**
 * Represents an essayautograde question.
 *
 * We can use almost all the methods from the parent "qtype_essay_question" class.
 * However, we override "make_behaviour" in case automatic grading is required.
 * Additionally, we implement the methods required for automatic grading.
 *
 * @copyright  2018 Gordon Bateson (gordon.bateson@gmail.com)
 * @copyright  based on work by 2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// interface: question_automatically_gradable
// class:     question_graded_automatically
class qtype_essayautograde_question extends qtype_essay_question implements question_automatically_gradable {

    /** @var string */
    public $feedback;

    /** @var int */
    public $feedbackformat;

    /** @var string */
    public $correctfeedback;

    /** @var int */
    public $correctfeedbackformat;

    /** @var string */
    public $incorrectfeedback;

    /** @var int */
    public $incorrectfeedbackformat;

    /** @var string */
    public $partiallycorrectfeedback;

    /** @var int */
    public $partiallycorrectfeedbackformat;

    /** Array of records from the "question_answers" table */
    protected $answers = null;

    /** Information about the latest response */
    protected $currentresponse = null;

    /** Text content of response template */
    protected $responsetemplatetext = null;

    /** Text content of response sample */
    protected $responsesampletext = null;

    /**
     * These variables are only used if needed
     * to detect patterns in a student response
     */
    private static $aliases = null;
    private static $metachars = null;
    private static $flipmetachars = null;

    /**
     * Override "make_behaviour" method in the parent class, "qtype_essay_question",
     * because we may need to autograde the response
     */
    public function make_behaviour(question_attempt $qa, $preferredbehaviour) {
        if ($this->enableautograde) {
            return question_engine::make_archetypal_behaviour($preferredbehaviour, $qa);
        } else {
            return question_engine::make_behaviour('manualgraded', $qa, $preferredbehaviour);
        }
    }

    /**
     * @param moodle_page the page we are outputting to.
     * @return qtype_essay_format_renderer_base the response-format-specific renderer.
     */
    public function get_format_renderer(moodle_page $page) {
        return $page->get_renderer($this->plugin_name(), 'format_' . $this->responseformat);
    }

    /**
     * Use by many of the behaviours to determine whether the student has provided
     * enough of an answer for the question to be graded automatically, or whether
     * it must be considered aborted.
     *
     * @param array $response responses, as returned by
     *        {@link question_attempt_step::get_qt_data()}.
     * @return bool whether this response can be graded.
     */
    public function is_gradable_response(array $response) {
        if ($this->get_validation_error($response)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * In situations where is_gradable_response() returns false, this method
     * should generate a description of what the problem is.
     * @return string the message.
     */
    public function get_validation_error(array $response) {

        // Check we have either a text answer or a file attachment.
        if (empty($response['answer']) && empty($response['attachments'])) {
            return get_string('noresponse', 'quiz');
        }

        // Ensure we have text response, if required
        if ($this->responseformat == 'noinline') {
            $responserequired = 0; // i.e. not required
        } else {
            $responserequired = $this->responserequired;
        }
        if ($responserequired && empty($response['answer'])) {
            return get_string('pleaseinputtext', $this->plugin_name());
        }

        // Ensure we have attachments, if required
        if ($this->itemtype == $this->plugin_constant('ITEM_TYPE_FILES')) {
            $attachmentsrequired = $this->itemcount;
        } else if ($this->attachments) {
            $attachmentsrequired = $this->attachmentsrequired;
        } else {
            $attachmentsrequired = 0;
        }
        if ($attachmentsrequired && empty($response['attachments'])) {
            return get_string('pleaseattachfiles', $this->plugin_name());
        }

        // Check that the answer is not simply the unaltered response template/sample.
        if ($text = $this->get_response_answer_text($response)) {

            $this->set_template_and_sample_text();

            if ($this->is_similar_text($text, $this->responsetemplatetext)) {
                return get_string('responseisnotoriginal', $this->plugin_name());
            }

            if ($this->is_similar_text($text, $this->responsesampletext)) {
                return get_string('responseisnotoriginal', $this->plugin_name());
            }
        }

        // No validation errors.
        return '';
    }

    /**
     * Grade a response to the question, returning a fraction between
     * get_min_fraction() and get_max_fraction(), and the corresponding
     * {@link question_state} right, partial or wrong.
     *
     * @param array $response responses, as returned by
     *      {@link question_attempt_step::get_qt_data()}.
     * @return array (float, integer) the fraction, and the state.
     */
    public function grade_response(array $response) {
        $this->update_current_response($response);
        if ($this->enableautograde) {
            $fraction = $this->get_current_response('autofraction');
            $state = question_state::graded_state_for_fraction($fraction);
        } else {
            // use manual grading, as per the "essay" question type
            $state = question_state::manually_graded_state_for_fraction(null);
        }
        return array($fraction, $state);
    }

    /**
     * Checks whether the users is allow to be served a particular file.
     *
     * @param question_attempt $qa the question attempt being displayed.
     * @param question_display_options $options the options that control display of the question.
     * @param string $component the name of the component we are serving files for.
     * @param string $filearea the name of the file area.
     * @param array $args the remaining bits of the file path.
     * @param bool $forcedownload whether the user must be forced to download the file.
     * @return bool true if the user can access this file.
     */
    public function check_file_access($qa, $options, $component, $filearea, $args, $forcedownload) {
        if (empty($options) || empty($args)) {
            return false; // shouldn't happen !!
        }
        if ($component == 'question') {
            if ($filearea == 'response_attachments') {
                return ($this->attachments != 0);
            }
            if ($filearea == 'response_answer') {
                return ($this->responseformat === 'editorfilepicker');
            }
            if ($filearea == 'hint') {
                return $this->check_hint_file_access($qa, $options, $args);
            }
            if (in_array($filearea, $this->qtype->feedbackfields)) {
                return $this->check_combined_feedback_file_access($qa, $options, $filearea, $args);
            }
        }
        if ($component == $this->plugin_name()) { // qtype_essayautograde
            if ($filearea == 'graderinfo' && $options->manualcomment) {
                return ($this->id == reset($args));
            }
        }
        return parent::check_file_access($qa, $options, $component, $filearea, $args, $forcedownload);
    }

    /**
     * Get one of the question hints. The question_attempt is passed in case
     * the question type wants to do something complex. For example, the
     * multiple choice with multiple responses question type will turn off most
     * of the hint options if the student has selected too many opitions.
     * @param int $hintnumber Which hint to display. Indexed starting from 0
     * @param question_attempt $qa The question_attempt.
     */
    public function get_hint($hintnumber, question_attempt $qa) {
        if (empty($this->hints[$hintnumber])) {
            return null;
        }
        return $this->hints[$hintnumber];
    }

    /**
     * format a hint for "multiple tries" behavior
     *
     * @param question_hint $hint
     * @param question_attempt $qa
     * @return string formatted hint text
     */
    public function format_hint(question_hint $hint, question_attempt $qa) {
        return $this->format_text($hint->hint, $hint->hintformat, $qa, 'question', 'hint', $hint->id);
    }

    /**
     * Generate a brief, plain-text, summary of the correct answer to this question.
     * This is used by various reports, and can also be useful when testing.
     * This method will return null if such a summary is not possible, or
     * inappropriate.
     * @return string|null a plain text summary of the right answer to this question.
     */
    public function get_right_answer_summary() {
        return $this->html_to_text($this->questiontext, $this->questiontextformat);
    }

    ///////////////////////////////////////////////////////
    // non-standard methods (used only in this class)
    ///////////////////////////////////////////////////////

    /**
     * qtype is plugin name without leading "qtype_"
     */
    protected function qtype() {
        return substr($this->plugin_name(), 6);
        // = return $this->qtype->name();
    }

    /**
     * Plugin name is class name without trailing "_question"
     */
    protected function plugin_name() {
        return substr(get_class($this), 0, -9);
        // = return $this->qtype->plugin_name();
     }

    /**
     * Fetch a constant from the plugin class in "questiontype.php".
     */
    public function plugin_constant($name) {
        $plugin = $this->plugin_name();
        return constant($plugin.'::'.$name);
    }

    public function update_current_response($response, $displayoptions=null) {
        global $CFG, $PAGE, $USER;

        if (empty($CFG->enableplagiarism)) {
            $enableplagiarism = false;
            $plagiarismparams = array();
            $context = null;
            $course = null;
            $cm = null;
        } else {
            $enableplagiarism = true;
            require_once($CFG->dirroot.'/lib/plagiarismlib.php');

            list($context, $course, $cm) = get_context_info_array($PAGE->context->id);
            $plagiarismparams = array(
                'userid' => $USER->id
            );
            if ($course) {
                $plagiarismparams['course'] = $course->id;
            }
            if ($cm) {
                $plagiarismparams['cmid'] = $cm->id;
                $plagiarismparams[$cm->modname] = $cm->instance;
            }
        }

        // Initialize data about this $response
        $count = 0;
        $bands = array();
        $phrases = array();
        $myphrases = array();
        $plagiarism = array();
        $breaks = 0;
        $rawpercent = 0;
        $rawfraction = 0.0;
        $autopercent = 0;
        $autofraction = 0.0;
        $currentcount = 0;
        $currentpercent = 0;
        $partialcount = 0;
        $partialpercent = 0;
        $completecount = 0;
        $completepercent = 0;

        if ($text = $this->get_response_answer_text($response)) {
            $this->set_template_and_sample_text();
            if ($this->is_similar_text($text, $this->responsetemplatetext)) {
                $text = '';
            } else if ($this->is_similar_text($text, $this->responsesampletext)) {
                $text = '';
            }
        }
        if ($enableplagiarism) {
            $plagiarism[] = plagiarism_get_links($plagiarismparams + array('content' => $text));
        }

        if (empty($response) || empty($response['attachments'])) {
            $files = array();
        } else {
            $files = $response['attachments']->get_files();
        }
        if ($enableplagiarism) {
            foreach ($files as $file) {
                $plagiarism[] = plagiarism_get_links($plagiarismparams + array('file' => $file));
            }
        }

        // detect common errors
        list($errors, $errortext, $errorpercent) = $this->get_common_errors($text);

        // Get stats for this $text.
        $stats = $this->get_stats($text, $files, $errors);

        // Count items in $text.
        switch ($this->itemtype) {
            case $this->plugin_constant('ITEM_TYPE_CHARS'): $count = $stats->chars; break;
            case $this->plugin_constant('ITEM_TYPE_WORDS'): $count = $stats->words; break;
            case $this->plugin_constant('ITEM_TYPE_SENTENCES'): $count = $stats->sentences; break;
            case $this->plugin_constant('ITEM_TYPE_PARAGRAPHS'): $count = $stats->paragraphs; break;
            case $this->plugin_constant('ITEM_TYPE_FILES'): $count = $stats->files; break;
        }

        // Get records from "question_answers" table.
        $answers = $this->get_answers();

        if (empty($answers) || ($this->itemtype == $this->plugin_constant('ITEM_TYPE_FILES'))) {

            // Set fractional grade from number of items.
            if (empty($this->itemcount)) {
                $rawfraction = 0.0;
            } else {
                $rawfraction = ($count / $this->itemcount);
            }

        } else {

            // Cache plugin constants.
            $ANSWER_TYPE_BAND = $this->plugin_constant('ANSWER_TYPE_BAND');
            $ANSWER_TYPE_PHRASE = $this->plugin_constant('ANSWER_TYPE_PHRASE');

            // override "addpartialgrades" with incoming form data, if necessary
            $addpartialgrades = $this->addpartialgrades;
            $addpartialgrades = optional_param('addpartialgrades', $addpartialgrades, PARAM_INT);

            // set fractional grade from item count and target phrases
            $rawfraction = 0.0;
            $checkbands = true;
            foreach ($answers as $answer) {
                switch ($answer->type) {

                    case $ANSWER_TYPE_BAND:
                        if ($checkbands) {
                            if ($answer->answer > $count) {
                                $checkbands = false;
                            }
                            // update band counts and percents
                            $completecount   = $currentcount;
                            $completepercent = $currentpercent;
                            $currentcount    = $answer->answer;
                            $currentpercent  = $answer->answerformat;
                        }
                        $bands[$answer->answer] = $answer->answerformat;
                        break;

                    case $ANSWER_TYPE_PHRASE:
                        if ($search = trim($answer->feedback)) {
                            if ($match = $this->search_text($search, $text, $answer->fullmatch, $answer->casesensitive, $answer->ignorebreaks)) {
                                list($pos, $length, $match) = $match;
                                $myphrases[$match] = $search;
                                $rawfraction += ($answer->feedbackformat / 100);
                            } else if (empty($answer->ignorebreaks) && preg_match('/\\bAND|ANY\\b/', $search) && preg_match("/[\r\n]/us", $text)) {
                                $breaks++;
                            }
                            $phrases[$search] = $answer->feedbackformat;
                        }
                        break;
                }
            }

            // update band counts for top grade band, if necessary
            if ($checkbands) {
                $completecount = $currentcount;
                $completepercent = $currentpercent;
            }

            // set the item width of the current band
            // and the percentage width of the current band
            $currentcount = ($currentcount - $completecount);
            $currentpercent = ($currentpercent - $completepercent);

            // set the number of items to be graded by the current band
            // and thus calculate the percent awarded by the current band
            if ($addpartialgrades && $currentcount) {
                $partialcount = ($count - $completecount);
                $partialpercent = round(($partialcount / $currentcount) * $currentpercent);
            } else {
                $partialcount = 0;
                $partialpercent = 0;
            }

            $rawfraction += (($completepercent + $partialpercent) / 100);

        }

        // deduct penalties for common errors
        $rawfraction -= ($errorpercent / 100);

        // make sure $autofraction is in range 0.0 - 1.0
        $autofraction = min(1.0, max(0.0, $rawfraction));

        // we can now set $autopercent and $rawpercent
        $rawpercent = round($rawfraction * 100);
        $autopercent = round($autofraction * 100);

        // store this information, in case it is needed elswhere
        $this->save_current_response('text', $text);
        $this->save_current_response('stats', $stats);
        $this->save_current_response('count', $count);
        $this->save_current_response('bands', $bands);
        $this->save_current_response('phrases', $phrases);
        $this->save_current_response('myphrases', $myphrases);
        $this->save_current_response('plagiarism', $plagiarism);
        $this->save_current_response('breaks', $breaks);
        $this->save_current_response('rawpercent', $rawpercent);
        $this->save_current_response('rawfraction', $rawfraction);
        $this->save_current_response('autopercent', $autopercent);
        $this->save_current_response('autofraction', $autofraction);
        $this->save_current_response('partialcount', $partialcount);
        $this->save_current_response('partialpercent', $partialpercent);
        $this->save_current_response('completecount', $completecount);
        $this->save_current_response('completepercent', $completepercent);
        $this->save_current_response('displayoptions', $displayoptions);
        $this->save_current_response('errors', $errors);
        $this->save_current_response('errortext', $errortext);
        $this->save_current_response('errorpercent', $errorpercent);
    }

    /**
     * set_template_and_sample_text
     */
    protected function set_template_and_sample_text() {
        if ($this->responsetemplatetext === null) {
            $this->responsetemplatetext = $this->to_plain_text(
                $this->responsetemplate, $this->responsetemplateformat
            );
        }
        if ($this->responsesampletext === null) {
            $this->responsesampletext = $this->to_plain_text(
                $this->responsesample, $this->responsesampleformat
            );
        }
    }

    /**
     * get_response_answer_text($response)
     */
    protected function get_response_answer_text($response) {
        if (empty($response) || empty($response['answer'])) {
            return '';
        }
        if (empty($response['answerformat'])) {
            $response['answerformat'] = 0; // FORMAT_MOODLE
        }
        return $this->to_plain_text($response['answer'], $response['answerformat']);
    }

    /**
     * to_plain_text
     */
    protected function to_plain_text($text, $format) {
        if (empty($text)) {
            return '';
        }
        $plaintext = question_utils::to_plain_text($text, $format, array('para' => false));
        $plaintext = $this->standardize_white_space($plaintext);
        return $plaintext;
    }

    /**
     * standardize_white_space($text)
     */
    protected function standardize_white_space($text) {
        // Standardize white space in $text.
        // Html-entity for non-breaking space, &nbsp;,
        // is converted to a unicode character, "\xc2\xa0",
        // that can be simulated by two ascii chars (194,160)
        // x0A-x0D are various newline characters (see below):
        //     x0A = 10 = \n = newline (Unix)
        //     x0B = 11 = \v = vertical tab
        //     x0C = 12 = \f = form feed
        //     x0D = 13 = \r = carriage return
        $text = str_replace(chr(194).chr(160), ' ', $text);
        $text = preg_replace('/[ \t]+/', ' ', trim($text));
        $text = preg_replace('/( *[\x0A-\x0D]+ *)+/s', "\n", $text);
        return $text;
    }

    /**
     * is_similar_text($a, $b, $similarity=null)
     */
    protected function is_similar_text($a, $b, $allowsimilarity=null) {

        if ($allowsimilarity === null) {
            if (isset($this->allowsimilarity)) {
                $allowsimilarity = $this->allowsimilarity;
            } else {
                $allowsimilarity = 10; // may happen during upgrade.
            }
        }

        // If 100% similarity is allowed, then we don't need to check anything.
        if ($allowsimilarity == 100) {
            return false;
        }

        if (empty($a)) {
            $a = '';
            $alen = 0;
        } else {
            $a = strip_tags($a);
            $alen = core_text::strlen($a);
        }

        if (empty($b)) {
            $b = '';
            $blen = 0;
        } else {
            $b = strip_tags($b);
            $blen = core_text::strlen($b);
        }

        // If one string is empty, they are identical if the other is also empty.
        // Otherwise, they are not similar.
        if ($alen == 0) {
            return ($blen == 0);
        }
        if ($blen == 0) {
            return ($alen == 0);
        }

        // If the strings are identical, then they are similar.
        if ($alen == $blen && $a == $b) {
            return true;
        }

        // Compare short strings (of up to 255 chars) with "levenshtein()" because its faster.
        // Compare long strings with "similar_text()" because it can handle texts of any length.
        if (max($alen, $blen) <= 255) {
            // "levenshtein()" returns the minimal number of characters
            // you have to replace, insert or delete to transform $a into $b
            // i.e. the lower the number, the more similar the texts are.
            // As the levenshtein number grows, the similarity decreases.
            $similarity = ($alen - levenshtein($a, $b));
        } else {
            // "similar_text()" returns the number of matching chars in $a and $b,
            // i.e. the higher number, the more similar the texts are.
            // As the number of matching chars grows, the similarity also increases.
            $similarity = similar_text($a, $b);
        }

        // Convert the similarity to a fraction and then percentage.
        $similarity = ($similarity / $alen);
        $similarity = round(100 * $similarity, 2);

        // If the similarity is greater than the allowed similiarity, return true.
        // Otherwise, return false. (i.e. texts are regarded as not similar)
        return ($similarity > $allowsimilarity);
    }

    /**
     * get_common_errors
     */
    protected function get_common_errors($text) {
        global $DB;

        $errors = array();
        $matches = array();

        if (empty($this->errorcmid)) {
            $cm = null;
        } else {
            $cm = get_coursemodule_from_id('', $this->errorcmid);
        }

        if (empty($this->errorpercent)) {
            $percent = 0;
        } else {
            $percent = $this->errorpercent;
        }

        if ($cm) {
            $entryids = array();
            if ($entries = $DB->get_records('glossary_entries', array('glossaryid' => $cm->instance), 'concept')) {
                foreach ($entries as $entry) {
                    if ($match = $this->glossary_entry_search_text($entry, $entry->concept, $text)) {
                        list($pos, $length, $match) = $match;
                        $errors[$match] = $this->glossary_entry_link($cm->name, $entry, $match);
                        $matches[$pos] = (object)array('pos' => $pos, 'length' => $length, 'match' => $match);
                    } else {
                        $entryids[] = $entry->id;
                    }
                }
            }
            if (count($entryids)) {
                list($select, $params) = $DB->get_in_or_equal($entryids);
                if ($aliases = $DB->get_records_select('glossary_alias', "entryid $select", $params)) {
                    foreach ($aliases as $alias) {
                        $entry = $entries[$alias->entryid];
                        if ($match = $this->glossary_entry_search_text($entry, $alias->alias, $text)) {
                            list($pos, $length, $match) = $match;
                            $errors[$match] = $this->glossary_entry_link($cm->name, $entry, $match);
                            $matches[$pos] = (object)array('pos' => $pos, 'length' => $length, 'match' => $match);
                        }
                    }
                }
            }
        }

        $errortext = $text;
        if (count($matches)) {
            // sort error $matches from last position to first position
            krsort($matches);
            foreach ($matches as $match) {
                $pos = $match->pos;
                $length = $match->length;
                $match = $errors[$match->match]; // a link to the glossary
                $errortext = substr_replace($errortext, $match, $pos, $length);
            }
        }

        if (count($errors)) {
            // sort the common errors by length (shortest to longest)
            // https://stackoverflow.com/questions/3955536/php-sort-hash-array-by-key-length
            $matches = array_keys($errors);
            $lengths = array_map('core_text::strlen', $matches);
            array_multisort($lengths, SORT_DESC, $matches, $errors);

            // remove matches that are substrings of longer matches
            while ($match = array_shift($matches)) {
                $search = '/^'.preg_quote($match, '/').'.+/iu';
                $search = preg_grep($search, $matches);
                if (count($search)) {
                    unset($errors[$match]);
                }
            }
        }

        return array($errors, $errortext, count($errors) * $percent);
    }

    /**
     * glossary_entry_search_text
     *
     * @param object $entry
     * @param string $match
     * @param string $text
     * @return mixed array($match, $pos, $length) if matching substring in $text; otherwise ""
     */
    protected function glossary_entry_search_text($entry, $search, $text) {
        if (empty($entry->usedynalink)) {
            $fullmatch = $this->errorfullmatch;
            $casesensitive = $this->errorcasesensitive;
        } else {
            $fullmatch = $entry->fullmatch;
            $casesensitive = $entry->casesensitive;
        }
        $ignorebreaks = $this->errorignorebreaks;
        return $this->search_text($search, $text, $fullmatch, $casesensitive, $ignorebreaks);
    }

    /**
     * search_text
     *
     * @param string $match
     * @param string $text
     * @return boolean TRUE if $text mattches the $match; otherwise FALSE;
     */
    protected function search_text($search, $text, $fullmatch=false, $casesensitive=false, $ignorebreaks=true) {

        $text = trim($text);
        if ($text=='') {
            return false; // unexpected ?!
        }

        $search = trim($search);
        if ($search=='') {
            return false; // shouldn't happen !!
        }

        if (self::$aliases===null) {
            // human readable aliases for regexp strings
            self::$aliases = array(' OR '  => '|',
                                   ' OR'   => '|',
                                   'OR '   => '|',
                                   ' , '   => '|',
                                   ' ,'    => '|',
                                   ', '    => '|',
                                   ','     => '|',
                                   ' AND ' => '\\b.*\\b',
                                   ' AND'  => '\\b.*\\b',
                                   'AND '  => '\\b.*\\b',
                                   ' ANY ' => '\\b.*\\b',
                                   ' ANY'  => '\\b.*\\b',
                                   'ANY '  => '\\b.*\\b');

            // allowable regexp strings and their internal aliases
            self::$metachars = array('^' => 'CARET',
                                     '$' => 'DOLLAR',
                                     '.' => 'DOT',
                                     '?' => 'QUESTION_MARK',
                                     '*' => 'ASTERISK',
                                     '+' => 'PLUS_SIGN',
                                     '|' => 'VERTICAL_BAR',
                                     '-' => 'HYPHEN',
                                     ':' => 'COLON',
                                     '!' => 'EXCLAMATION_MARK',
                                     '=' => 'EQUALS_SIGN',
                                     '(' => 'OPEN_ROUND',
                                     ')' => 'CLOSE_ROUND',
                                     '[' => 'OPEN_SQUARE',
                                     ']' => 'CLOSE_SQUARE',
                                     '{' => 'OPEN_CURLY',
                                     '}' => 'CLOSE_CURLY',
                                     '<' => 'OPEN_ANGLE',
                                     '>' => 'CLOSE_ANGLE',
                                     '\\' => 'BACKSLASH',
                                     '\b' => 'WORD_BREAK');
            self::$flipmetachars = array_flip(self::$metachars);
        }

        $regexp = strtr($search, self::$aliases);
        $regexp = strtr($regexp, self::$metachars);
        $regexp = preg_quote($regexp, '/');
        $regexp = strtr($regexp, self::$flipmetachars);
        if ($fullmatch) {
            $regexp = "\\b$regexp\\b";
        }
        $regexp = "/$regexp/u"; // unicode match
        if (empty($casesensitive)) {
            $regexp .= 'i';
        }
        if ($ignorebreaks) {
            $regexp .= 's';
        }
        if (preg_match($regexp, $text, $match, PREG_OFFSET_CAPTURE)) {
            list($match, $offset) = $match[0];
            $length = strlen($match);
            if (core_text::strlen($search) < core_text::strlen($match[0])) {
                $match = $search;
            }
            return array($offset, $length, $match);
        } else {
            return ''; // no matches
        }
    }

    /**
     * Store information about latest response to this question
     *
     * @param  string $name
     * @param  string $value
     * @return void, but will update currentresponse property of this object
     */
    public function glossary_entry_link($glossaryname, $entry, $text) {
        $params = array('eid' => $entry->id,
                        'displayformat' => 'dictionary');
        $url = new moodle_url('/mod/glossary/showentry.php', $params);

        $params = array('target' => '_blank',
                        'title' => $glossaryname.': '.$entry->concept,
                        'class' => 'glossary autolink concept glossaryid'.$entry->glossaryid);
        return html_writer::link($url, $text, $params);
    }

    /**
     * Store information about latest response to this question
     *
     * @param  string $name
     * @param  string $value
     * @return void, but will update currentresponse property of this object
     */
    public function save_current_response($name, $value) {
        if ($this->currentresponse===null) {
            $this->currentresponse = new stdClass();
        }
        $this->currentresponse->$name = $value;
    }

    /**
     * Returns information about latest response to this question
     *
     * @return string
     */
    public function get_current_response($name='') {
        if ($this->currentresponse===null) {
            return $this->currentresponse;
        }
        if (empty($name)) {
            return $this->currentresponse;
        }
        return $this->currentresponse->$name;
    }

    /**
     * get "answers" ordered by "type" (=fraction)
     * and "percent" (=answer/feedback format)
     */
    public function get_answers() {
        global $DB;
        if ($this->answers===null) {
            if ($this->answers = $DB->get_records('question_answers', array('question' => $this->id), 'id')) {

                $ANSWER_TYPE = $this->plugin_constant('ANSWER_TYPE');
                $ANSWER_FULL_MATCH = $this->plugin_constant('ANSWER_FULL_MATCH');
                $ANSWER_CASE_SENSITIVE = $this->plugin_constant('ANSWER_CASE_SENSITIVE');
                $ANSWER_IGNORE_BREAKS = $this->plugin_constant('ANSWER_IGNORE_BREAKS');

                foreach ($this->answers as $id => $answer) {
                    $fraction = intval($answer->fraction);
                    $this->answers[$id]->fraction = $fraction;
                    $this->answers[$id]->type = ($fraction & $ANSWER_TYPE);
                    $this->answers[$id]->fullmatch = (($fraction & $ANSWER_FULL_MATCH) ? 1 : 0);
                    $this->answers[$id]->casesensitive = (($fraction & $ANSWER_CASE_SENSITIVE) ? 1 : 0);
                    $this->answers[$id]->ignorebreaks = (($fraction & $ANSWER_IGNORE_BREAKS) ? 1 : 0);
                }
            } else {
                $this->answers = array();
            }
        }
        return $this->answers;
    }

    /**
     * get_stats
     */
    protected function get_stats($text, $files, $errors) {
        $precision = 1;
        $stats = (object)array('chars' => $this->get_stats_chars($text),
                               'words' => $this->get_stats_words($text),
                               'sentences' => $this->get_stats_sentences($text),
                               'paragraphs' => $this->get_stats_paragraphs($text),
                               'files' => $this->get_stats_files($files),
                               'longwords' => $this->get_stats_longwords($text),
                               'uniquewords' => $this->get_stats_uniquewords($text),
                               'fogindex' => 0,
                               'commonerrors' => count($errors),
                               'lexicaldensity' => 0,
                               'charspersentence' => 0,
                               'wordspersentence' => 0,
                               'longwordspersentence' => 0,
                               'sentencesperparagraph' => 0);
        if ($stats->words) {
            $stats->lexicaldensity = round(($stats->uniquewords / $stats->words) * 100, 0).'%';
        }
        if ($stats->sentences) {
            $stats->charspersentence = round($stats->chars / $stats->sentences, $precision);
            $stats->wordspersentence = round($stats->words / $stats->sentences, $precision);
            $stats->longwordspersentence = round($stats->longwords / $stats->sentences, $precision);
        }
        if ($stats->wordspersentence) {
            $stats->fogindex = ($stats->wordspersentence + $stats->longwordspersentence);
            $stats->fogindex = round($stats->fogindex * 0.4, $precision);
        }
        if ($stats->paragraphs) {
            $stats->sentencesperparagraph = round($stats->sentences / $stats->paragraphs, $precision);
        }
        return $stats;
    }

    /**
     * get_words
     *
     * using the same reg as "count_words()" in moodlelib.php
     */
    protected function get_words($text) {
         return preg_split('/[\p{Z}\p{Cc}—–]+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * get_unique_words
     */
    protected function get_unique_words($text) {
        $items = core_text::strtolower($text);
        $items = $this->get_words($text);
        $items = array_unique($items);
        return $items;
    }

    /**
     * get_stats
     */
    protected function get_stats_chars($text) {
        return core_text::strlen($text);
    }

    /**
     * get_stats_words
     */
    protected function get_stats_words($text) {
        return count($this->get_words($text));
    }

    /**
     * get_stats_sentences
     */
    protected function get_stats_sentences($text) {
        $items = preg_split('/[!?.]+(?![0-9])/', $text);
        $items = array_filter($items);
        return count($items);
    }

    /**
     * get_stats_paragraphs
     */
    protected function get_stats_paragraphs($text) {
        $items = explode("\n", $text);
        $items = array_filter($items);
        return count($items);
    }

    /**
     * get_stats_files
     */
    protected function get_stats_files($files) {
        return count($files);
    }

    /**
     * get_stats_uniquewords
     */
    protected function get_stats_uniquewords($text) {
        return count($this->get_unique_words($text));
    }

    /**
     * get_stats_longwords
     */
    protected function get_stats_longwords($text) {
        $count = 0;
        $items = $this->get_unique_words($text);
        foreach ($items as $item) {
            if ($this->count_syllables($item) > 2) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * count_syllables
     *
     * based on: https://github.com/e-rasvet/sassessment/blob/master/lib.php
     */
    protected function count_syllables($word) {
        // https://github.com/vanderlee/phpSyllable (multilang)
        // https://github.com/DaveChild/Text-Statistics (English only)
        // https://pear.php.net/manual/en/package.text.text-statistics.intro.php
        // https://pear.php.net/package/Text_Statistics/docs/latest/__filesource/fsource_Text_Statistics__Text_Statistics-1.0.1TextWord.php.html

        static $syllable_counts = null;
        if ($syllable_counts === null) {
            // initialize with some well-known problematic words
            $syllable_counts = self::get_syllable_counts();
        }

        $str = strtolower($word);

        // very short word (1 or 2 chars)
        if (strlen($str) < 2) {
            return 1;
        }

        // If we already know the syllable count, use that.
        if (array_key_exists($str, $syllable_counts)) {
            return $syllable_counts[$str];
        }

        $count = 0;

        // Detect common endings with extra syllable.
        if (preg_match('/(ia|io|ius|ium)^/', $str)) {
            $count++;
        }

        // Detect syllables for double-vowels.
        $vowelcount = 0;
        $vowels = array('aa','ae','ai','ao','au','ay',
                        'ea','ee','ei','eo','eu','ey',
                        'ia','ie','ii','io','iu','iy',
                        'oa','oe','oi','oo','ou','oy',
                        'ua','ue','ui','uo','uu','uy',
                        'ya','ye','yi','yo','yu','yy');
        $str = str_replace($vowels, '', $str, $vowelcount);
        $count += $vowelcount;

        // If the last letter is "E", it is often silent.
        $silentvowel = (substr($str, -1) == 'e');

        if ($silentvowel) {
            $final3chars = substr($str, -3);
            if (preg_match('/[bcdfgkpstxyz]le/', $final3chars)) {
                // able, cycle, idle, rifle, angle, ankle, apple, hassle, little, axle, puzzle
                $silentvowel = false;
            } else if ($final3chars == 'phe') {
                // apostrophe, catastrophe
                $silentvowel = false;
            }
        }

        // Detect syllables for single-vowels.
        $vowelcount = 0;
        $vowels = array('a','e','i','o','u','y');
        $str = str_replace($vowels, '', $str, $vowelcount);
        $count += $vowelcount;

        // Adjust the count for words that end in "e"
        // and have at least one other vowel.
        if ($count > 1 && $silentvowel) {
            $count--;
        }

        $syllable_counts[$str] = $count;
        return $count;
    }

    /**
     * get_syllable_counts
     *
     * @return array of words with their syllable count
     */
    static protected function get_syllable_counts() {
        return array(
            // final "e" as separate syllable
            'aborigine' => 5,
            'adobe' => 3,
            'anemone' => 4,
            'cafe' => 2,
            'chile' => 2,
            'coyote' => 3,
            'epitome' => 4,
            'guacamole' => 4,
            'hyperbole' => 4,
            'karate' => 3,
            'machete' => 3,
            'maybe' => 2,
            'recipe' => 3,
            'sesame' => 3,
            'simile' => 3,
            'yosemite' => 4,

            // internal silent-e
            'jukebox' => 2,
            'shoreline' => 2,

            // double vowel as 2-syllables
            'cooperation' => 5,
            'react' => 2,

            // internal "ia" as 2-syllables
            'piano' => 3,
            'giant' => 2,
            // social, racial, spatial

            // final "ia" as 2-syllables
            'Australia' => 4,
            'California' => 5,

            // final "io" as 2-syllables
            'radio' => 3,
            'Ohio' => 3,

            // final "ion" as 2-syllables
            'ion' => 2,
            'lion' => 2,
            'union' => 3,

            // final "ius" as 2-syllables
            'genius' => 3,
            'celsius' => 3,
            'radius' => 3,

            // final "ium" as 2-syllables
            'aquarium' => 3,
            'calcium' => 3,
            'stadium' => 3,
            // Belgium

            // final "eum|oem" as 2-syllables
            'museum' => 2,
            'poem' => 2,

            // final "che" as 1-syllable
            'apache' => 3,
            'psyche' => 2,

            // final "ble" as 1-syllable
            'able' => 2,
            'adaptable' => 4,
            'incredible' => 4,
            'syllable' => 3, 
            'table' => 2,

            // final "cle" as 1-syllable
            'cycle' => 2,
            'bicycle' => 3,
            'vehicle' => 3,

            // final "dle" as 1-syllable
            'handle' => 2,
            'idle' => 2,
            'saddle' => 2,

            // final "fle" as 1-syllable
            'rifle' => 2,
            'shuffle' => 2,

            // final "gle" as 1-syllable
            'angle' => 2,
            'struggle' => 2,
            'triangle' => 3,

            // final "kle" as 1-syllable
            'ankle' => 2,
            'tackle' => 2,
            'buckle' => 2,

            // final "ple" as 1-syllable
            'apple' => 2,
            'example' => 3,
            'people' => 2,

            // final "sle" as 1-syllable
            'aisle' => 1,
            'isle' => 1,
            'hassle' => 2,

            // final "tle" as 1-syllable
            'little' => 2,
            'subtle' => 2,
            'title' => 2,

            // final "xle" as 1-syllable
            'axle' => 2,

            // final "yle" as 1-syllable
            'style' => 1,
            'styles' => 1,

            // final "zle" as 1-syllable
            'puzzle' => 2,
            'drizzle' => 2,

            // final "phe" as 1-syllable
            'apostrophe' => 4,
            'catastrophe' => 4,

            // female names
            'aphrodite' => 4,
            'ariadne' => 4,
            'chloe' => 2,
            'jesse' => 2,
            'daphne' => 2,
            'hermione' => 4,
            'penelope' => 4,
            'persephone' => 4,
            'phoebe' => 2,
            'zoe' => 2,

            // unusual words
            'abalone' => 4,
            'abare' => 3,
            'abed' => 2,
            'abruzzese' => 4,
            'abbruzzese' => 4,
            'acreage' => 3,
            'adame' => 3,
            'adieu' => 2,
            'calliope' => 4,
            'circe' => 2,
            'gethsemane' => 4,
            'syncope' => 3,
            'tamale' => 3,
            'eurydice' => 4,
            'euterpe' => 3,
        );
    }

    ///////////////////////////////////////////////////////
    // methods from "question_graded_automatically" class
    // see "question/type/questionbase.php"
    ///////////////////////////////////////////////////////

    /**
     * Check a request for access to a file belonging to a combined feedback field.
     * @param question_attempt $qa the question attempt being displayed.
     * @param question_display_options $options the options that control display of the question.
     * @param string $filearea the name of the file area.
     * @param array $args the remaining bits of the file path.
     * @return bool whether access to the file should be allowed.
     */
    protected function check_combined_feedback_file_access($qa, $options, $filearea, $args = null) {
        $state = $qa->get_state();
        if (! $state->is_finished()) {
            $response = $qa->get_last_qt_data();
            if (! $this->is_gradable_response($response)) {
                return false;
            }
            list($fraction, $state) = $this->grade_response($response);
        }
        if ($state->get_feedback_class().'feedback' == $filearea) {
            return ($this->id == reset($args));
        } else {
            return false;
        }
    }

    /**
     * Check a request for access to a file belonging to a hint.
     * @param question_attempt $qa the question attempt being displayed.
     * @param question_display_options $options the options that control display of the question.
     * @param array $args the remaining bits of the file path.
     * @return bool whether access to the file should be allowed.
     */
    protected function check_hint_file_access($qa, $options, $args) {
        $hint = $qa->get_applicable_hint();
        return ($hint->id == reset($args));
    }
}
