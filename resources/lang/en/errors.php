<?php

return [

    /*
    |--------------------------------------------------------------------------|
    | Exceptions                                                               |
    |--------------------------------------------------------------------------|
    */

    'game' => [
        'csv_not_found' => 'Cannot find csv files in the folder :folder',
        'duplicated_nick' => "The :nick nickname is not unique in :file file",
        'empty_record' => "In the file :file, the record :index is empty.",
        'fields_mismatch' => "Processing :game file there was an error\n a record doesn't have the required fields: the game has :count_fieldNames field names and the record has :count_fields fields \n:fieldNames\n:fields",
        'folder_not_found' => "Cannot find the folder :folder",
        'game_not_found' => "Cannot find :game game, maybe its not registered.",
        'stats_mismatch' => "The teams scores doesn't match in :file, :team_a kills :team_a_kills :team_b deaths :team_b_deaths, :team_b kills :team_b_kills :team_a deaths :team_a_deaths",
    ]

];
