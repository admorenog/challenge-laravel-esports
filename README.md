# Challenge eSports
This project is a challenge about eSports players

## Requirements
The project has been made in Laravel 8.67.0 (php ^7.4|^8.0) but I used things of php 7.4 so the requirements are:

- php ^7.4
- composer

## Installation
In the root folder `cp .env.example .env` and `composer install` to install every vendor required by laravel

## Troubleshooting
If there is and error executing the command you can try `composer dumpauto` and `php artisan optimize`, which will reload the registered classes and clear the cache files.

## Tests
I've created a few Unit Tests to ensure the right behaviour of the application with the laravel unit testing.
To execute it just `php artisan test`.
For a global view check the first function of [GameTest.php](tests/Unit/GameTest.php) which has the command behaviour.

## Execution
I've created a command that executes the required task:

`php artisan challenge:best_player`

Accepts an argument (by default `rankings_good`) which indicates the folder to process, the possible values are:

`rankings_good`

`rankings_lol_fixed`

`rankings_valorant_fields_fixed`

`rankings_valorant_fields_rows_fixed`

`rankings_kills_death_fail`

## Main tasks:
 * read csv files with different formats that represents a videogame (nickname is unique for game, but can be in multiple files)
 * check errors and discard everything if something went wrong
 * show the best player in all videogames with a custom system of calc.

## About resources

* The client docs are stored in docs/*, also I wrote a few notes (at the end of this file) about errors that I found and the solutions I have applied.
* The client resources are stored in storage/app/*
* Also, I've created a few folders checking the validations:

  [rankings_good](storage/app/rankings_good) with all records fixed

  [rankings_empty](storage/app/rankings_empty) the folder doesn't contain any csv file 

  [rankings_lol_fixed](storage/app/rankings_lol_fixed) only the lol file fixed

  [rankings_valorant_fields_fixed](storage/app/rankings_valorant_fields_fixed) lol file fixed and valorant fields fixed
  
  [rankings_valorant_fields_rows_fixed](storage/app/rankings_valorant_fields_rows_fixed) lol file fixed and valorant fields and rows fixed (but a nickname stills duplicated)

  [rankings_kills_death_fail](storage/app/rankings_kills_death_fail) The valorant stats doesn't match between teams.
  

## Validations
- The file should have the requested fields in every record
- The file should not contain empty records
- The nickname should be unique
- The kills of one team should match with the deaths of the enemy team.

## Notes
In the file [00_Code Test-Best Multi-eSports Player](docs/00_Code&#32;Test-Best&#32;Multi-eSports&#32;Player.docx) I detected a few errors, but aren't stoppers and if I was wrong about the solution provided can be modified fast in the code when the client clarifies the doubts:

- In the League of legends example

  `E.g. a player playing as a Mid with 10 kills, 5 deaths and no assists will be granted with 2 KDA points ((10 + 0) / 5 ). Aggregating 2000 damage deal and 200 of healing (2 + 2000*0.03 + 200*0.01), the final result is 10 rating points.`

  I get 64 points in `(2 + 2000*0.03 + 200*0.01)` calc instead 10. I don't know if is the damage deal modifier (0.003) or the csv file is wrong in this value too.

- First I though that the valorant file is completely wrong, and I didn't care about the "winner" field, but the general specification is: 
`A player will receive 10 additional rating points if their team won the game. Every game must have a winning team.`

  In Valorant the fields described are:
  `player name, nickname, team name, kills, deaths`

  Between team name and kills I saw `true` or `false` which matches with the League Of Legends file and have sense if we need to calc the score.

- In the Valorant calculation section is `0 Deaths is not a valid value` because the division would break the calculations, I added the same rule to the League of legends scores calculation and applied to the final score.

- I think that I expend more time than expected (I'm not sure how much time, I programmed it in free times between home tasks) because I'm not used to create testing files and write the documentation in english. Also, I had doubts about the folders structure, for this challenge doesn't matter, but some vendors of Laravel framework assumes that the "Models" folder has only classes that extends the "Illuminate" Model class and can be problematic.
