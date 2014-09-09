<?php
require('E:\code\php\PhpLog\PhpLog.php');
PhpLog::log('begin:This is a  profile log','profile','test'); 
PhpLog::log('This is a  trace log','trace','test'); 
PhpLog::log('This is a  warning log','warning','test'); 
PhpLog::log('This is a  error log','error','test'); 
PhpLog::log('This is a  info log','info','test'); 
PhpLog::log('This is a  nothing log','nothing','test'); 
PhpLog::log('end:This is a  profile log','profile','test'); 

PhpLog::flush();