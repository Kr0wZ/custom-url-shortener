# custom-url-shortener
Base code to create your own URL shortener service. It still contains some bugs (and maybe security flaws)

Link to my video (in french) in which I describe all the code: https://youtu.be/BbNEJK9Sr_U

Place all the files in the `/var/www/html` to make it work by default.

To automatically create the database, use the following file this way:

```bash
sudo mysql -u root -p
source /path/to/mysql.txt
```

Do not forget to modify the website configuration file -> `/etc/apache2/sites-enabled/YOUR-FILE.conf`
And add the following lines inside the virtualhost:

```
<Directory "/var/www/html">
  AllowOverride All
</Directory>
```

This will allow the `.htaccess` file to perform redirections.
