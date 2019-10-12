<?php

class DashboardController extends Controller
{

    public $pageTitle = 'This is the page title';

    public function actionHome(){
        $this->layout = 'basic';
        $emails = ['test@gmail.com','johndoe@gmail.com'];
        $this->render('view',['emails'=>$emails]);
    }

    public function message( $msg ){
        return $msg;
    }

    public function actionEvents()
    {
        $results = Event::model()->findByAttributes(['event_time'=>'10:00am']);
        $this->render("events",['data'=>$results]);
    }
}