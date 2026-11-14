# devmatrixa.alekh.dev

## Overview

Free, no-signup collection of developer & SEO tools — no bloat, no paywalls.

## Technology stack

- Laravel ^13.17
- PHP ^8.5
- Vue.js
- Tailwind CSS
- Vite

## Repository structure

- `.editorconfig`
- `.env.example`
- `.gitattributes`
- `.gitignore`
- `.npmrc`
- `README.md`
- `artisan`
- `bootstrap/app.php`
- `bootstrap/providers.php`
- `composer.json`
- `composer.lock`
- `config/app.php`

## Setup

1. Clone this repository and enter its directory.
2. Run `composer setup` to install PHP dependencies, create `.env`, and generate the application key.
3. Run `composer run dev` to start the PHP development server.

The app uses file-based sessions and cache, and synchronous queues, so no database service or migrations are required. The prebuilt frontend assets are included in `public/build`; Node.js and npm are not needed to run the app. Rebuild those assets only when changing frontend source files.

## Development notes

- Never commit secrets, access tokens, passwords, or production environment files.
- Check configuration and dependency manifests before deploying.
- Replace these general setup notes with project-specific commands when the exact runtime requirements are confirmed.

## License

Check this repository for a LICENSE file before redistributing or reusing the project.


:root {
  /* Brand Colors */
  --primary: #6818E8;
  --primary-dark: #4010B0;
  --blue: #0078E0;
  --cyan: #0098F0;

  /* Typography */
  --text-primary: #102040;
  --text-secondary: #384868;

  /* Supporting */
  --background: #FFFFFF;
  --border: #D9D9E8;

  /* Brand Gradient */
  --brand-gradient: linear-gradient(
    120deg,
    #4010B0 0%,
    #6818E8 35%,
    #0078E0 70%,
    #0098F0 100%
  );
}

#270069
#261B3C
#2B0075