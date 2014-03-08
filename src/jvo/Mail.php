<?php
namespace JohnVanOrange\jvo;

class Mail extends \Swift_Message {
 
 private $mailer, $message;

 public function __construct() {
  $transport = \Swift_MailTransport::newInstance();
  $this->mailer = \Swift_Mailer::newInstance($transport);
  $this->message = $this::newInstance();
 }

 public function __destruct() {
 }
 
 public function sendMessage($to, $subject, $body) {
  $setting = new Setting;
  $toEmail = $to[0];
  $toName = NULL;
  if (isset($to[1])) $toName = $to[1];
  $this->message->setFrom(SITE_EMAIL, $setting->get('site_name'))
                ->setTo($toEmail, $toName)
                ->setSubject($subject)
                ->setBody($body);
  return $this->send();
 }
 
 public function sendAdminMessage($subject, $body) {
  $setting = new Setting;
  $site_name = $setting->get('site_name');
  $this->message->setFrom(SITE_EMAIL, $site_name)
                ->setTo(ADMIN_EMAIL, $site_name . ' Admin')
                ->setSubject($subject)
                ->setBody($body);
  return $this->send();
 }
 
 public function setTo($to, $name = NULL) {
  return $this->message->setTo($to, $name);
 }
 
 public function setFrom($from, $name = NULL) {
  return $this->message->setFrom($from, $name);
 }
 
 public function setSubject($subject) {
  return $this->message->setSubject($subject);
 }
 
 public function setBody($body, $contentType = NULL) {
  return $this->message->setBody($body, $contentType);
 }
 
 public function send() {
  return $this->mailer->send($this->message);
 }
 
}