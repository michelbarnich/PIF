<?php
     function replaceSpecialChars($string) { // replaces "<", ">" and "," with their html charcode, so a message wont be interpreted as html tag
        $string = str_replace("<", "&lt;", $string);
        $string = str_replace(">", "&gt;", $string);
        $string = str_replace(",", "&sbquo;", $string);
        $string = str_replace(" ", "&nbsp;", $string);

        return $string;
    }