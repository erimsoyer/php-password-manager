<?php

class PasswordGenerator {
    public function generate($length, $lowercase, $uppercase, $numbers, $special) {
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nums  = '0123456789';
        $specs = '!@#$%^&*()-_=+[]{};:,.<>?';

        $password = [];

        // Pull required characters from each set
        $password = array_merge(
            $this->getRandomChars($lower, $lowercase),
            $this->getRandomChars($upper, $uppercase),
            $this->getRandomChars($nums, $numbers),
            $this->getRandomChars($specs, $special)
        );

        // If password is still short, fill remaining with random mixed characters
        $all = $lower . $upper . $nums . $specs;
        while (count($password) < $length) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        // Shuffle for randomness
        shuffle($password);
        return implode('', $password);
    }

    private function getRandomChars($source, $count) {
        $chars = [];
        for ($i = 0; $i < $count; $i++) {
            $chars[] = $source[random_int(0, strlen($source) - 1)];
        }
        return $chars;
    }
}
