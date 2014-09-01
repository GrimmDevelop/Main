<?php

if(!function_exists('briefFeld')) {
    function briefFeld($brief, $feld, $name, $mult = null, $fullRow = false) {
        $firstValue = $brief->{$feld};
        if($mult !== null) {
            if($brief->{$feld . '_1'} != '') {
                $firstValue = $brief->{$feld . '_1'};
            }
        }
        if($fullRow) {
            $templateString = '<tr><td valign="top" colspan="2"><i>%2$s</i></td></tr>' . "\n";
        } else {
            $templateString = '<tr><td width="120" align="right" valign="top">%s</td><td valign="top">%s</td></tr>' . "\n";
        }
        $html = '';
        if($firstValue != '') {
            $html.= sprintf($templateString, $name, $firstValue);
            if($mult !== null) {
                $mult = abs((int)$mult);
                for($i = 2 ; $i <= $mult; $i++) {
                    if($brief->{$feld . '_' . $i} != '') {
                        $html.= sprintf($templateString, '', $brief->{$feld . '_' . $i});
                    }
                }
            }
        }
        return $html;
    }
}