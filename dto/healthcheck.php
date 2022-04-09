<?php
    class Healthcheck {

        public $subject;
        public $authors;

        public function __construct() {
            $this->subject = 'Podstawy telemedycyny - projekt';
            $this->authors = [
                'Weronika Wojcik',
                'Bartosz Lukasik',
                'Marcel Pikula'
            ];
        }

        public function json() {
            return json_encode($this);
        }
    }
?>