# Symfony API Project

This is a basic API written using Symfony 4 and FOSRestBundle

## Installation

After cloning the repo, make the `public/` directory a document root with you web server.

Be sure the `/var` directory is recursively writable by the web server.

In the root cloned directory, run:
```bash
composer install
```

Replace the following values in the `.env` file with the values for your server:
```bash
DATABASE_URL=mysql://db_user:db_pass@db_host:db_port/db_name
REST_API_HOST=https://www.api.dev
REST_API_VIDEOS_PATH=/public/videos
REST_API_EVENTS_PATH=/public/events
```

Run the database migrations using the command:
```bash
php bin/console doctrine:migrations:migrate
```

## REST Endpoints

### Videos

* `GET /videos` list all videos; these can be filtered using the following URL parameters
  * `name=[video title]`
  * `eventId=[event ID]`

* `POST /videos` add a new video using the following form fields
  * `title`
  * `description`
  * `thumbnail`
  * `playistUrl`
  * `eventId`

* `DELETE /videos` delete a video by passing in the video's `id` as a form field

### Events

* `GET /events` list all events; these can be filtered using the following URL parameters
  * `name`
  * `id`

* `POST /events` add a new event using the following form fields
  * `title`
  * `description`
  * `startDate`
  * `endDate`

* `DELETE /videos` delete a event by passing in the event's `id` as a form field

## License
[MIT](https://choosealicense.com/licenses/mit/)