<?php

namespace AG\Bundle\BaseBundle\Lib;

/**
 * Base helper functions
 */
class Helper
{

    /**
     * Generates a strong password of N length containing at least one lower case letter,
     * one uppercase letter, one digit, and one special character. The remaining characters
     * in the password are chosen at random from those four sets.
     *
     * The available characters in each set are user friendly - there are no ambiguous
     * characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
     * makes it much easier for users to manually type or speak their passwords.

     * Note: the $add_dashes option will increase the length of the password by
     * floor(sqrt(N)) characters.
     * 
     * @param int $length
     * @param boolean $addDashes
     * @param string $availableSets 'luds'
     * @return string
     */
    public function getToken($length = 9, $addDashes = false, $availableSets = 'luds')
    {
        $chars = [
            'l' => 'abcdefghjkmnpqrstuvwxyz',
            'u' => 'ABCDEFGHJKMNPQRSTUVWXYZ',
            'd' => '23456789',
            's' => '!@#$%&*?',
        ];
        $sets = [];

        foreach (str_split($availableSets) as $char) {
            if (!empty($chars[$char])) {
                $sets[] = $chars[$char];
            }
        }

        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }

        $password = str_shuffle($password);

        if (!$addDashes) {
            return $password;
        }

        $dashLen = floor(sqrt($length));
        $dashStr = '';
        while (strlen($password) > $dashLen) {
            $dashStr .= substr($password, 0, $dashLen) . '-';
            $password = substr($password, $dashLen);
        }
        $dashStr .= $password;

        return $dashStr;
    }
}
