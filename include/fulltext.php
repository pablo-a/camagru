<?php

function get_words($string, $sign) {
    $string = trim($string);
    $result_array = preg_split("/\s+/", $string);
    if ($result_array[0] !== "") {
        for ($i=0; $i < count($result_array); $i++) {
            $result_array[$i] = $sign . $result_array[$i];
        }
        return $result_array;
    }
    return NULL;
}

// VERIFIE LA SYNTAXE DE LA RECHERCHE (LETTRES, CHIFFRES, + et -)

function valid_research($tab) {
    $pattern = "/[^a-zA-Z1-9-+]/";
    for ($i=0; $i < count($tab); $i++) {
        if (preg_match($pattern, $tab[$i])) {
            return FALSE;
        }
    }
    return TRUE;
}

function create_research($tab1, $tab2, $tab3)
{
    $result = "";
    if (!valid_research($tab1) || !valid_research($tab2) || !valid_research($tab3)) {
        return NULL;
    }
    if ($tab1 !== NULL) {
        for ($i=0; $i < count($tab1); $i++) {
            $result .= $tab1[$i] . " ";
        }
    }
    if ($tab2 !== NULL) {
        for ($i=0; $i < count($tab2); $i++) {
            $result .= $tab2[$i] . " ";
        }
    }
    if ($tab3 !== NULL) {
        for ($i=0; $i < count($tab3); $i++) {
            $result .= $tab3[$i] . " ";
        }
    }
    return $result;
}





 ?>
