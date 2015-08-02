# nginx-rtmp-auth
PHP/SQL backend for handling nginx-rtmp module stream authentication

Requirements:
  - Nginx with RTMP module installed (https://github.com/arut/nginx-rtmp-module)
  - a MySQL server
  - FastCGI
  - PHP with MySQLi extension
  
Server-side configuration:
  - Set nginx.conf as the live nginx conf (location of file depends on system)
  - Set the web root to an appropriate value
  - Adjust worker_processes and worker_connections to sane values for the platform
  - Set the name of the rtmp server application block to whatever is desired (defaults to "stream")
  - Place the .php files in the web root and adjust the on_publish directive url to reflect the location of auth.php
  - Set MySQL-related variables in common.php ($host, $username, $password, $dbname, $usertablename)
  - Ensure the MySQL server is accepting connections from the user specified in common.php and the user specified in common.php has the correct privileges to read from the defined users table
  - Configure the users table
    - auth.php expects the following columns:
      - username VARCHAR()
      - idhash VARCHAR()
    - Other columns may be added as required

Broadcaster-side configuration (assuming OBS):
  - Under Broadcast Settings, set Custom as the streaming service
  - Set server to "rtmp://DOMAIN/stream?IDHASH" (assumes default rtmp server application block name of stream)
  - Set play path to USERNAME
  
Player-side configuration:
  - The RTMP URL will be in the format "rtmp://DOMAIN/stream/USERNAME" (assumes default rtmp server application block name of stream)
