<?php

Route::group(array('prefix' => 'queue'), function()
{
    Route::get('test', function() {
        Queue::push('Grimm\Queue', array('asdf' => 1));
    });
});
