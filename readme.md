# Minimal Viable Blog

This project is an experiment in how little code is required to build a blog.
It aims to be a single file blog tool.

## Setup

Download a copy of this repo. Install the project dependencies using `composer install`. Then, add content to folders in the repo root they will correspond to URLs (`pages/about-us.md` becomes `/pages/about-us/`).

Each page can optionally contain front-matter or metadata in the following format:

```
key: value
another-key: another value.


# Markdown content

Leave two empty lines between the metadata and the markdown content.
```

## Running

Use the local webserver `php -S localhost:8080` or any other.

## Misc

Feedback, issues and merge-requrests welcome.

~Sander
