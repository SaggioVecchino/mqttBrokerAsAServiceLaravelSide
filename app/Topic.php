<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $guarded = [];


    public static function topicToRegEx($topic, $allow)
    {
        return $allow ? self::permissionToRegEx($topic) : self::prohibitionToRegEx($topic);
    }

    private static function permissionToRegEx($topic)
    {
        $fields = explode('/', $topic);
        $regEx = '';
        foreach ($fields as $field) {
            if ($field == '+') {
                $regEx .= '([\\w ]+|\\+)\\/';
            } elseif ($field == '#') {
                $regEx .= '([\\w ]+|\\+)(\\/([\w ]+|\\+))*(\\/\\#)?';
            } else {
                $regEx .= $field . '\\/';
            }
        }
        $len = strlen($regEx);
        if ($regEx {
            $len - 1} == '/')
            $regEx = substr($regEx, 0, $len - 2);
        $regEx = '/^' . $regEx . '$/';
        return $regEx;
    }

    private static function prohibitionToRegEx($topic)
    {
        $fields = explode('/', $topic);
        $regEx = '';
        if (count($fields))
            $regEx = '(\\#|';
        foreach ($fields as $field) {
            if ($field == '+') {
                $regEx .= '(([\\w ]+\\/?|\\+\\/?)(\\#|';
            } elseif ($field == '#') {
                $regEx .= '((([\\w ]+|\\+)(\\/([\w ]+|\\+))*(\\/\\#)?)(\\#|';
            } else {
                $regEx .= '((' . $field . '\\/?|\\+\\/?)(\\#|';
            }
        }

        $regEx = substr($regEx, 0, strlen($regEx) - 1);

        for ($i = 0; $i < 2 * count($fields); $i++)
            $regEx .= ')?';
        if (count($fields))
            $regEx .= ')';
        $regEx = '/^' . $regEx . '$/';
        return $regEx;
    }
}
