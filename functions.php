<?php
// for common function

function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function cleanSpace($string)
{
    $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
    return $string = trim(preg_replace('/\s\s+/', ' ', $string));
}
