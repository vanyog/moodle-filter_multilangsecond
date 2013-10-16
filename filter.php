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

defined('MOODLE_INTERNAL') || die();

// Given XML or non XML multilinguage text, return relevant text according to
// current language:
//   - look for multilang blocks in the text.
//   - if there exists texts in the currently active language, print them.
//   - else, if there exists texts in the current parent language, print them.
//   - else, print the first language in the text.
// Please note that English texts are not used as default anymore!
//
// This version is based on original multilang filter by Gaetan Frenoy,
// rewritten by Eloy, skodak and vanyog.
//
// Changes made by Vanyo Georgiev <info@vanyog.com> 14-October-2013.
// This filter has an admin setting filter_multilangsecond_mode. 
// If this setting is set to true non html syntax can be used for multilang blocks like
// {mlang en}English{mlang}{mlang bg}Bulgarian{mlang}
// Otherwise a language block is a serie of identical html tags with lang="XX" atributes:
// <h1 lang="en">Heading in English</h1>
// <h1 lang="bg">Heading in Bulgarian</h1>
// The old syntax with <lang> tags is valid too.

class filter_multilangsecond extends moodle_text_filter {
    public function filter($text, array $options = array()) {
        global $CFG;

        if (empty($text) or is_numeric($text)) {
            return $text;
        }

        if ($CFG->filter_multilangsecond_mode) $search = '/(?:\{mlang\s+[a-z0-9]+\}.*?\{mlang\}){2,}/is';
        else $search = '/<([a-z0-9]+)[^>]*?lang=".*?".*?>.*?<\/\1>\s*(?:<\1[^>]*?lang=".*?".*?>.*?<\/\1>\s*)+/is';

        $result = preg_replace_callback($search, 'filter_multilangsecond_impl', $text);

        if (is_null($result)) {
            return $text; // Error during regex processing (too many nested spans?).
        } else {
            return $result;
        }
    }
}

function filter_multilangsecond_impl($langblock) {
    global $CFG;

    $mylang = current_language();
    static $parentcache;
    if (!isset($parentcache)) {
        $parentcache = array();
    }
    if (!array_key_exists($mylang, $parentcache)) {
        $parentlang = get_parent_language($mylang);
        $parentcache[$mylang] = $parentlang;
    } else {
        $parentlang = $parentcache[$mylang];
    }

    if ($CFG->filter_multilangsecond_mode){
        $searchtosplit = '/\{mlang\s+([a-z0-9]+)\}(.*?)\{mlang\}/is';
        $ri = 2;
    }
    else {
        // <span lang="XX"> tags are removed, but other tags are kept.
        if ($langblock[1]=='span'){
            $searchtosplit = '/<(?:'.$langblock[1].')[^>]+lang="([a-zA-Z0-9_-]+)"[^>]*>(.*?)<\/'.$langblock[1].'>/is';
            $ri = 2;
        }
        else{
            $searchtosplit = '/<(?:'.$langblock[1].')[^>]+lang="([a-zA-Z0-9_-]+)"[^>]*>.*?<\/'.$langblock[1].'>/is';
            $ri = 0;
        }
    }

    if (!preg_match_all($searchtosplit, $langblock[0], $rawlanglist)) {
        // Skip malformed blocks.
        return $langblock[0];
    }

    $langlist = array();
    foreach ($rawlanglist[1] as $index => $lang) {
        $lang = str_replace('-', '_', strtolower($lang)); // Normalize languages.
        // Skip lang attributes set by OpenOffice writer.
        if (isset($langlist[$lang])) return $langblock[0];
        $langlist[$lang] = $rawlanglist[$ri][$index];
    }

    if (array_key_exists($mylang, $langlist)) {
        return $langlist[$mylang];
    } else if (array_key_exists($parentlang, $langlist)) {
        return $langlist[$parentlang];
    } else {
        $first = array_shift($langlist);
        return $first;
    }
}


