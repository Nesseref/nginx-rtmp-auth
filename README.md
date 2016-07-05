# nginx-rtmp-auth
Backend for handling nginx rtmp module stream authentication

Note: the fun nginx/rtmp stuff is in auth.php/auth.py and nginx.conf. Everything else is a very bare-bones set of scripts to manage user tasks (user registration, id hash/password changes, etc) that could be easily replicated with practically any other web scripting language capable of interaction with a database.

For that matter, the auth scripts could be easily replicated in practically anything else that is capable of database interaction (or, really, even just reading from a .htpassword style file). All the script really needs to do is process a request, check and see if the query string constitutes a valid set of credentials and, if so, return a HTTP 200 and, if not, return a HTTP 400. If you're interested in an authentication system for your nginx rtmp server and posess non-trivial programming experience, this project should be viewed as more of an example than a drop-in solution.

See https://github.com/Nesseref/html5-livestreaming for an example of using the python side of this project to provide authentication for an HTML5 (HLS with JS web player) environment.

# PHP
Requirements:
  - Nginx with RTMP module (https://github.com/arut/nginx-rtmp-module)
  - a MySQL server
  - FastCGI
  - PHP with MySQLi extension
  
Server-side configuration:
  - Set nginx.conf as the live nginx conf (location of file depends on system)
  - Set the web root to an appropriate value
  - Adjust worker_connections to a sane value for the platform
  - Set worker_processes to 1 to work around an issue in the nginx-rtmp module
  - Set the name of the rtmp server application block to whatever is desired (defaults to "stream")
  - Place the .php/.html files in the web root and adjust the on_publish directive url to reflect the location of auth.php
  - Set MySQL-related variables in common.php ($host, $username, $password, $dbname, $usertablename)
  - Set the non-MySQL-related variables in common.php ($streamurl (the URL to stream to minus the id hash at the end, i.e. "rtmp://DOMAIN/stream?" assuming the rtmp server application block name of "stream"), $baseurl (the URL of the web stuff, i.e. if the main page is served at http://stream.frogbox.es and each other page is at the same directory level as the index, the baseurl would be "http://stream.frogbox.es"))
  - Ensure the MySQL server is accepting connections from the user specified in common.php and the user specified in common.php has the correct privileges to read from the defined users table
  - Configure the users table
    - The following schema are expected (but easily changed):
      - username VARCHAR(64)
      - email VARCHAR(64)
      - password VARCHAR(64)
      - idhash VARCHAR(64)
    - Other columns may be added as required

# Python/Flask
Requirements:
  - Nginx with RTMP module (https://github.com/arut/nginx-rtmp-module)
  - a PostgreSQL server
  - Flask, Flask API
  - psycopg2

Server-side configuration:
  - Set nginx.conf as the live nginx conf (location of file depends on system)
  - Set the web root to an appropriate value
  - Set worker_processes to 1 to work around an issue in the nginx-rtmp module
  - Set the name of the rtmp server application block to whatever is desired (defaults to "stream")
  - Adjust the on_publish directive URL to reflect where the auth script is being served from (e.g. with the Flask development server, the URL would be http://localhost:5000/auth)
  - Set PostgreSQL-related variables in config.py (host, username, password, database, usertablename)
  - Ensure the PostgreSQL server is accepting connections from the user specified in common.py and the user specified in common.py has the correct privileges to read from the defined users table
  - Configure the users table
    - The following schema are expected (but easily changed):
      - username VARCHAR(64)
      - email VARCHAR(64)
      - password VARCHAR(64)
      - idhash VARCHAR(64)
    - Other columns may be added as required

# Non-specific

Broadcaster-side configuration (assuming OBS):
  - Under Broadcast Settings, set Custom as the streaming service
  - Set server to "rtmp://DOMAIN/stream?IDHASH" (assumes default rtmp server application block name of "stream")
    - This is referred to as "Stream RTMP URL" in the PHP web stuff
  - Set play path to USERNAME
  
Player-side configuration:
  - The RTMP URL will be in the format "rtmp://DOMAIN/stream/USERNAME" (assumes default rtmp server application block name of "stream")

Known issues:
  - OBS Studio (aka OBS MultiPlatform aka OBS Linux/Mac) versions <0.11.4 mangle variables defined in the server/stream URL when passing them to nginx, meaning that information sent that way (i.e. idhash) cannot be accessed by the authentication script
