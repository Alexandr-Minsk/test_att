<?php

/**
 * MyTest
 *
 * Класс для решения тестового задания
 *
 * @author Ф.И.О. <asmikhalchyk@gmail.com>
 * @version 1.0
 */

final class MyTest
{
    /**
     * Свойство класса
     *
     * @var object PDO
     */
    
    private $db;

    /**
     * MyTest constructor.
     */

    public function __construct()
    {
        $this->install();
        $this->fill();
    }

    /**
     * Метод класса
     *
     * @return boolean
     */

    private function install(){
        $user='root';
        $password='qqq';
        $this->db= new PDO ('mysql:host=localhost;port=31075;dbname=att', $user, $password);
        $this->db->query(
            "CREATE TABLE myTable (
              id INT NOT NULL AUTO_INCREMENT,
              script_name VARCHAR(25),
              script_execution_time FLOAT,
              script_result VARCHAR(7),
              PRIMARY KEY (id)
            )"
        );
        return true;

    }

    /**
     * Метод класса
     *
     * @return boolean
     */

    private function fill(){
        $status=['active', 'failed', 'success'];
        $st=$this->db->prepare("INSERT INTO myTable VALUES (?, ?, ?, ?)");
        for($i=1; $i<=500; $i++){
            $script_name=$this->getRandomString();
            $script_execution_time=rand(100,999)*0.01;
            $script_result=$status[rand(0,2)];
            $st->execute([$i, $script_name, $script_execution_time, $script_result]);
        }

        return true;
    }

    public function get(){
        $sql="SELECT * FROM `myTable` WHERE `script_result`='failed'";
        $st=$this->db->query($sql);
        $results=$st->fetchAll();

        return $this->view($results);
    }

    /**
     * Метод класса
     *
     * @param integer $length количество символов в случайной строке
     * @return string
     */

    private function getRandomString($length = 24){
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    public function view($data){
        $result='<!DOCTYPE html>
                    <html lang="en">
                      <head>
                        <meta charset="utf-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
                        <title>Bootstrap 101 Template</title>
                    
                        <!-- Bootstrap -->
                        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
                    
                        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
                        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
                        <!--[if lt IE 9]>
                          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
                          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                        <![endif]-->
                      </head>
                      <body>
                        <div class="container">
                        <h1>Hello, world!</h1>
                        <table class="table table-hover">
                         <thead>
                          <tr>
                           <th>#</th>
                           <th>id</th>
                           <th>script_name</th>
                           <th>script_execution_time</th>
                           <th>script_result</th>
                          </tr>
                        </thead>
                        <tbody>';
                            foreach ($data as $k=>$v){
                                $result.='<tr>
                                              <th scope="row">'.($k+1).'</th>
                                               <td>'.$v['id'].'</td>
                                               <td>'.$v['script_name'].'</td>
                                               <td>'.$v['script_execution_time'].'</td>
                                               <td>'.$v['script_result'].'</td>
                                          </tr>';
                            }

        $result.='</tbody>
                        </table>
                        
                        </div>
                        <!-- jQuery (necessary for Bootstrap\'s JavaScript plugins) -->
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                        <!-- Include all compiled plugins (below), or include individual files as needed -->
                        <script src="js/bootstrap.min.js"></script>
                      </body>
                    </html>';
        return $result;
    }
}