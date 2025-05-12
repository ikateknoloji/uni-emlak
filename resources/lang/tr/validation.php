<?php

return [
 'required' => ':attribute alanı zorunludur.',
 'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
 'unique' => ':attribute daha önceden alınmış.',
 'min' => [
  'string' => ':attribute en az :min karakter olmalıdır.',
 ],
 'confirmed' => ':attribute doğrulama eşleşmiyor.',
 'password' => [
  'mixed' => ':attribute en az bir büyük harf, bir küçük harf ve bir sayı içermelidir.',
  'symbols' => ':attribute en az bir özel karakter içermelidir.',
  'letters' => ':attribute en az bir harf içermelidir.',
 ],
 'attributes' => [
  'name' => 'isim',
  'email' => 'e-posta',
  'password' => 'şifre',
  'password_confirmation' => 'şifre doğrulama',
 ],
];
