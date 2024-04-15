<?php

namespace App\Controllers;

class HomepageController extends AppController {
    public function index(): void {
        $this->render('homepage');
    }
}