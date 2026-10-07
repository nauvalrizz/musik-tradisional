# MuSantara — Musik Nusantara

An interactive web explorer of traditional Indonesian musical instruments (*alat musik tradisional*). Browse instruments by island, listen to real audio samples, take a quiz, and manage the collection through an admin panel.

**Live demo:** https://musantara.site.je

> Personal portfolio project.

## Features

**Explorer**
- Instrument catalog with photos, descriptions, region of origin, category (*petik / pukul / tiup / gesek / goyang / getar*) and sound-source classification (*idiofon / aerofon / kordofon / membranofon*)
- Browse by island (*pulau*) with an interactive map
- Audio sample playback for each instrument
- Live search

**Quiz**
- Interactive quiz about traditional instruments with instant feedback and result page
- Results stored for statistics

**Admin panel**
- CRUD management of instruments (with image & audio upload)
- Quiz statistics dashboard

## Tech stack

- Laravel 12 (PHP 8.2+)
- MySQL
- Tailwind CSS 4 + Vite
- Vanilla JS (quiz engine, map, audio player)

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# create a MySQL database and set DB_* in .env, then:
php artisan migrate
npm install && npm run build
php artisan serve
```

Import `musik_tradisional.sql` (or run migrations + seeders) to get the instrument dataset.
