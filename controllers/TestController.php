<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'empty';
        return $this->render('index');
    }


    public function actionEvents()
    {
        $out = array();

        $eventMajority = ['event-important', 'event-success', 'event-warning', 'event-info', 'event-inverse', 'event-special'];

        $masterClasses = ["Подхрюкивание",
            "Прикрякивание",
            "Уквакивание",
            "Замыкивание",
            "Бебеканье",
            "Мемеканье",
            "Мяучество",
            "Подпыхтивание",
            "Крысяканье",
            "Топотание",
            "Тяготенье",
            "Мрыморостроение",
            "Торгование",
            "Прихрапывание",
            "Потягиваниие",
            "Обежрание",
            "Рисование окружностей",
            "Подбадривакние",
            "Недосыпание",
            "Пересаливание",
            "Тормозяканье",
            "Криворукость"];

        $events = [
            [
                "start" => strtotime("2017-05-01").'000',
                "end" => strtotime("2017-05-05").'000'
            ],
            [
                "start" => strtotime("2017-05-04").'000',
                "end" => strtotime("2017-05-07").'000'
            ],
            [
                "start" => strtotime("2017-05-12").'000',
                "end" => strtotime("2017-05-15").'000'
            ],
            [
                "start" => strtotime("2017-05-19").'000',
                "end" => strtotime("2017-05-22").'000'
            ],
            [
                "start" => strtotime("2017-05-24").'000',
                "end" => strtotime("2017-05-25").'000'
            ],
            [
                "start" => strtotime("2017-05-29").'000',
                "end" => strtotime("2017-05-31").'000'
            ]
        ];


        $i = 0;

        foreach($events as $event){ 	//from day 01 to day 15
            $out[] = array(
                'id' => $i++,
                'title' => $masterClasses[rand(0,count($masterClasses)-1)],
                'url' => '/',
                'class' => $eventMajority[$i-1],
                'start' => $event['start'],
                'end' => $event['end']
            );
        }

        echo json_encode(array('success' => 1, 'result' => $out));

    }
}
