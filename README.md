### Description

This project using for generating images from video through MovingImage REST API.

### Requirements

* php 5.4+
* ffmpeg

### Installation

* clone the project
* `composer install`

### Running

For ensuring that everything is alright:
1. copy `app/config/prod.json.example` to `app/config/prod.json`
2. add correct credentials userName and passwors to `app/config/prod.json`
3. switch folder to <project_folder>/web/
4. start the php server `php -S localhost:8000`
5. open in the browser url `http://localhost:8000/image/<video_id>/<timestamp>`
