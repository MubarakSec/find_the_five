<?php

session_start();

// --- تدمير  بيانات الجلسة ---
$_SESSION = [];

// ---حذف كوكي الجلسة  ---
/* if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() - 3600, '/');
} */

$params = session_get_cookie_params();
setcookie(
  session_name(),
  '',
  time() - 42000,
  $params['path'],
  $params['domain'],
  $params['secure'],
  $params['httponly']
);
// --- تدمير الجلسة على السيرفر ---
session_destroy();

// --- إعادة التوجيه بعد ثانيتين ليعرض الصفحة رسالة قصيرة قبل الرجوع ---
header("Location:index.php");
exit;
