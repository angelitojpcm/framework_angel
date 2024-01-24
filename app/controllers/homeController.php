<?php

class homeController extends Controller
{
    function __construct()
    {
    }
    function index()
    {
        $data =
            [
                'title' => 'Home',
                'bg'    => 'dark'
            ];

        View::render('home', $data);
    }
}
