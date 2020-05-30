Задача модуля - сделать resize изображений и поместить их в 
локальную папку на JINO.ru


set PATH=%PATH%;Y:\ospanel5-3-5\modules\php\PHP_7.2-x64

Apache
<FilesMatch ".(eot|ttf|otf|woff|woff2)">
  <IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
  </IfModule>
</FilesMatch>

