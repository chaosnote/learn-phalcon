<?php

class i18nController extends BaseController
{
    /**
     * @Route("/i18n")
     */
    public function indexAction()
    {
        // Prints € 4 560
        $formatter = new MessageFormatter('fr_FR', '€ {0, number, integer}');
        echo $formatter->format([4560]) . "<br/>";

        // Prints USD$ 4,560.5
        $formatter = new MessageFormatter('en_US', 'USD$ {0, number}');
        echo $formatter->format([4560.50]) . "<br/>";

        // Prints ARS$ 1.250,25
        $formatter = new MessageFormatter('es_AR', 'ARS$ {0, number}');
        echo $formatter->format([1250.25]) . "<br/>";

        // Setting parameters
        $time   = time();
        $values = [7, $time, $time];

        // Prints 'At 3:50:31 PM on Apr 19, 2015, there was a disturbance on planet 7.'
        $pattern   = 'At {1, time} on {1, date}, there was a disturbance on planet {0, number}.';
        $formatter = new MessageFormatter('en_US', $pattern);
        echo $formatter->format($values) . "<br/>";

        // Prints 'À 15:53:01 le 19 avr. 2015, il y avait une perturbation sur la planète 7.'
        $pattern   = 'À {1, time} le {1, date}, il y avait une perturbation sur la planète {0, number}.';
        $formatter = new MessageFormatter('fr_FR', $pattern);
        echo $formatter->format($values) . "<br/>";
    }

    //
    // Locale-Sensitive comparison
    //  A : Phalcon Locale-Sensitive Comparison 支援多少語系 ?
    //  Q : 參考 PHP 的 Intl 擴充套件
    //

    //
    // Queueing
    //  處理非同步工作(實際上仍是經由其它軟件整合)
    //   例: 郵件
    //

    /**
     * @Route("/queueing")
     */
    public function QueueingAction()
    {
        /*
        // 連線到 Redis
        $redis = new Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);

        // 將任務放入佇列
        $redis->rpush('my_queue', json_encode([
            'task' => 'send_email',
            'email' => 'user@example.com',
            'message' => 'Hello!',
        ]));

        // 背景工作程序從佇列中取得任務
        $task = json_decode($redis->blpop('my_queue', 0)[1], true);

        // 處理任務
        if ($task['task'] === 'send_email') {
            // 發送電子郵件
            mail($task['email'], 'Email from queue', $task['message']);
        }
        */
    }
}
