Задача модуля - сделать resize изображений и поместить их в 
локальную папку на JINO.ru


set PATH=%PATH%;Y:\ospanel5-3-5\modules\php\PHP_7.2-x64

Apache
<FilesMatch ".(eot|ttf|otf|woff|woff2)">
  <IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
  </IfModule>
</FilesMatch>


With .htaccess you can do it like this:

# ----------------------------------------------------------------------
# Allow loading of external fonts
# ----------------------------------------------------------------------
<FilesMatch "\.(ttf|otf|eot|woff|woff2)$">
    <IfModule mod_headers.c>
        SetEnvIf Origin "http(s)?://(www\.)?(google.com|staging.google.com|development.google.com|otherdomain.example|dev02.otherdomain.example)$" AccessControlAllowOrigin=$0
        Header add Access-Control-Allow-Origin %{AccessControlAllowOrigin}e env=AccessControlAllowOrigin
        Header merge Vary Origin
    </IfModule>
</FilesMatch>


I had the same problem with woff-fonts, multiple subdomains had to have access. To allow subdomains I added something like this to my httpd.conf:

SetEnvIf Origin "^(.*\.example\.com)$" ORIGIN_SUB_DOMAIN=$1
<FilesMatch "\.woff$">
    Header set Access-Control-Allow-Origin "%{ORIGIN_SUB_DOMAIN}e" env=ORIGIN_SUB_DOMAIN
</FilesMatch>
For multiple domains you could just change the regex in SetEnvIf.


<IfModule mod_headers.c>
    <If "%{HTTP:Host} =~ /\\bcdndomain\\.example$/i && %{HTTP:Origin} =~ /\\bmaindomain\\.example$/i">
        Header set Access-Control-Allow-Origin "*"
    </If>
</IfModule>