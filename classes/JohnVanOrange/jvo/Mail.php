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