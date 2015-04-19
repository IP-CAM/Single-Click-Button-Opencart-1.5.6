<?php

/**
 * Created by PhpStorm.
 * User: Tantacula
 * Date: 27.10.2014
 * Time: 0:05
 */
class ControllerModuleSingleclick extends Controller
{
    private $error = array();

    /**
     * Функция для хэдера шаблона, вставить в хедер и убрать лишнее из vqmod
     */
    public function singleHeader()
    {
        $this->language->load('module/singleclick');
        $this->data['singleclick_name'] = $this->language->get('name');
        $this->data['singleclick_comment'] = $this->language->get('comment');
        $this->data['singleclick_phone'] = $this->language->get('phone');
        $this->data['singleclick_message'] = $this->language->get('message');
        $this->data['singleclick_send_button']
            = $this->language->get('send_button');

        /*
         * $singleclick = ControllerModuleSingleclick::primaryFunction();
         */
    }

    public function index()
    {
        $this->language->load('module/singleclick');

        $this->data['email_subject'] = $this->language->get('email_subject');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')
            && $this->validate()
        ) {

            $product = trim($this->request->post['product_name']);
            $price = trim($this->request->post['product_price']);
            $name = trim($this->request->post['customer_name']);
            $phone = trim($this->request->post['customer_phone']);
            $comment = trim($this->request->post['customer_message']);

            // Ой, устал я от этих языковых файлов.
            $message = "Быстрый заказ" . PHP_EOL . PHP_EOL .
                "Дата заказа: " . date('d.m.Y H:i') . PHP_EOL .
                "Товар: " . $product . " (" . $price . ")" . PHP_EOL .
                "Заказчик: " . $name . PHP_EOL .
                "Телефон: " . $phone . PHP_EOL .
                "Комментарий: " . $comment;

            /*
             * Looks like кому-то было лень написать модель
             */
            $time = time();
            $sql = "INSERT INTO " . DB_PREFIX . "singleclick SET id='',name = '"
                . $this->db->escape($name) . "',phone = '"
                . $this->db->escape($phone) . "',message='"
                . $this->db->escape($message) . "',date='" . $time . "'";
            $query = $this->db->query($sql);


            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($name);
            $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'),
                $name, ENT_QUOTES, 'UTF-8')));
            $mail->setText(strip_tags(html_entity_decode($message, ENT_QUOTES,
                'UTF-8')));
            $mail->send();

            $this->redirect($this->url->link('module/singleclick/success'));
            // return 'Done';
        } else {

            //header("HTTP/1.0 400 Bad Request");
            $resp = array('error' => $this->error);
            $this->response->setOutput(json_encode($resp));

        }


    }

    public function success()
    {
        $success = array('success' => '1');
        $this->response->setOutput(json_encode($success));
    }

    private function validate()
    {

        $this->language->load('module/singleclick');

        if (!isset($this->request->post['customer_name'])
            || (utf8_strlen($this->request->post['customer_name']) < 3)
            || (utf8_strlen($this->request->post['customer_name']) > 32)
        ) {
            $this->error = $this->language->get('error_name');
        }

        if (!isset($this->request->post['customer_phone'])
            || (utf8_strlen($this->request->post['customer_phone']) < 5)
            || (utf8_strlen($this->request->post['customer_phone']) > 32)
        ) {
            $this->error = $this->language->get('error_phone');
        }

        /*
         * Залог на будущее, которое никогда не наступит
         */

        /*
        if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }
        */


        /*
        if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
              $this->error['captcha'] = $this->language->get('error_captcha');
        }
        */

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
